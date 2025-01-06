<?php

declare(strict_types=1);

namespace Maginium\Customer\Models;

use Magento\Customer\Model\CustomerRegistry as BaseCustomerRegistry;
use Magento\Customer\Model\Data\CustomerSecure;
use Magento\Customer\Model\Data\CustomerSecureFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\ObjectManager\ResetAfterRequestInterface;
use Magento\Store\Model\StoreManagerInterface;
use Maginium\Customer\Interfaces\Data\CustomerInterface;
use Maginium\Framework\Support\Collection;
use Maginium\Framework\Support\Validator;

/**
 * CustomerRegistry is responsible for managing Customer models and their secure data in a registry.
 * It caches instances of Customer and CustomerSecure models to avoid unnecessary database calls.
 * The registry supports both customer ID and email-based retrieval.
 */
class CustomerRegistry extends BaseCustomerRegistry implements ResetAfterRequestInterface
{
    /**
     * Separator used in the registry key for email-based lookups.
     *
     * This constant defines the delimiter to structure keys in the registry
     * when performing email-based lookups, ensuring consistency and readability.
     */
    public const REGISTRY_SEPARATOR = ':';

    /**
     * @var CustomerFactory CustomerFactory instance for creating customer models
     */
    private $customerFactory;

    /**
     * @var CustomerSecureFactory CustomerSecureFactory instance for creating customer secure data models
     */
    private $customerSecureFactory;

    /**
     * @var Collection Customer registry keyed by customer ID for quick access
     */
    private $customerRegistryById;

    /**
     * @var Collection Customer registry keyed by customer email for quick access
     */
    private $customerRegistryByEmail;

    /**
     * @var Collection Customer registry keyed by customer mobile number number for quick access
     */
    private $customerRegistryByMobileNumber;

    /**
     * @var Collection CustomerSecure registry keyed by customer ID for quick access to secure data
     */
    private $customerSecureRegistryById;

    /**
     * @var StoreManagerInterface StoreManager instance to fetch the current website information
     */
    private $storeManager;

    /**
     * Constructor to initialize dependencies.
     *
     * @param CustomerFactory $customerFactory
     * @param CustomerSecureFactory $customerSecureFactory
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        CustomerFactory $customerFactory,
        StoreManagerInterface $storeManager,
        CustomerSecureFactory $customerSecureFactory,
    ) {
        $this->storeManager = $storeManager;
        $this->customerFactory = $customerFactory;
        $this->customerSecureFactory = $customerSecureFactory;

        // Initialize the registries as collections
        $this->customerRegistryById = collect();
        $this->customerRegistryByEmail = collect();
        $this->customerSecureRegistryById = collect();
        $this->customerRegistryByMobileNumber = collect();
    }

    /**
     * Removes a customer instance from the registry using customer ID.
     * This ensures that the customer's data is no longer cached across the system.
     *
     * @param int $customerId Customer's unique identifier.
     *
     * @return void
     */
    public function remove($customerId): void
    {
        // Check if the customer exists in the registry by customer ID
        if ($this->customerRegistryById->has($customerId)) {
            // Retrieve the customer object using the customer ID
            /** @var Customer $customer */
            $customer = $this->customerRegistryById->get($customerId);

            // Generate the email and mobile number keys using customer data
            $emailKey = $this->getEmailKey($customer->getEmail(), $customer->getWebsiteId());
            $mobileNumberKey = $this->getMobileNumberKey($customer->getMobileNumber(), $customer->getWebsiteId());

            // Unset the customer data from the email registry
            $this->customerRegistryByEmail->forget($emailKey);

            // Unset the customer data from the mobile number registry
            $this->customerRegistryByMobileNumber->forget($mobileNumberKey);

            // Unset the customer data from the ID registry
            $this->customerRegistryById->forget($customerId);

            // Unset the customer data from the secure registry
            $this->customerSecureRegistryById->forget($customerId);
        }
    }

    /**
     * Retrieves a Customer model by its unique customer ID.
     * If the customer cannot be found, a NoSuchEntityException is thrown.
     *
     * @param string $customerId Customer's unique identifier.
     *
     * @throws NoSuchEntityException If the customer cannot be found.
     *
     * @return Customer The retrieved Customer model.
     */
    public function retrieve($customerId): mixed
    {
        // Check if the customer exists in the registry by customer ID
        if ($this->customerRegistryById->has($customerId)) {
            // If customer exists in the registry, return the customer instance
            return $this->customerRegistryById->get($customerId);
        }

        // If not found in registry, load the customer from the database using the customer ID
        /** @var Customer $customer */
        $customer = $this->customerFactory->create()->loadBy($customerId, CustomerInterface::ID);

        // If the customer doesn't exist (ID is empty), throw an exception
        if (! $customer->getId()) {
            throw NoSuchEntityException::singleField('customerId', $customerId);
        }

        // Store the customer in the registry for future retrievals
        $this->customerRegistryById->put($customerId, $customer);

        // Also store the customer in additional registries (by email and mobile number)
        $this->storeCustomerInRegistry($customer);

        // Return the retrieved customer instance
        return $customer;
    }

