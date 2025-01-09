<?php

declare(strict_types=1);

namespace Maginium\CustomerAuth\Actions;

use Magento\Customer\Api\AccountManagementInterface;
use Maginium\Checkout\Facades\CheckoutSession;
use Maginium\Customer\Facades\AccountManagement;
use Maginium\Customer\Facades\CustomerSession;
use Maginium\Customer\Facades\CustomerUrl;
use Maginium\Customer\Interfaces\Data\CustomerInterface;
use Maginium\Customer\Interfaces\Services\CustomerServiceInterface;
use Maginium\Customer\Models\Customer;
use Maginium\CustomerAuth\Dtos\RegisterDto;
use Maginium\CustomerAuth\Interfaces\RegisterInterface;
use Maginium\Foundation\Enums\HttpStatusCode;
use Maginium\Foundation\Exceptions\Exception;
use Maginium\Foundation\Exceptions\LocalizedException;
use Maginium\Framework\Actions\Concerns\AsAction;
use Maginium\Framework\Support\Arr;
use Maginium\Framework\Support\Facades\Escaper;
use Maginium\Framework\Support\Facades\Event;
use Maginium\Framework\Support\Facades\Log;
use Maginium\Framework\Token\Facades\CustomerTokenService;

/**
 * Class Register
 * Handles customer registration including account creation, session management,
 * event triggering, and email confirmation handling.
 */
class Register implements RegisterInterface
{
    // Using the AsAction trait to add common action functionality
    use AsAction;

    /**
     * @var CustomerServiceInterface The service interface for customer-related operations.
     */
    private CustomerServiceInterface $customerService;

    /**
     * Constructor for Register action.
     *
     * @param CustomerServiceInterface $customerService The customer service dependency.
     */
    public function __construct(CustomerServiceInterface $customerService)
    {
        $this->customerService = $customerService;

        // Set the logger to use this class name for contextual logging
        Log::setClassName(static::class);
    }

    /**
     * Main entry point to handle customer registration.
     *
     * @param RegisterDto $data A DTO containing the registration details.
     *
     * @throws LocalizedException For domain-specific errors during registration.
     * @throws Exception For any unexpected errors during execution.
     *
     * @return array A structured response containing the status and additional data.
     */
    public function handle(RegisterDto $data): array
    {
        try {
            // Regenerate the session ID to avoid session fixation attacks.
            CustomerSession::regenerateId();

            // Start the emulation process for the current website.
            $this->startEmulation();

            // Prepare the customer model using the DTO data.
            $customerData = $this->prepareCustomer($data);

            // Process account creation and trigger necessary events.
            $customer = $this->processCustomerAccount($customerData, $data);

            // Retrieve the confirmation status for the newly created account.
            $confirmationStatus = AccountManagement::getConfirmationStatus($customer->getId());

            // Handle cases where email confirmation is required.
            if ($confirmationStatus === AccountManagementInterface::ACCOUNT_CONFIRMATION_REQUIRED) {
                return $this->handleEmailNotConfirmed($data);
            }

            // Generate a unique token for the authenticated customer
            $token = $this->generateCustomerToken($customer);

            // CheckoutSession::loadCustomerQuote();

            // Merge customer data with the generated token for the response payload
            $customerData = Arr::merge($customer->toDataArray(), $token);

            // Prepare the response with the payload, status code, success message, and meta information
            $response = $this->response()
                ->setPayload($customerData) // Set the payload
                ->setStatusCode(HttpStatusCode::OK) // Set HTTP status code to 200 (OK)
                ->setMessage(__('Customer registration successfully')); // Set a success message with the model name

            // Return the formatted result as an associative array
            return $response->toArray();
        } catch (LocalizedException $e) {
            // Rethrow the exception for further handling
            throw $e;
        } catch (Exception $e) {
            // Wrap and rethrow the exception with additional details and an HTTP 500 status code
            throw LocalizedException::make(
                __('An error occurred during customer registration: %1', $e->getMessage()), // Localized error message with details
                $e, // Attach the original exception
                HttpStatusCode::INTERNAL_SERVER_ERROR, // Set the HTTP status code to 500 (Internal Server Error)
            );
        }
    }

