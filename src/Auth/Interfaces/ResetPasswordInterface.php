<?php

declare(strict_types=1);

namespace Maginium\CustomerAuth\Interfaces;

use Maginium\Foundation\Exceptions\LocalizedException;

/**
 * Interface for handling password reset functionality.
 *
 * This interface defines the contract for processing a password reset request,
 * including validating the reset token, resetting the user's password, and returning
 * the appropriate response message.
 */
interface ResetPasswordInterface
{
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
    public function handle(int $customerId, string $token, string $password): array;
}
