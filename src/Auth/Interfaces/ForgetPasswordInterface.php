<?php

declare(strict_types=1);

namespace Maginium\CustomerAuth\Interfaces;

use Maginium\Foundation\Exceptions\LocalizedException;

/**
 * Interface for handling forgot password functionality.
 *
 * This interface defines the contract for processing a password reset request.
 * It validates the provided email, initiates the reset process, and sends a reset
 * link to the customer's email address if the request is valid.
 */
interface ForgetPasswordInterface
{
    /**
     * Processes a password reset request for the specified email.
     *
     * This method validates the provided email address, checks if the email is associated
     * with a valid customer, and triggers the sending of a password reset link via email.
     *
     * If the email is invalid or the customer cannot be found, the relevant exceptions
     * (`LocalizedException`, `SecurityViolationException`, or `NoSuchEntityException`) will be thrown.
     *
     * @param string $email The email address of the customer requesting the password reset.
     *
     * @throws LocalizedException If an error occurs during the validation or processing of the email.
     * @throws SecurityViolationException If the email is associated with multiple customer accounts.
     * @throws NoSuchEntityException If no customer exists with the provided email.
     *
     * @return array|null A success message with a status code if the reset request is successful, or null if there's no further action required.
     */
    public function handle(string $email): ?array;
}