    /**
     * Retrieves secure data for a customer model by ID.
     * The secure data includes sensitive information like password hash and failed login attempts.
     *
     * @param int $customerId Customer's unique identifier
     *
     * @throws NoSuchEntityException If the customer does not exist
     *
     * @return CustomerSecure The customer secure data model
     */
    public function retrieveSecureData($customerId)
    {
        // Return the secure data from registry if available
        if (isset($this->customerSecureRegistryById[$customerId])) {
            return $this->customerSecureRegistryById[$customerId];
        }

        // Retrieve the customer model and create secure data model
        /** @var Customer $customer */
        $customer = $this->retrieve($customerId);

        /** @var CustomerSecure $customerSecure */
        $customerSecure = $this->customerSecureFactory->create();
        $customerSecure->setPasswordHash($customer->getPasswordHash());
        $customerSecure->setRpToken($customer->getRpToken());
        $customerSecure->setRpTokenCreatedAt($customer->getRpTokenCreatedAt());
        $customerSecure->setDeleteable($customer->isDeleteable());
        $customerSecure->setFailuresNum($customer->getFailuresNum());
        $customerSecure->setFirstFailure($customer->getFirstFailure());
        $customerSecure->setLockExpires($customer->getLockExpires());

        // Store the customer secure data in the registry
        $this->customerSecureRegistryById[$customer->getId()] = $customerSecure;

        return $customerSecure;
    }

    /**
     * Retrieves a Customer model by a given identifier (ID, email, or mobile number).
     * If the identifier is an integer, it tries to get the customer by ID.
     * If the identifier is a valid email, it retrieves the customer by email.
     * If the identifier is a valid mobile number, it retrieves the customer by mobile number.
     * Throws a NoSuchEntityException if no customer is found.
     *
     * @param string $identifier The identifier, which can be an ID (integer), email (string), or mobile number (string).
     * @param int|null $websiteId Optional website ID, defaults to current website if not provided.
     *
     * @throws NoSuchEntityException If the customer cannot be found.
     *
     * @return Customer The retrieved Customer model.
     */
    public function retrieveByIdentifier(string $identifier, ?int $websiteId = null): Customer
    {
        // Check if the identifier is an integer (customer ID)
        if (Validator::isInt($identifier)) {
            return $this->retrieve((int)$identifier);
        }

        // Check if the identifier is a valid email address
        if (Validator::isEmail($identifier)) {
            return $this->retrieveByEmail($identifier, $websiteId);
        }

        // Check if the identifier is a valid mobile number
        if (Validator::isPhoneNumber($identifier)) {
            // Assuming mobile number should be a string and checking if it's valid
            return $this->retrieveByMobileNumber($identifier, $websiteId);
        }

        // If identifier doesn't match any known type, throw NotFound exception
        throw new NoSuchEntityException(__('No customer found for the given identifier.'));
    }

    /**
     * Retrieves a Customer model by email and optionally website ID.
     * If no customer is found, a NoSuchEntityException is thrown.
     *
     * @param string $customerEmail Customer's email address.
     * @param int|null $websiteId Optional website ID, defaults to current website if not provided.
     *
     * @throws NoSuchEntityException If the customer cannot be found.
     *
     * @return Customer The retrieved Customer model.
     */
    public function retrieveByEmail($customerEmail, $websiteId = null)
    {
        // If no website ID is provided, use the current website or default website
        if ($websiteId === null) {
            $websiteId = $this->storeManager->getStore()->getWebsiteId()
                ?: $this->storeManager->getDefaultStoreView()->getWebsiteId();
        }

        // Generate the email key for the given email and website ID
        $emailKey = $this->getEmailKey($customerEmail, $websiteId);

        // Check if the customer exists in the email registry
        if ($this->customerRegistryByEmail->has($emailKey)) {
            // If customer exists, return the customer instance from the registry
            return $this->customerRegistryByEmail->get($emailKey);
        }

        // If not found in registry, create a new customer object and load it by email
        /** @var Customer $customer */
        $customer = $this->customerFactory->create();
        $customer->setWebsiteId($websiteId);
        $customer->loadBy($customerEmail, CustomerInterface::EMAIL);

        // If the customer does not exist (email is empty), throw an exception
        if (! $customer->getEmail()) {
            throw new NoSuchEntityException(
                __('No such model with email = %fieldValue, websiteId = %field2Value', [
                    'fieldValue' => $customerEmail,
                    'field2Value' => $websiteId,
                ]),
            );
        }

        // Store the customer in the registry for future retrievals
        $this->storeCustomerInRegistry($customer);

        // Return the retrieved customer instance
        return $customer;
    }

