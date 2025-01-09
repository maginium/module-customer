<?php

declare(strict_types=1);

namespace Maginium\CustomerAuth\Actions;

use Magento\Customer\Model\Customer\CredentialsValidator;
use Maginium\Customer\Facades\AccountManagement;
use Maginium\Customer\Facades\CustomerRegistry;
use Maginium\Customer\Facades\CustomerSession;
use Maginium\Customer\Interfaces\Data\CustomerInterface;
use Maginium\Customer\Interfaces\Services\CustomerServiceInterface;
use Maginium\CustomerAuth\Interfaces\ResetPasswordInterface;
use Maginium\Foundation\Enums\HttpStatusCode;
use Maginium\Foundation\Exceptions\Exception;
use Maginium\Foundation\Exceptions\InputException;
use Maginium\Foundation\Exceptions\LocalizedException;
use Maginium\Framework\Actions\Concerns\AsAction;
use Maginium\Framework\Support\Facades\Log;
use Maginium\Framework\Support\Str;

/**
 * Class ResetPassword.
 *
 * Handles password reset requests and manages the process of resetting the customer's password.
 */
class ResetPassword implements ResetPasswordInterface
{
    // Using the AsAction trait to add common action functionality
    use AsAction;

    /**
     * @var CustomerServiceInterface
     */
    private $customerService;

    /**
     * Credentials Validator.
     *
     * @var CredentialsValidator
     */
    private $credentialsValidator;

    /**
     * ResetPassword constructor.
     *
     * @param CustomerServiceInterface $customerService
     */
    public function __construct(
        CredentialsValidator $credentialsValidator,
        CustomerServiceInterface $customerService,
    ) {
        $this->customerService = $customerService;
        $this->credentialsValidator = $credentialsValidator;

        // Set Logger class name
        Log::setClassName(static::class);
    }

    /**
     * Processes a password reset request.
     *
     * This method validates the provided password reset token, resets the customer's
     * password, and returns a response indicating whether the reset was successful or
     * if there were any issues (e.g., invalid token, password strength).
     *
     * @param int $customerId The customer id.
     * @param string $token The reset password token used for verification.
     * @param string $password The new password to set for the customer.
     *
     * @throws LocalizedException If an error occurs during the password reset process.
     * @throws InputException If the provided input data is invalid or insufficient.
     *
     * @return array An associative array containing the response status and a success or error message.
     */
    public function handle(int $customerId, string $token, string $password): array
    {
        /** @var CustomerInterface $customer */
        $customer = CustomerRegistry::retrieve($customerId);

        // Validate the reset token and password
        $this->validateResetPasswordLinkToken((int)$customer->getId(), $token, $password);

        try {
            // Reset the customer's password using the provided token and new password
            AccountManagement::resetPassword($customer->getEmail(), $token, $password);

            // Log the customer out if they were logged in and start a new session
            if (CustomerSession::isLoggedIn()) {
                CustomerSession::logout();
                CustomerSession::start();
            }

            // Clear the reset password token and customer ID from the session
            CustomerSession::unsRpToken();
            CustomerSession::unsRpCustomerId();

            // Return a success response
            return $this->response()
                ->setPayload([]) // Optional: include additional payload data if needed
                ->setStatusCode(HttpStatusCode::OK) // HTTP 200 OK
                ->setMessage(__('Password has been reset successfully.')) // Success message
                ->toArray();
        } catch (LocalizedException|InputException $e) {
            // Propagate service exceptions as-is
            throw $e;
        } catch (Exception $e) {
            // Catch general exceptions and return a localized error message
            throw LocalizedException::make(
                __('We\'re unable to reset the password at the moment. Please try again later.'),
                $e,
                HttpStatusCode::INTERNAL_SERVER_ERROR,
            );
        }
    }

    /**
     * Validate the reset password link token.
     *
     * Verifies that the token is valid for the specified customer.
     *
     * @param int $customerId The customer ID
     * @param string $token The reset password token
     * @param string $password The new password to be set
     *
     * @throws LocalizedException If the token or password is invalid
     * @throws InputException If the password is empty
     */
    private function validateResetPasswordLinkToken(int $customerId, string $token, string $password): void
    {
        // Ensure the customer ID and token are valid
        if ($customerId <= 0 || empty($token)) {
            throw LocalizedException::make(__('Invalid customer ID or reset token.'));
        }

        // Ensure the new password is not empty
        if (Str::length($password) <= 0) {
            throw InputException::make(__('Please enter a new password.'));
        }
    }
}
