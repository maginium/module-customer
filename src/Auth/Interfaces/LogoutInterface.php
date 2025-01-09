<?php

declare(strict_types=1);

namespace Maginium\CustomerAuth\Interfaces;

use Maginium\Foundation\Exceptions\Exception;

/**
 * Interface LogoutInterface.
 *
 * This interface defines the contract for logging out a customer. It includes the method to invalidate
 * the customer's session or access token, ensuring that the customer is logged out and cannot access protected
 * resources without re-authentication.
 */
interface LogoutInterface
{
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
     * @return string[] An array containing a success message and HTTP status code indicating the result.
     */
    public function handle(int $customerId): array;
}
