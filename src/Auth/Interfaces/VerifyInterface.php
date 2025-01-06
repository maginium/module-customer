<?php

declare(strict_types=1);

namespace Maginium\CustomerAuth\Interfaces;

/**
 * Interface VerifyInterface.
 *
 * This interface defines the contract for verifying customer existence based on an identifier (email or phone number).
 * It also provides the available verification methods for resetting the customer's password.
 */
interface VerifyInterface
{
    /**
     * The key representing the authentication type (e.g., email, phone).
     */
    public const TYPE = 'type';

    /**
     * The key representing the actual value (e.g., email address, phone number).
     */
    public const VALUE = 'value';

    /**
     * The key representing the masked value (e.g., partially hidden email or phone number).
     */
    public const MASK = 'mask';

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
     * @return string[] An array containing the available verification methods (email, phone) for the password reset.
     */
    public function handle(string $identifier): array;
}