    /**
     * Retrieves a Customer model by mobile number and optionally website ID.
     * If no customer is found, a NoSuchEntityException is thrown.
     *
     * @param string $customerMobileNumber Customer's mobile number.
     * @param int|null $websiteId Optional website ID, defaults to current website if not provided.
     *
     * @throws NoSuchEntityException If the customer cannot be found.
     *
     * @return Customer The retrieved Customer model.
     */
    public function retrieveByMobileNumber($customerMobileNumber, $websiteId = null)
    {
        // If no website ID is provided, use the current website's ID or the default store view's website ID.
        if ($websiteId === null) {
            $websiteId = $this->storeManager->getStore()->getWebsiteId()
                ?: $this->storeManager->getDefaultStoreView()->getWebsiteId();
        }

        // Generate the mobile number key using the provided mobile number and website ID.
        $mobileNumberKey = $this->getMobileNumberKey($customerMobileNumber, $websiteId);

        // Check if the customer data is already cached in the registry by mobile number.
        if ($this->customerRegistryByMobileNumber->has($mobileNumberKey)) {
            // If found, return the customer data from the registry.
            return $this->customerRegistryByMobileNumber->get($mobileNumberKey);
        }

        // If customer is not cached, load the customer data from the database.
        /** @var Customer $customer */
        $customer = $this->customerFactory->create();
        $customer->setWebsiteId($websiteId); // Set the website ID for the customer.
        $customer->loadBy($customerMobileNumber, CustomerInterface::MOBILE_NUMBER); // Load customer by mobile number.

        // If no customer with the given mobile number is found, throw an exception.
        if (! $customer->getEmail()) {
            throw new NoSuchEntityException(
                __('No such model with mobile number = %fieldValue, websiteId = %field2Value', [
                    'fieldValue' => $customerMobileNumber,
                    'field2Value' => $websiteId,
                ]),
            );
        }

        // Store the retrieved customer in the registry for future lookups.
        $this->storeCustomerInRegistry($customer);

        // Return the retrieved customer.
        return $customer;
    }

    /**
     * Push a new customer instance to the registry.
     * This updates the registry with the latest customer data.
     *
     * @param Customer $customer The customer model to add to the registry
     *
     * @return $this
     */
    public function push($customer)
    {
        // Store the customer by their ID in the registry.
        $this->customerRegistryById->put($customer->getId(), $customer);

        // Generate an email key and store the customer by email in the registry.
        $emailKey = $this->getEmailKey($customer->getEmail(), $customer->getWebsiteId());
        $this->customerRegistryByEmail->put($emailKey, $customer);

        // Generate a mobile number key and store the customer by mobile number in the registry.
        $mobileNumberKey = $this->getMobileNumberKey($customer->getMobileNumber(), $customer->getWebsiteId());
        $this->customerRegistryByMobileNumber->put($mobileNumberKey, $customer);

        // Return the current instance to allow method chaining.
        return $this;
    }

    /**
     * {@inheritDoc}
     * Resets the state of the registry to clear cached customer data.
     * This is invoked after each request to avoid stale data.
     */
    public function _resetState(): void
    {
        // Clear all customer registries by resetting them to empty collections.
        $this->customerRegistryById = collect();
        $this->customerRegistryByEmail = collect();
        $this->customerRegistryByMobileNumber = collect();
        $this->customerSecureRegistryById = collect();
    }

    /**
     * Generate a key for the email-based lookup in the registry.
     *
     * @param string $email The email address of the customer.
     * @param string $websiteId The website ID associated with the customer.
     *
     * @return string A unique key generated by combining the email and website ID.
     */
    protected function getEmailKey($email, $websiteId)
    {
        // Combine the email and website ID using the registry separator to create a unique key.
        return $email . self::REGISTRY_SEPARATOR . $websiteId;
    }

    /**
     * Generate a key for the mobile number-based lookup in the registry.
     *
     * @param string $mobileNumber The mobile number of the customer.
     * @param string $websiteId The website ID associated with the customer.
     *
     * @return string A unique key generated by combining the mobile number and website ID.
     */
    protected function getMobileNumberKey($mobileNumber, $websiteId)
    {
        // Combine the mobile number and website ID using the registry separator to create a unique key.
        return $mobileNumber . self::REGISTRY_SEPARATOR . $websiteId;
    }

    /**
     * Stores customer data into all relevant registries.
     *
     * @param Customer $customer The customer instance to store
     */
    private function storeCustomerInRegistry(Customer $customer)
    {
        // Store the customer by their ID in the registry.
        $this->customerRegistryById->put($customer->getId(), $customer);

        // Generate an email key and store the customer by email in the registry.
        $this->customerRegistryByEmail->put($this->getEmailKey($customer->getEmail(), $customer->getWebsiteId()), $customer);

        // Generate a mobile number key and store the customer by mobile number in the registry.
        $this->customerRegistryByMobileNumber->put($this->getMobileNumberKey($customer->getMobileNumber(), $customer->getWebsiteId()), $customer);
    }
}
