<?php

declare(strict_types=1);

namespace Maginium\CustomerAuth\Strategies;

use Maginium\Customer\Interfaces\Data\CustomerInterface;
use Maginium\CustomerAuth\Dtos\LoginDto;
use Maginium\Foundation\Exceptions\AuthenticationException;
use Maginium\Foundation\Exceptions\LocalizedException;
use Maginium\Foundation\Exceptions\NotFoundException;
use Maginium\Framework\Actions\Concerns\AsAction;
use Maginium\Framework\Support\Validator;

/**
 * Class Email.
 */
class Email extends AbstractStrategy
{
    // Using the AsAction trait to add common action functionality
    use AsAction;

    /**
     * Authenticates the customer based on the provided credentials.
     *
     * This method verifies the customer's identity by checking the provided identifier (e.g., mobile number, email)
     * and validating the password. If successful, it returns the authenticated customer instance.
     * If the customer cannot be found or the password is incorrect, it returns `false`.
     * Any other validation or unexpected errors will throw an exception.
     *
     * @param LoginDto $data The login data transfer object containing the identifier and password to verify.
     * @param int $websiteId The website ID used to verify the customer's association with the correct website.
     *
     * @throws NotFoundException If no customer is found with the provided identifier.
     * @throws AuthenticationException If the provided password is incorrect.
     * @throws LocalizedException If an unexpected error occurs during the authentication process.
     *
     * @return CustomerInterface|false The authenticated customer instance on success, or `false` if authentication fails.
     */
    public function authenticate(LoginDto $data, int $websiteId): CustomerInterface|false
    {
        // Validate the email address format
        if (! Validator::isEmail($data->getIdentifier())) {
            // Throw a localized exception to notify the user that the email format is incorrect
            throw LocalizedException::make(__('The email address is invalid. Please verify the email and try again.'));
        }

        /** @var CustomerInterface|false $customer */
        $customer = $this->customerRepository->authenticate($data->getIdentifier(), $data->getPassword(), $websiteId);

        $this->dispatchCustomerAuthenticatedEvent($customer, $data->getPassword());

        // Return the formatted result as an associative array
        return $customer;
    }
}
