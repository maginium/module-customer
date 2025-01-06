<?php

declare(strict_types=1);

namespace Maginium\Customer\Interfaces\Data;

use DateTimeInterface;
// use Maginium\Framework\Database\Interfaces\HasCustomerGroupsInterface;
use Maginium\Framework\Database\Interfaces\Data\ModelInterface;
use Maginium\Framework\Database\Interfaces\HasLocalePreference;

// use Maginium\Framework\Database\Interfaces\HasMetadataInterface;

/**
 * Interface CustomerInterface.
 *
 * This interface defines constants used for interacting with the customer model in the Maginium module.
 * It includes table name, event prefix, and field identifiers that are commonly used across models and other parts
 * of the application related to the customer data.
 *
 * @method int getId() Get the customer ID.
 * @method void setId(int $id) Set the customer ID.
 * @method int getGroupId() Get the customer group ID.
 * @method void setGroupId(int $groupId) Set the customer group ID.
 * @method string getDefaultBilling() Get the default billing address.
 * @method void setDefaultBilling(string $defaultBilling) Set the default billing address.
 * @method string getDefaultShipping() Get the default shipping address.
 * @method void setDefaultShipping(string $defaultShipping) Set the default shipping address.
 * @method string getConfirmation() Get the confirmation status.
 * @method void setConfirmation(string $confirmation) Set the confirmation status.
 * @method string getCreatedAt() Get the creation timestamp of the customer.
 * @method void setCreatedAt(string $createdAt) Set the creation timestamp of the customer.
 * @method string getUpdatedAt() Get the last update timestamp of the customer.
 * @method void setUpdatedAt(string $updatedAt) Set the last update timestamp of the customer.
 * @method string getCreatedIn() Get the store where the customer was created.
 * @method void setCreatedIn(string $createdIn) Set the store where the customer was created.
 * @method string getDob() Get the customer's date of birth.
 * @method void setDob(string $dob) Set the customer's date of birth.
 * @method string getEmail() Get the customer's email address.
 * @method void setEmail(string $email) Set the customer's email address.
 * @method string getFirstname() Get the customer's first name.
 * @method void setFirstname(string $firstname) Set the customer's first name.
 * @method string getLastname() Get the customer's last name.
 * @method void setLastname(string $lastname) Set the customer's last name.
 * @method string getMiddlename() Get the customer's middle name.
 * @method void setMiddlename(string $middlename) Set the customer's middle name.
 * @method string getPrefix() Get the customer's prefix (e.g., Mr., Mrs.).
 * @method void setPrefix(string $prefix) Set the customer's prefix.
 * @method string getSuffix() Get the customer's suffix (e.g., Jr., Sr.).
 * @method void setSuffix(string $suffix) Set the customer's suffix.
 * @method string getGender() Get the customer's gender.
 * @method void setGender(string $gender) Set the customer's gender.
 * @method int getStoreId() Get the customer's store ID.
 * @method void setStoreId(int $storeId) Set the customer's store ID.
 * @method string getTaxvat() Get the customer's tax VAT number.
 * @method void setTaxvat(string $taxvat) Set the customer's tax VAT number.
 * @method int getWebsiteId() Get the customer's website ID.
 * @method void setWebsiteId(int $websiteId) Set the customer's website ID.
 * @method array getAddresses() Get the list of the customer's addresses.
 * @method void setAddresses(array $addresses = null) Set the list of the customer's addresses.
 * @method bool getDisableAutoGroupChange() Get whether auto group change is disabled.
 * @method void setDisableAutoGroupChange(bool $disableAutoGroupChange) Set whether auto group change is disabled.
 * @method \Magento\Customer\Api\Data\CustomerExtensionInterface getExtensionAttributes() Get the customer's extension attributes.
 * @method void setExtensionAttributes(\Magento\Customer\Api\Data\CustomerExtensionInterface $extensionAttributes) Set the customer's extension attributes.
 * @method DateTimeInterface|null getRpTokenCreatedAt() Retrieve the creation timestamp of the customer's reset password token.
 */
interface CustomerInterface extends /* HasCustomerGroupsInterface, */ HasLocalePreference, /* HasMetadataInterface, */ ModelInterface
{
    /**
     * Entity table_name identifier.
     *
     * This constant represents the name of the table used to store customer data.
     * It is used across models, repositories, and other parts of the application to reference the database table.
     *
     * @var string
     */
    public const TABLE_NAME = 'customer_model';

    /**
     * Prefix of model events names.
     *
     * This constant defines the prefix used for model-related event names.
     * Events related to customer data can be triggered with names like
     * "customer_save_before" or "customer_delete_after".
     *
     * @var string
     */
    public const EVENT_PREFIX = 'customer';

    /**
     * Customer ID field.
     *
     * This constant represents the field name for the unique identifier (ID) of the customer.
     * It is used in database operations to reference the customer's ID field.
     *
     * @var string
     */
    public const ID = 'model_id';

