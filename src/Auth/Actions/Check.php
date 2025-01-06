<?php

declare(strict_types=1);

namespace Maginium\CustomerAuth\Actions;

use Exception;
use Magento\Store\Model\App\Emulation;
use Magento\Store\Model\Store;
use Maginium\Customer\Interfaces\Services\CustomerServiceInterface;
use Maginium\CustomerAuth\Interfaces\CheckInterface;
use Maginium\Foundation\Enums\HttpStatusCode;
use Maginium\Foundation\Exceptions\LocalizedException;
use Maginium\Foundation\Exceptions\NotFoundException;
use Maginium\Framework\Actions\Concerns\AsAction;
use Maginium\Framework\Support\Facades\Log;

/**
 * Class Check.
 */
class Check implements CheckInterface
{
    // Using the AsAction trait to add common action functionality
    use AsAction;

    /**
     * @var CustomerServiceInterface
     */
    private $customerService;

    /**
     * Check constructor.
     *
     * @param CustomerServiceInterface $customerService
     */
    public function __construct(
        CustomerServiceInterface $customerService,
    ) {
        $this->customerService = $customerService;

        // Set Logger class name
        Log::setClassName(static::class);
    }

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
     * @return array An array containing a success message and an HTTP status code indicating the result of the check.
     */
    public function handle(string $identifier): array
    {
        try {
            // Start store emulation
            $this->startEmulation();

            // Get the website ID
            $websiteId = $this->getWebsiteId();

            // Check if a customer exists for the provided identifier in the current website.
            $customerExists = $this->customerService->isExists($identifier, $websiteId);

            // If customer doesn't exist, throw a NotFoundException with a relevant message.
            if (! $customerExists) {
                throw NotFoundException::make(__('Customer with identifier %1, not exists', $identifier), null, HttpStatusCode::NOT_FOUND);
            }

            // Stop the store environment emulation once the check is complete.
            $this->stopEmulation();

            // Prepare the response to be returned, including a payload and status code.
            // This indicates the customer existence check was successful.
            $response = $this->response()
                ->setPayload() // Set the response payload
                ->setStatusCode(HttpStatusCode::OK) // Set the HTTP status code to OK (200)
                ->setMessage(__('Customer existence check successful.')); // Set a success message

            // Return the response as an associative array.
            return $response->toArray();
        } catch (NotFoundException|LocalizedException $e) {
            // If a NotFoundException or LocalizedException is caught, propagate it as-is.
            throw $e;
        } catch (Exception $e) {
            // Catch any general exceptions and throw a localized exception with a user-friendly error message.
            throw LocalizedException::make(
                __('An error occurred while checking the customer: %1', $e->getMessage()),
                $e,
                HttpStatusCode::INTERNAL_SERVER_ERROR, // Return internal server error status
            );
        }
    }
}
