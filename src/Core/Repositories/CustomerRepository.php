<?php

declare(strict_types=1);

namespace Maginium\Customer\Repositories;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory as CustomerCollectionFactory;
use Magento\Framework\Exception\NoSuchEntityException;
// use Magento\Customer\Api\Data\CustomerInterfaceFactory as ModelFactory;
use Maginium\Customer\Facades\CustomerRegistry;
use Maginium\Customer\Facades\CustomerSession;
use Maginium\Customer\Interfaces\Data\CustomerInterface;
use Maginium\Customer\Interfaces\Data\CustomerInterfaceFactory as ModelFactory;
use Maginium\Customer\Interfaces\Repositories\CustomerRepositoryInterface;
use Maginium\Customer\Models\Customer;
use Maginium\Foundation\Exceptions\AuthenticationException;
use Maginium\Foundation\Exceptions\Exception;
use Maginium\Foundation\Exceptions\InvalidArgumentException;
use Maginium\Foundation\Exceptions\LocalizedException;
use Maginium\Foundation\Exceptions\NotFoundException;
use Maginium\Framework\Crud\Repository;
use Maginium\Framework\Support\Facades\Event;
use Maginium\Framework\Support\Facades\Log;
use Maginium\Framework\Support\Validator;

/**
 * Class CustomerRepository.
 *
 * This class extends the base `CustomerRepository` and implements custom functionality for handling customers.
 */
class CustomerRepository // extends Repository implements CustomerRepositoryInterface
{
    /**
     * Event triggered when a customer successfully registers.
     */
    public const CUSTOMER_REGISTER_SUCCESS = 'customer_register_success';

    /**
     * @var AccountManagementInterface
     */
    private $accountManagement;

    /**
     * CustomerRepository constructor.
     *
     * @param ModelFactory $model The customer model factory.
     * @param CustomerCollectionFactory $collection The customer collection factory.
     * @param AccountManagementInterface $accountManagement The account management service for authentication.
     */
    public function __construct(
        ModelFactory $model,
        CustomerCollectionFactory $collection,
        AccountManagementInterface $accountManagement,
    ) {
        // parent::__construct($model, $collection);

        $this->accountManagement = $accountManagement;
    }

    /**
     * Check if a customer exists by identifier.
     *
     * This method determines whether a customer exists based on their identifier (ID or email)
     * and an optional website ID. Throws an exception if the identifier is invalid or empty.
     *
     * @param int|string $identifier The customer identifier (ID or email).
     * @param int|null $websiteId The website ID to scope the search, or null for the default scope.
     *
     * @throws InvalidArgumentException If the identifier is empty or invalid.
     *
     * @return bool True if the customer exists, otherwise false.
     */
    public function isExists(int|string $identifier, ?int $websiteId = null): bool
    {
        // Validate the identifier to ensure it is not empty
        if (empty($identifier)) {
            throw InvalidArgumentException::make(__('Customer identifier cannot be empty.'));
        }

        try {
            // Attempt to retrieve the customer by identifier
            /** @var Customer $customer */
            $customer = CustomerRegistry::retrieveByIdentifier($identifier, $websiteId);

            // Return true if the customer exists and has a valid ID
            return $customer !== null && $customer->getId() > 0;
        } catch (Exception $e) {
            // Log the exception for debugging and return false
            Log::warning('Failed to check customer existence: ' . $e->getMessage(), [
                'identifier' => $identifier,
                'websiteId' => $websiteId,
            ]);

            return false;
        }
    }

    /**
     * Create a customer account.
     *
     * This method handles the creation of a customer account, performing necessary business operations
     * such as validating the email, creating the account, and performing any additional post-creation tasks.
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer The registration data transfer object containing customer details.
     * @param int|null $websiteId The website ID to associate the customer with, or null for the default.
     *
     * @throws LocalizedException If the email is already associated with an existing customer.
     *
     * @return CustomerInterface|false The created customer object on success, or false on failure.
     */
    public function createAccount(
        \Magento\Customer\Api\Data\CustomerInterface $customer,
        $password = null,
        ?int $websiteId = null,
    ): CustomerInterface|false {
        // Check if a customer with the provided email already exists
        if ($this->isExists($customer->getEmail(), $websiteId)) {
            throw LocalizedException::make(__('A customer with the same email already exists.'));
        }

        try {
            // Attempt to create the customer account
            $registeredCustomer = $this->accountManagement->createAccount(
                $customer,
                $password,
            );

            // Ensure the customer creation was successful
            if (! $registeredCustomer || ! $registeredCustomer->getId()) {
                return false;
            }

            // Log in the customer by setting their session data.
            CustomerSession::setCustomerDataAsLoggedIn($registeredCustomer);

            // Dispatch a custom event signaling successful registration.
            Event::dispatchNow(self::CUSTOMER_REGISTER_SUCCESS, ['customer' => $registeredCustomer]);

            // Retrieve the customer model by identifier (email or ID)
            /** @var Customer $customer */
            $customer = CustomerRegistry::retrieve($registeredCustomer->getId());

            // If the customer doesn't exist, return false
            if (! $customer->getId()) {
                return false;
            }

            // Return the newly created customer object
            return $customer;
        } catch (LocalizedException $e) {
            // Log the error for debugging and rethrow it
            Log::error('Customer account creation failed: ' . $e->getMessage());

            throw $e;
        } catch (Exception $e) {
            // Handle unexpected errors
            Log::critical('An unexpected error occurred during account creation: ' . $e->getMessage());

            return false;
        }
    }

