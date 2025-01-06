<?php

declare(strict_types=1);

namespace Maginium\Customer\Models;

use Magento\Customer\Model\AccountConfirmation as BaseAccountConfirmation;
use Magento\Store\Model\ScopeInterface;
use Maginium\Customer\Interfaces\Data\CustomerInterface;
use Maginium\Framework\Support\Facades\Config;
use Maginium\Framework\Support\Facades\Registry;

/**
 * Class AccountConfirmation.
 * This class extends the BaseAccountConfirmation to manage account confirmation
 * logic related to customer registration and email/phone changes.
 */
class AccountConfirmation extends BaseAccountConfirmation
{
    /**
     * Configuration path for email confirmation when creating a new customer.
     * This configuration controls whether a new customer must confirm their email after registration.
     */
    public const XML_PATH_IS_CONFIRM = 'customer/create_account/confirm';

    /**
     * Configuration path for email confirmation when updating an existing customer's email.
     * This configuration controls whether a customer must confirm their email after updating it.
     */
    public const XML_PATH_IS_CONFIRM_EMAIL_CHANGED = 'customer/account_information/confirm';

    /**
     * Constant representing confirmed status.
     * This status is used when the account has been confirmed and does not require further confirmation.
     */
    private const ACCOUNT_CONFIRMED = 'account_confirmed';

    /**
     * Constant representing confirmation required status.
     * This status is used when the account requires confirmation, such as after a change in email or phone.
     */
    private const ACCOUNT_CONFIRMATION_REQUIRED = 'account_confirmation_required';

    /**
     * Constant representing confirmation not required status.
     * This status is used when no confirmation is required for the account, such as when no changes are made.
     */
    private const ACCOUNT_CONFIRMATION_NOT_REQUIRED = 'account_confirmation_not_required';

    /**
     * Checks if the customer's email and phone are verified.
     *
     * This method evaluates the verification status of both the email
     * and phone number for a given customer.
     *
     * @param CustomerInterface $customer The customer object whose verification status is to be checked.
     *
     * @return array Returns an associative array containing the verification statuses:
     *               - 'email' => bool (true if email is verified, false otherwise)
     *               - 'phone' => bool (true if phone is verified, false otherwise)
     */
    public function isCustomerVerified(CustomerInterface $customer): array
    {
        return [
            'email' => $this->isEmailVerified($customer),
            'phone' => $this->isPhoneVerified($customer),
        ];
    }

    /**
     * Checks if phone confirmation is required when the phone number has been changed.
     * It will return true if the confirmation is required, false otherwise.
     *
     * @param int|null $websiteId The website ID to check against.
     * @param int|null $customerId The customer ID to check against.
     * @param string|null $customerPhone The phone number of the customer.
     *
     * @return bool Returns true if phone confirmation is required, false otherwise.
     */
    public function isPhoneChangedConfirmationRequired(?int $websiteId, ?int $customerId, ?string $customerPhone): bool
    {
        // Set the scope to the website context
        Config::setScope(ScopeInterface::SCOPE_WEBSITES);
        Config::setScopeId($websiteId);

        // Check if confirmation should be skipped and return the result of the configuration setting
        return ! $this->canSkipConfirmation($customerId, $customerPhone)
            && Config::getBool(self::XML_PATH_IS_CONFIRM_EMAIL_CHANGED);
    }

    /**
     * Checks if phone confirmation is required for a customer based on their information.
     * This method evaluates whether a customer's phone change requires confirmation.
     *
     * @param CustomerInterface $customer The customer object that contains the phone number.
     *
     * @return bool Returns true if phone confirmation is required for the customer, false otherwise.
     */
    public function isCustomerPhoneChangedConfirmRequired(CustomerInterface $customer): bool
    {
        // Get the phone change confirmation status and check if confirmation is required
        return $this->getPhoneChangedConfirmStatus($customer) === self::ACCOUNT_CONFIRMATION_REQUIRED;
    }

    /**
     * Checks whether the confirmation may be skipped when registering using a specific phone number.
     * This checks the identity of the phone number against the registry to determine if confirmation can be skipped.
     *
     * @return bool Returns true if confirmation can be skipped, false otherwise.
     *
     * @see AccountConfirmation::isConfirmationRequired
     */
    public function isEmailVerified(CustomerInterface $customer): bool
    {
        // Check if the customer requires confirmation and whether they are eligible to skip confirmation
        return ! (
            $customer->getConfirmation() &&
            $this->isConfirmationRequired($customer->getWebsiteId(), $customer->getId(), $customer->getEmail())
        );
    }

    /**
     * Checks whether the confirmation may be skipped when registering using a specific phone number.
     * This checks the identity of the phone number against the registry to determine if confirmation can be skipped.
     *
     * @return bool Returns true if confirmation can be skipped, false otherwise.
     *
     * @see AccountConfirmation::isConfirmationRequired
     */
    public function isPhoneVerified(CustomerInterface $customer): bool
    {
        // Check if the customer requires confirmation and whether they are eligible to skip confirmation
        return ! (
            $customer->getConfirmation() &&
            $this->isConfirmationRequired($customer->getWebsiteId(), $customer->getId(), $customer->getMobileNumber())
        );
    }

    /**
     * Returns the phone confirmation status if the phone number has been changed.
     * This method checks if the customer's phone change requires confirmation based on the configured settings.
     *
     * @param CustomerInterface $customer The customer object whose phone change is to be checked.
     *
     * @return string Returns one of the following statuses:
     *                - ACCOUNT_CONFIRMED if the account is confirmed.
     *                - ACCOUNT_CONFIRMATION_REQUIRED if confirmation is required.
     *                - ACCOUNT_CONFIRMATION_NOT_REQUIRED if no confirmation is needed.
     */
    private function getPhoneChangedConfirmStatus(CustomerInterface $customer): string
    {
        // Extract website ID, customer ID, and mobile number from the customer object
        $websiteId = (int)$customer->getWebsiteId();
        $customerId = (int)$customer->getId();
        $mobileNumber = $customer->getMobileNumber(); // getMobileNumber() may return null or an empty value

        // Ensure the mobile number is valid for checking the confirmation status
        $isPhoneChangedConfirmationRequired = $this->isPhoneChangedConfirmationRequired($websiteId, $customerId, $mobileNumber);

        // Return the appropriate confirmation status based on whether confirmation is required
        if ($isPhoneChangedConfirmationRequired) {
            return $customer->getConfirmation() ? self::ACCOUNT_CONFIRMATION_REQUIRED : self::ACCOUNT_CONFIRMED;
        }

        return self::ACCOUNT_CONFIRMATION_NOT_REQUIRED;
    }

    /**
     * Checks whether confirmation may be skipped when registering using a specific phone number.
     * This method checks if the phone number matches one that should bypass the confirmation process.
     *
     * @param int|null $customerId The ID of the customer to check.
     * @param string $customerPhone The phone number to check against.
     *
     * @return bool Returns true if confirmation can be skipped, false otherwise.
     */
    private function canSkipConfirmation($customerId, $customerPhone): bool
    {
        // Return false if customerId or customerPhone is not provided
        if (! $customerId || $customerPhone === null) {
            return false;
        }

        // Check if a specific phone number has been flagged to skip confirmation
        $skipConfirmationIfPhone = Registry::registry('skip_confirmation_if_phone');

        // Return false if the registry entry is not set
        if (! $skipConfirmationIfPhone) {
            return false;
        }

        // Compare the phone number (case-insensitive) to determine if confirmation can be skipped
        return mb_strtolower($skipConfirmationIfPhone) === mb_strtolower($customerPhone);
    }
}
