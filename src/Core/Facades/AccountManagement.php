<?php

declare(strict_types=1);

namespace Maginium\Customer\Facades;

use Magento\Customer\Api\AccountManagementInterface;
use Maginium\Framework\Support\Facade;

/**
 * Facade for interacting with the Customer Session service.
 *
 * @method static void remove(string $customerId) Removes a customer from the registry using the customer ID.
 * @method static \Magento\Customer\Api\Data\CustomerInterface createAccount(\Magento\Customer\Api\Data\CustomerInterface $customer, $password = null, $redirectUrl = '') Creates a customer account with optional password and redirect URL.
 * @method static \Magento\Customer\Api\Data\CustomerInterface createAccountWithPasswordHash(\Magento\Customer\Api\Data\CustomerInterface $customer, $hash, $redirectUrl = '') Creates a customer account using an existing password hash and optional redirect URL.
 * @method static bool validate(\Magento\Customer\Api\Data\CustomerInterface $customer) Validates customer data.
 * @method static bool isReadonly($customerId) Checks if the customer account is readonly.
 * @method static bool activate($email, $confirmationKey) Activates a customer account using a confirmation key.
 * @method static bool activateById($customerId, $confirmationKey) Activates a customer account using the customer's ID and confirmation key.
 * @method static \Magento\Customer\Api\Data\CustomerInterface authenticate($email, $password) Authenticates a customer by their email and password.
 * @method static bool changePassword($email, $currentPassword, $newPassword) Changes a customer's password by email.
 * @method static bool changePasswordById($customerId, $currentPassword, $newPassword) Changes a customer's password by customer ID.
 * @method static void initiatePasswordReset($email, $template, $websiteId = null) Initiates a password reset by sending a password reset link email to the customer.
 * @method static bool resetPassword($email, $resetToken, $newPassword) Resets a customer's password using a reset token.
 * @method static bool validateResetPasswordLinkToken($customerId, $resetPasswordLinkToken) Validates if a password reset token is valid.
 * @method static string getConfirmationStatus($customerId) Gets the confirmation status of a customer's account.
 * @method static void resendConfirmation($email, $websiteId, $redirectUrl = '') Resends a confirmation email to the customer.
 * @method static bool isEmailAvailable($customerEmail, $websiteId = null) Checks if the provided email is available for registration on the given website.
 * @method static bool isCustomerInStore($customerWebsiteId, $storeId) Checks if the customer is associated with a specific store.
 * @method static \Magento\Customer\Api\Data\AddressInterface getDefaultBillingAddress($customerId) Retrieves the default billing address for the given customer ID.
 * @method static \Magento\Customer\Api\Data\AddressInterface getDefaultShippingAddress($customerId) Retrieves the default shipping address for the given customer ID.
 * @method static string getPasswordHash($password) Returns a hashed password that can be saved to the database.
 *
 * @see AccountManagementInterface
 */
class AccountManagement extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getAccessor(): string
    {
        return AccountManagementInterface::class;
    }
}