    /**
     * Authenticate a customer by their identifier and password.
     *
     * @param int|string $identifier The customer email or ID to check or mobile number.
     * @param string $password The password for authentication.
     * @param int|null $websiteId The website ID to which the customer belongs. Defaults to null for the default website.
     *
     * @throws NotFoundException If the model with the given identifier does not exist.
     * @throws LocalizedException If an error occurs during the authentication process.
     *
     * @return CustomerInterface|false The authenticated customer model if successful, or false if authentication fails.
     */
    public function authenticate(int|string $identifier, string $password, ?int $websiteId = null): CustomerInterface|false
    {
        if (empty($identifier) || empty($password)) {
            throw InvalidArgumentException::make('Email and password cannot be empty.');
        }

        try {
            // Retrieve the customer model by identifier (email or ID)
            /** @var Customer $customer */
            $customer = CustomerRegistry::retrieveByIdentifier($identifier, $websiteId);

            // If the customer doesn't exist, return false
            if (! $customer->getId()) {
                return false;
            }

            // Attempt to authenticate the customer using the account management service
            $loggedInCustomer = $this->accountManagement->authenticate($customer->getEmail(), $password);

            // Return false if authentication fails
            if (! $loggedInCustomer->getId()) {
                return false;
            }

            // Log in the customer by setting their session data.
            CustomerSession::setCustomerDataAsLoggedIn($loggedInCustomer);

            // Return the authenticated customer model
            return $customer;
        } catch (NoSuchEntityException|LocalizedException $e) {
            // Propagate repository exceptions as-is
            throw $e;
        } catch (Exception $e) {
            // Catch any general exceptions and rethrow a localized exception with a generic error message
            throw AuthenticationException::make(__('An error occurred while authenticating the customer.'));
        }
    }

    /**
     * Retrieve a customer model by email.
     *
     * @param string $email The customer email to retrieve.
     * @param int|null $websiteId Optional website ID, defaults to current website if not provided.
     *
     * @return CustomerInterface The customer model.
     */
    public function getByEmail(string $email, ?int $websiteId = null): CustomerInterface
    {
        return $this->getCustomerByIdentifier($email, $websiteId);
    }

    /**
     * Retrieve a customer model by mobile number.
     *
     * @param string $mobileNumber The customer mobile number to retrieve.
     * @param int|null $websiteId Optional website ID, defaults to current website if not provided.
     *
     * @return CustomerInterface The customer model.
     */
    public function getByMobileNumber(string $mobileNumber, ?int $websiteId = null): CustomerInterface
    {
        return $this->getCustomerByIdentifier($mobileNumber, $websiteId);
    }

    /**
     * Retrieve a customer model by email or mobile number.
     *
     * @param string $identifier The customer email or mobile number to retrieve.
     * @param int|null $websiteId Optional website ID, defaults to current website if not provided.
     *
     * @throws NotFoundException If the customer with the given identifier (email or mobile number) is not found.
     *
     * @return CustomerInterface The customer model.
     */
    private function getCustomerByIdentifier(string $identifier, ?int $websiteId = null): CustomerInterface
    {
        // Create new csutomer object
        $customer = $this->factory();

        // Attempt to load the customer based on email or mobile number
        if (Validator::isEmail($identifier)) {
            // If it's an email, load by email address
            $customer = $customer->loadBy($identifier, CustomerInterface::EMAIL, $websiteId);
        } else {
            // If it's a mobile number, load by mobile number
            $customer = $customer->loadBy($identifier, CustomerInterface::MOBILE_NUMBER, $websiteId);
        }

        if (! $customer || ! $customer->getId()) {
            throw NotFoundException::make(__('Customer not found.'));
        }

        return $customer;
    }
}
