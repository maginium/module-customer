<?php

declare(strict_types=1);

namespace Maginium\CustomerAuth\Actions;

use Maginium\Customer\Facades\CustomerSession;
use Maginium\CustomerAuth\Interfaces\LogoutInterface;
use Maginium\Foundation\Enums\HttpStatusCode;
use Maginium\Foundation\Exceptions\Exception;
use Maginium\Foundation\Exceptions\LocalizedException;
use Maginium\Framework\Actions\Concerns\AsAction;
use Maginium\Framework\Support\Facades\Log;
use Maginium\Framework\Support\Facades\Token;

/**
 * Class Logout.
 */
class Logout implements LogoutInterface
{
    // Using the AsAction trait to add common action functionality
    use AsAction;

    /**
     * Constructor to initialize dependencies for the Logout action.
     */
    public function __construct()
    {
        // Sets the class name for logging purposes
        Log::setClassName(static::class);
    }

    /**
     * Logs out the customer by invalidating the provided access token.
     *
     * This method checks the provided access token and invalidates it, ensuring that the customer's session
     * is terminated and access to protected resources is revoked. If the token is invalid or does not exist,
     * an exception will be thrown.
     *
     * @param int $customerId The customer's id.
     *
     * @throws NotFoundException If no active session is found with the provided access token.
     * @throws LocalizedException If an unexpected error occurs during the logout process.
     *
     * @return array An array containing a success message and HTTP status code indicating the result.
     */
    public function handle(int $customerId): array
    {
        try {
            // Logout the customer session.
            CustomerSession::logout();

            // Revoke the customer's access token.
            Token::customer()->revoke($customerId);

            // Set last login customer id
            CustomerSession::setLastCustomerId($customerId);

            // Prepare the response with the payload, status code, success message, and meta information
            $response = $this->response()
                ->setStatusCode(HttpStatusCode::OK) // Set HTTP status code to 200 (OK)
                ->setMessage(__('Customer logged out successfully')); // Set a success message with the model name

            // Return the formatted result as an associative array
            return $response->toArray();
        } catch (LocalizedException $e) {
            // Propagate service exceptions as-is
            throw $e;
        } catch (Exception $e) {
            // Catch any general exceptions and rethrow a localized exception with a generic error message
            throw LocalizedException::make(
                __('An error occurred during customer logout: %1', $e->getMessage()),
                $e,
                HttpStatusCode::INTERNAL_SERVER_ERROR,
            );
        }
    }
}
