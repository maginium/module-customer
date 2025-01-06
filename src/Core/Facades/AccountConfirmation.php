<?php

declare(strict_types=1);

namespace Maginium\Customer\Facades;

use Maginium\Customer\Interfaces\Data\CustomerInterface;
use Maginium\Customer\Models\AccountConfirmation as AccountConfirmationManager;
use Maginium\Framework\Support\Facade;

/**
 * Class AccountConfirmation.
 *
 * Facade for interacting with the Account Confirmation management. This facade provides easy access to account
 * confirmation related operations, such as verifying customer email and phone confirmation status, and determining
 * if confirmation is required for changes in email or phone.
 *
 * @method static array isCustomerVerified(CustomerInterface $customer) Check if the customer is verified and if confirmation is complete.
 * @method static bool isPhoneChangedConfirmationRequired($websiteId, $customerId, $customerPhone)  Check if phone confirmation is required when the phone number has been changed for a customer.
 * @method static bool isCustomerPhoneChangedConfirmRequired(CustomerInterface $customer) Check if phone confirmation is required for the customer, based on their current phone status.
 * @method static bool isEmailVerified(CustomerInterface $customer) Check if the customer's email is verified and if confirmation is complete.
 * @method static bool isPhoneVerified(CustomerInterface $customer) Check if the customer's phone number is verified.
 * @method static bool isConfirmationRequired($websiteId, $customerId, $customerEmail)  Determine if email confirmation is required when creating a new customer account or updating an existing email.
 * @method static bool isEmailChangedConfirmationRequired($websiteId, $customerId, $customerEmail) Check if email confirmation is required when the customer's email has changed.
 * @method static bool isCustomerEmailChangedConfirmRequired(CustomerInterface $customer) Check if email confirmation is required for the customer, based on their current email status.
 *
 * @see AccountConfirmationManager
 */
class AccountConfirmation extends Facade
{
    /**
     * Get the accessor for the facade.
     *
     * This method must be implemented by subclasses to return the accessor string
     * corresponding to the underlying service or class the facade represents.
     *
     * @return string The accessor for the facade.
     */
    protected static function getAccessor(): string
    {
        return AccountConfirmationManager::class;
    }
}