    /**
     * Prepares the customer instance from the provided registration data.
     *
     * @param RegisterDto $data The DTO containing registration details.
     *
     * @return object The prepared customer instance with data set.
     */
    private function prepareCustomer(RegisterDto $data)
    {
        // Create a new customer instance using the service factory.
        /** @var Customer $customer */
        $customer = $this->customerService->factory();

        // Populate the customer model with values from the $data object

        // Set the website ID
        $customer->setWebsiteId($this->getWebsiteId());

        // Set the store ID
        $customer->setStoreId($this->getStoreId());

        // Set the customer's date of birth
        $customer->setDob($data->getDob());

        // Set the customer's gender
        $customer->setGender($data->getGender());

        // Set the customer's prefix
        $customer->setPrefix($data->getPrefix());

        // Set the customer's suffix
        $customer->setSuffix($data->getSuffix());

        // Set the customer's group ID
        $customer->setGroupId($data->getGroup());

        // Set the customer's email address
        $customer->setEmail($data->getEmail());

        // Set the customer's last name
        $customer->setLastname($data->getLastname());

        // Set the customer's first name
        $customer->setFirstname($data->getFirstname());

        // Set the customer's tax identification number (taxvat)
        $customer->setTaxvat($data->getTaxvat());

        // Set a custom attribute for the mobile number
        // $customer->setCustomAttribute(
        //     CustomerInterface::MOBILE_NUMBER, // The custom attribute code for the mobile number
        //     $data->getPhone(), // Get the phone number from the $data object
        // );

        $customer->setCustomAttribute(
            'payment_preference', // The custom attribute code for the mobile number
            null, // Get the phone number from the $data object
        );

        return $customer;
    }

    /**
     * Processes the customer account creation and triggers post-registration events.
     *
     * @param object $customer The prepared customer model.
     * @param RegisterDto $data The DTO containing registration details.
     *
     * @return CustomerInterface The account confirmation status.
     */
    private function processCustomerAccount($customer, RegisterDto $data): CustomerInterface
    {
        // Create the customer account and persist it to the database.
        $customer = $this->customerService->createAccount($customer, $data->getPassword(), $this->getWebsiteId());

        // Retrieve the confirmation status for the newly created account.
        return $customer;
    }

    /**
     * Generates a token for the authenticated customer.
     *
     * This method generates an authentication token for the customer to be used in future requests.
     *
     * @param CustomerInterface $customer The authenticated customer instance.
     *
     * @return array The generated token data, including the token for the customer.
     */
    private function generateCustomerToken(CustomerInterface $customer): array
    {
        // Create and return a customer-specific token.
        return [
            self::TOKEN => CustomerTokenService::create((int)$customer->getId()),
        ];
    }

    /**
     * Handles scenarios where email confirmation is required.
     *
     * @param RegisterDto $data The registration data containing customer email.
     *
     * @return array A response indicating that email confirmation is required.
     */
    private function handleEmailNotConfirmed(RegisterDto $data): array
    {
        // Generate the URL for email confirmation.
        $url = CustomerUrl::getEmailConfirmationUrl($data->getEmail());

        // Generate the email confirmation message including the URL.
        $message = $this->getEmailConfirmationMessage($url);

        // Create and return a structured response indicating email confirmation is pending.
        return $this->response()
            ->setPayload(['url' => $url, 'message' => $message])
            ->setStatusCode(HttpStatusCode::OK)
            ->setMessage($message)
            ->toArray();
    }

    /**
     * Generates an email confirmation message with a clickable link.
     *
     * @param string $url The confirmation URL.
     *
     * @return string A formatted message with the confirmation link.
     */
    private function getEmailConfirmationMessage(string $url): string
    {
        // Escape HTML to prevent XSS while allowing anchor tags for the link.
        return Escaper::escapeHtml(
            __('You must confirm your account. Please check your email for the confirmation link or <a href="%1">Click here</a> to resend confirmation email.', $url),
            ['a'],
        );
    }
}
