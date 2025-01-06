<?php

declare(strict_types=1);

namespace Maginium\CustomerAuth\Actions;

use Exception;
use Maginium\Customer\Facades\CustomerRegistry;
use Maginium\Customer\Models\Customer;
use Maginium\CustomerAuth\Enums\Strategies;
use Maginium\CustomerAuth\Helpers\Data as CustomerAuthHelper;
use Maginium\CustomerAuth\Interfaces\VerifyInterface;
use Maginium\Foundation\Enums\HttpStatusCode;
use Maginium\Foundation\Exceptions\LocalizedException;
use Maginium\Foundation\Exceptions\NotFoundException;
use Maginium\Framework\Actions\Concerns\AsAction;
use Maginium\Framework\Support\Facades\Log;

/**
 * Class Verify
 * Handles customer verification for password reset by checking
 * the availability of authentication methods (email, phone).
 */
class Verify implements VerifyInterface
{
    // Using the AsAction trait to add common action functionality
    use AsAction;

    /**
     * Constructor to initialize dependencies for the Verify action.
     */
    public function __construct()
    {
        // Sets the class name for logging purposes
        Log::setClassName(static::class);
    }

    /**
     * Verifies the customer's existence using the provided identifier (email or phone number)
     * and returns the available verification methods for resetting the password.
     *
     * This method checks whether the provided identifier (email or phone number) exists in the system,
     * and if the customer is found, it returns the available authentication methods (email, phone) for the password reset.
     * If the customer is not found, a NotFoundException is thrown.
     *
     * @param string $identifier The identifier (email or phone number) to verify for customer existence.
     *
     * @throws NotFoundException If no customer is found with the provided identifier.
     * @throws LocalizedException If an unexpected error occurs during the verification process.
     *
     * @return array An array containing the available verification methods (email, phone) for the password reset.
     */
    public function handle(string $identifier): array
    {
        try {
            // Start emulation of the store environment to check customer details
            $this->startEmulation();

            // Retrieve the customer data based on the identifier (email or phone)
            $customer = CustomerRegistry::retrieveByIdentifier($identifier, $this->getWebsiteId());

            // Prepare verification data for authentication methods (email and phone)
            $verifyData = $this->prepareData($customer);

            // Return the response with verification data and status code
            return $this->response()
                ->setPayload($verifyData) // Set the response payload with verification data
                ->setStatusCode(HttpStatusCode::OK) // Set HTTP status code to OK (200)
                ->setMessage(__('Customer verification data retrieved successfully.')) // Success message
                ->toArray(); // Return response as an associative array
        } catch (NotFoundException|LocalizedException $e) {
            // If a NotFoundException or LocalizedException is thrown, rethrow it
            throw $e;
        } catch (Exception $e) {
            // Catch any general exceptions and throw them as localized exceptions with a message
            throw LocalizedException::make(
                __('An error occurred while retrieving verification data: %1', $e->getMessage()),
                $e,
                HttpStatusCode::INTERNAL_SERVER_ERROR, // Return internal server error status
            );
        }
    }

    /**
     * Prepares the verification data for available authentication methods (email, phone).
     *
     * This method collects the customer's email and mobile number (if available)
     * and prepares the corresponding verification data to be returned.
     *
     * @param Customer $customer The customer model.
     *
     * @return array The list of verification methods (email, phone).
     */
    protected function prepareData(Customer $customer): array
    {
        $verifyData = [];

        // Check if the customer has a valid email and add it to the verification data
        if ($email = $customer->getEmail()) {
            $verifyData[] = $this->buildVerificationItem($email, Strategies::EMAIL);
        }

        // Check if the customer has a valid mobile number and add it to the verification data
        if ($mobileNumber = $customer->getMobileNumber()) {
            $verifyData[] = $this->buildVerificationItem($mobileNumber, Strategies::PHONE);
        }

        return $verifyData;
    }

    /**
     * Builds a verification data item for either email or phone.
     *
     * This method creates a verification item with the raw value (email or phone),
     * its masked version for security purposes, and the authentication type.
     *
     * @param string $value The raw value (email or phone).
     * @param string $type  The type of authentication (email or phone).
     *
     * @return array A verification data item with type, value, and masked value.
     */
    protected function buildVerificationItem(string $value, string $type): array
    {
        return [
            static::TYPE => $type, // Authentication type (email/phone)
            static::VALUE => $value, // Raw value (email or phone)
            static::MASK => CustomerAuthHelper::mask($value, $type), // Masked value for security
        ];
    }
}
