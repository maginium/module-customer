<?php

declare(strict_types=1);

namespace Maginium\CustomerAuth\Interfaces;

use Maginium\CustomerAuth\Dtos\UpdateDto;
use Maginium\Foundation\Exceptions\Exception;

/**
 * Interface UpdateInterface.
 *
 * This interface defines the contract for updating customer data in the system.
 * Implementing classes should handle the logic for verifying and updating customer details,
 * including handling validation and error management for the update process.
 */
interface UpdateInterface
{
    /**
     * Updates the customer data based on the provided information.
     *
     * This method processes the customer update by accepting an `UpdateDto` object containing
     * customer details such as email, phone number, password, and other optional information.
     * It performs necessary checks, such as verifying whether the customer exists and ensuring
     * that the new data is valid. On successful update, the customer data is modified and a success
     * message is returned. If the customer does not exist, or if an error occurs, an exception is thrown.
     *
     * @param UpdateDto $data A data transfer object containing the details to update the customer information.
     *
     * @throws NotFoundException If the customer to be updated cannot be found based on the provided identifier (e.g., email, phone number).
     * @throws LocalizedException If an error occurs while processing the update request (e.g., validation failure, invalid data).
     *
     * @return string[] An array with a success message and the HTTP status code indicating the update result.
     */
    public function handle(UpdateDto $data): array;
}
