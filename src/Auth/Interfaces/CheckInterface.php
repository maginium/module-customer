<?php

declare(strict_types=1);

namespace Maginium\CustomerAuth\Interfaces;

/**
 * Interface for verifying customer existence.
 *
 * This interface defines the contract for checking if a customer exists in the system
 * based on a unique identifier (such as an email or mobile number). If the customer
 * exists, a success message is returned; if not, a `NotFoundException` is thrown.
 */
interface CheckInterface
{
    /**
     * Verifies if a customer exists based on the provided identifier.
     *
     * This method checks if a customer with the specified identifier (e.g., email or mobile number)
     * exists in the store. If the customer is found, it returns a success message. If no customer is found,
     * a `NotFoundException` is thrown to indicate the absence of the customer.
     *
     * @param string $identifier The identifier (such as email or mobile number) to check for customer existence.
     *
     * @throws NotFoundException If no customer is found with the provided identifier.
     * @throws LocalizedException If an unexpected error occurs during the existence check process.
     *
     * @return string[] An array containing a success message and an HTTP status code indicating the result of the check.
     */
    public function handle(string $identifier): array;
}