    /**
     * Constants for ID.
     */
    public const MOBILE_NUMBER = 'mobile_number';

    /**
     * Column 'lock_expires' for the customer.
     */
    public const LOCK_EXPIRES = 'lock_expires';

    /**
     * Label constant.
     */
    public const LABEL = 'label';

    /**
     * Constant representing "Never".
     */
    public const NEVER = 'Never';

    /**
     * Constant representing "Not Specified".
     */
    public const NOT_SPECIFIED = 'Not Specified';

    /**
     * Constant for IS_ACTIVE.
     *
     * Represents the status of the customer account. This constant is used for checking if a customer is active.
     */
    public const IS_ACTIVE = 'is_active';

    /**
     * Constant for CONFIRMATION.
     *
     * Represents the confirmation status of the customer account (e.g., confirmed or pending).
     */
    public const CONFIRMATION = 'confirmation';

    /**
     * Constant for CREATED_IN.
     *
     * Represents the store or platform where the customer was created.
     */
    public const CREATED_IN = 'created_in';

    /**
     * Constant for DOB.
     *
     * Represents the date of birth of the customer.
     */
    public const DOB = 'dob';

    /**
     * Constant for EMAIL.
     *
     * Represents the email address of the customer.
     */
    public const EMAIL = 'email';

    /**
     * Constant for FIRSTNAME.
     *
     * Represents the first name of the customer.
     */
    public const FIRSTNAME = 'firstname';

    /**
     * Constant for GENDER.
     *
     * Represents the gender of the customer (e.g., male, female, etc.).
     */
    public const GENDER = 'gender';

    /**
     * Constant for GROUP_ID.
     *
     * Represents the group ID to which the customer belongs.
     */
    public const GROUP_ID = 'group_id';

    /**
     * Constant for LASTNAME.
     *
     * Represents the last name of the customer.
     */
    public const LASTNAME = 'lastname';

    /**
     * Constant for MIDDLENAME.
     *
     * Represents the middle name of the customer.
     */
    public const MIDDLENAME = 'middlename';

    /**
     * Constant for PREFIX.
     *
     * Represents the prefix for the customer's name (e.g., Mr., Mrs.).
     */
    public const PREFIX = 'prefix';

    /**
     * Constant for STORE_ID.
     *
     * Represents the store ID where the customer is registered.
     */
    public const STORE_ID = 'store_id';

    /**
     * Constant for SUFFIX.
     *
     * Represents the suffix for the customer's name (e.g., Jr., Sr.).
     */
    public const SUFFIX = 'suffix';

    /**
     * Constant for TAXVAT.
     *
     * Represents the tax VAT number for the customer.
     */
    public const TAXVAT = 'taxvat';

    /**
     * Constant for WEBSITE_ID.
     *
     * Represents the website ID the customer is associated with.
     */
    public const WEBSITE_ID = 'website_id';

    /**
     * Constant for DEFAULT_BILLING.
     *
     * Represents the default billing address of the customer.
     */
    public const DEFAULT_BILLING = 'default_billing';

    /**
     * Constant for DEFAULT_SHIPPING.
     *
     * Represents the default shipping address of the customer.
     */
    public const DEFAULT_SHIPPING = 'default_shipping';

    /**
     * Constant for KEY_ADDRESSES.
     *
     * Represents the key for the customer's addresses.
     */
    public const KEY_ADDRESSES = 'addresses';

    /**
     * Constant for DISABLE_AUTO_GROUP_CHANGE.
     *
     * Represents whether auto group change is disabled for the customer.
     */
    public const DISABLE_AUTO_GROUP_CHANGE = 'disable_auto_group_change';

    /**
     * Get the formatted full name of the customer including prefix and suffix.
     *
     * @return string|null The formatted full name of the customer
     */
    public function getName(): ?string;

    /**
     * Get the full name of the customer.
     *
     * @return string|null The full name of the customer
     */
    public function getFullName(): ?string;

    /**
     * Get the gender text by its ID.
     *
     * @return string|null The gender text or null if not found
     */
    public function getGender(): ?string;

    /**
     * Check if the customer is active.
     *
     * @return string|null Returns "active" if the customer is active, "inactive" if not, or null on error.
     */
    public function getStatus();

    /**
     * Retrieves the last activity of the customer.
     *
     * @return string Returns the formatted date of the last login activity or "Never" if no activity found.
     */
    public function getLastActivity(): string;

    /**
     * Checks if the customer account is locked.
     *
     * @return bool Returns true if the account is locked, false otherwise.
     */
    public function isLocked(): bool;

    /**
     * Get the customer's mobile number.
     *
     * Retrieves the mobile number associated with the customer's account.
     *
     * @return string|null The mobile number of the customer or null if not set.
     */
    public function getMobileNumber(): ?string;

    /**
     * Retrieve customer address array.
     *
     * @return DataObject[]
     */
    public function getAllAddresses();
}
