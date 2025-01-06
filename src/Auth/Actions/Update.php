<?php

declare(strict_types=1);

namespace Maginium\CustomerAuth\Actions;

use Exception;
use Maginium\Customer\Interfaces\Services\CustomerServiceInterface;
use Maginium\Customer\Models\Customer;
use Maginium\CustomerAuth\Dtos\UpdateDto;
use Maginium\CustomerAuth\Interfaces\UpdateInterface;
use Maginium\Foundation\Enums\HttpStatusCode;
use Maginium\Foundation\Exceptions\LocalizedException;
use Maginium\Framework\Actions\Concerns\AsAction;
use Maginium\Framework\Support\Facades\Log;

/**
 * Class Update
 * Handles updating customer data in the system.
 * Implementing classes should handle the logic for verifying and updating customer details,
 * including handling validation and error management for the update process.
 */
class Update implements UpdateInterface
{
    // Using the AsAction trait to add common action functionality
    use AsAction;

    /**
     * @var CustomerServiceInterface The service interface for customer-related operations.
     */
    private CustomerServiceInterface $customerService;

    /**
     * Constructor for Update action.
     *
     * @param CustomerServiceInterface $customerService The customer service dependency.
     */
    public function __construct(CustomerServiceInterface $customerService)
    {
        $this->customerService = $customerService;

        // Set the logger to use this class name for contextual logging
        Log::setClassName(static::class);
    }

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
    public function handle(UpdateDto $data): array
    {
        try {
            // Initialize the customer service and factory
            $factory = $this->customerService->factory();

            // Set the data from the UpdateDto to the factory
            $factory->setData($data->all());

            // Update the customer information in the service
            $customer = $this->customerService->update($factory);

            // Prepare the response with the payload, status code, success message, and meta information
            $response = $this->response()
                ->setPayload($customer->toArray()) // Set the payload
                ->setStatusCode(HttpStatusCode::OK) // Set HTTP status code to 200 (OK)
                ->setMessage(__('Customer registration successfully')); // Set a success message with the model name

            // Return the formatted result as an associative array
            return $response->toArray();
        } catch (LocalizedException $e) {
            // Rethrow the exception for further handling
            throw $e;
        } catch (Exception $e) {
            // Wrap and rethrow the exception with additional details and an HTTP 500 status code
            throw LocalizedException::make(
                __('An error occurred during customer registration: %1', $e->getMessage()), // Localized error message with details
                $e, // Attach the original exception
                HttpStatusCode::INTERNAL_SERVER_ERROR, // Set the HTTP status code to 500 (Internal Server Error)
            );
        }
    }
}
