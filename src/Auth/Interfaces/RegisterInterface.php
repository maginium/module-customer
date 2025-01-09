<?php

declare(strict_types=1);

namespace Maginium\CustomerAuth\Interfaces;

use Maginium\CustomerAuth\Dtos\RegisterDto;
use Maginium\Foundation\Exceptions\Exception;

/**
 * Interface RegisterInterface.
 *
 * This interface defines the contract for registering a new customer in the system.
 * Implementing classes should handle the logic for verifying customer details, creating
 * new customer accounts, and managing related processes such as validation and error handling.
 */
interface RegisterInterface
{
    /**
     * Key used to store the customer authentication token.
     */
    public const TOKEN = 'token';

    /**
     * Registers a new customer based on the provided registration data.
     *
     * This method processes the registration by accepting a `RegisterDto` object containing
     * customer information such as email, phone number, password, and optional details.
     * It performs necessary checks, such as verifying whether the customer already exists.
     * On successful registration, the customer is created and a success message is returned.
     * If the customer already exists or an error occurs, an exception is thrown.
     *
     * @param RegisterDto $data A data transfer object containing the registration details of the customer.
     *
     * @throws NotFoundException If the customer already exists based on the provided identifier (email, phone number).
     * @throws LocalizedException If an error occurs while processing the registration request (e.g., validation failure).
     *
     * @return string[] An array with a success message and the HTTP status code indicating the registration result.
     */
    public function handle(RegisterDto $data): array;
}
