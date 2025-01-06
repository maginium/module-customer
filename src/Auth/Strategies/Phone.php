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
 * Class Phone.
 *
 * This class handles customer authentication via phone number. It validates the provided phone number,
 * checks the associated password, and returns the corresponding customer instance if authentication is successful.
 */
class Phone extends AbstractStrategy
{
    // Using the AsAction trait to add common action functionality
    use AsAction;

    /**
     * Authenticates the customer based on the provided phone number and password.
     *
     * This method verifies the customer's identity by checking the provided phone number and validating the password.
     * If successful, it returns the authenticated customer instance.
     * If the customer cannot be found or the password is incorrect, it returns `false`.
     * Any other validation or unexpected errors will throw an exception.
     *
     * @param LoginDto $data The login data transfer object containing the phone number and password to verify.
     * @param int $websiteId The website ID used to verify the customer's association with the correct website.
     *
     * @throws NotFoundException If no customer is found with the provided phone number.
     * @throws AuthenticationException If the provided password is incorrect.
     * @throws LocalizedException If an unexpected error occurs during the authentication process.
     *
     * @return CustomerInterface|false The authenticated customer instance on success, or `false` if authentication fails.
     */
    public function authenticate(LoginDto $data, int $websiteId): CustomerInterface|false
    {
        // Validate the provided phone number format
        if (! Validator::isPhoneNumber($data->getIdentifier())) {
            // Throw an exception with a localized message if the phone number format is invalid
            throw LocalizedException::make(__('The phone number is invalid. Please verify the phone number and try again.'));
        }

        /** @var CustomerInterface|false $customer */
        // Authenticate the customer using the phone number and password
        $customer = $this->customerRepository->authenticate($data->getIdentifier(), $data->getPassword(), $websiteId);

        $this->dispatchCustomerAuthenticatedEvent($customer, $data->getPassword());

        // Return the customer instance on successful authentication, or false if authentication fails
        return $customer;
    }
}
