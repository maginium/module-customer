<?php

declare(strict_types=1);

namespace Maginium\Customer\Models\Attributes\Customer;

use Magento\Customer\Api\Data\AddressInterface;
use Magento\Customer\Api\Data\CustomerExtensionInterface;
use Maginium\Customer\Interfaces\Data\CustomerInterface;

/**
 * Trait SetterAttributes.
 *
 * This trait provides setter methods for customer attributes, allowing modification of various customer-related data.
 *
 * @method Customer setWebsiteId(int $value) Set the website ID associated with the customer.
 * @method Customer setDisableAutoGroupChange(bool $value) Set whether auto group change is disabled for the customer.
 * @method Customer setGroupId(int $value) Set the group ID for the customer.
 * @method Customer setDefaultBilling(int $value) Set the default billing address ID for the customer.
 * @method Customer setDefaultShipping(int $value) Set the default shipping address ID for the customer.
 * @method Customer setPasswordHash(string $string) Set the hashed password for the customer.
 * @method Customer setId(int $id) Set the customer ID.
 * @method Customer setGroupId(int $groupId) Set the group ID of the customer.
 * @method Customer setDefaultBilling(string $defaultBilling) Set the default billing address ID.
 * @method Customer setDefaultShipping(string $defaultShipping) Set the default shipping address ID.
 * @method Customer setConfirmation(string $confirmation) Set the confirmation code for the customer.
 * @method Customer setCreatedIn(string $createdIn) Set the area where the customer was created (e.g., "admin" or "frontend").
 * @method Customer setDob(string $dob) Set the customer's date of birth.
 * @method Customer setEmail(string $email) Set the customer's email address.
 * @method Customer setFirstname(string $firstname) Set the customer's first name.
 * @method Customer setLastname(string $lastname) Set the customer's last name.
 * @method Customer setMiddlename(string $middlename) Set the customer's middle name.
 * @method Customer setPrefix(string $prefix) Set the customer's prefix (e.g., "Mr." or "Dr.").
 * @method Customer setSuffix(string $suffix) Set the customer's suffix (e.g., "Jr." or "Sr.").
 * @method Customer setTaxvat(string $taxvat) Set the customer's VAT tax number.
 * @method Customer setWebsiteId(int $websiteId) Set the website ID associated with the customer.
 * @method Customer setAddresses(array|null $addresses) Set the customer's addresses.
 * @method Customer setDisableAutoGroupChange(int $disableAutoGroupChange) Set whether auto group change is disabled for the customer.
 * @method Customer setExtensionAttributes(CustomerExtensionInterface $extensionAttributes) Set the extension attributes for the customer.
 */
trait SetterAttributes
{
    /**
     * Set group id.
     *
     * This method sets the customer's group ID, which can represent different customer categories
     * or access levels (e.g., regular, wholesale, VIP).
     *
     * @param int $groupId The customer group ID to be set.
     *
     * @return $this Returns the current instance to allow method chaining.
     */
    public function setGroupId($groupId)
    {
        // Sets the customer group ID in the data array
        $this->setData(CustomerInterface::GROUP_ID, $groupId);

        // Returns the current instance for chaining further method calls
        return $this;
    }

    /**
     * Set default billing address id.
     *
     * This method sets the customer's default billing address ID, used when processing orders and invoices.
     *
     * @param string $defaultBilling The default billing address ID to be set.
     *
     * @return $this Returns the current instance to allow method chaining.
     */
    public function setDefaultBilling($defaultBilling)
    {
        // Stores the default billing address ID in the data array
        $this->setData(CustomerInterface::DEFAULT_BILLING, $defaultBilling);

        // Returns the current instance for chaining further method calls
        return $this;
    }

    /**
     * Set default shipping address id.
     *
     * This method sets the customer's default shipping address ID, used during order processing.
     *
     * @param string $defaultShipping The default shipping address ID to be set.
     *
     * @return $this Returns the current instance to allow method chaining.
     */
    public function setDefaultShipping($defaultShipping)
    {
        // Stores the default shipping address ID in the data array
        $this->setData(CustomerInterface::DEFAULT_SHIPPING, $defaultShipping);

        // Returns the current instance for chaining further method calls
        return $this;
    }

    /**
     * Set confirmation.
     *
     * This method sets the customer's confirmation status. It can be used for email verification or account activation.
     *
     * @param string $confirmation The confirmation status to be set (e.g., pending, confirmed).
     *
     * @return $this Returns the current instance to allow method chaining.
     */
    public function setConfirmation($confirmation)
    {
        // Sets the confirmation status in the data array
        $this->setData(CustomerInterface::CONFIRMATION, $confirmation);

        // Returns the current instance for chaining further method calls
        return $this;
    }

    /**
     * Set created in area.
     *
     * This method sets the location or area where the customer was created,
     * such as a specific store or region within the system.
     *
     * @param string $createdIn The area of creation (e.g., 'US Store', 'Europe').
     *
     * @return $this Returns the current instance to allow method chaining.
     */
    public function setCreatedIn($createdIn)
    {
        // Sets the creation area in the data array
        $this->setData(CustomerInterface::CREATED_IN, $createdIn);

        // Returns the current instance for chaining further method calls
        return $this;
    }

    /**
     * Set date of birth.
     *
     * This method sets the customer's date of birth, which may be used for age verification or special offers.
     *
     * @param string $dob The customer's date of birth in 'YYYY-MM-DD' format.
     *
     * @return $this Returns the current instance to allow method chaining.
     */
    public function setDob($dob)
    {
        // Stores the customer's date of birth in the data array
        $this->setData(CustomerInterface::DOB, $dob);

        // Returns the current instance for chaining further method calls
        return $this;
    }

    /**
     * Set email address.
     *
     * This method sets the customer's email address, which is essential for communication, marketing, and login.
     *
     * @param string $email The email address to be set.
     *
     * @return $this Returns the current instance to allow method chaining.
     */
    public function setEmail($email)
    {
        // Sets the customer's email address in the data array
        $this->setData(CustomerInterface::EMAIL, $email);

        // Returns the current instance for chaining further method calls
        return $this;
    }

    /**
     * Set first name.
     *
     * This method sets the customer's first name, used for personalizing communications.
     *
     * @param string $firstname The first name to be set.
     *
     * @return $this Returns the current instance to allow method chaining.
     */
    public function setFirstname($firstname)
    {
        // Sets the customer's first name in the data array
        $this->setData(CustomerInterface::FIRSTNAME, $firstname);

        // Returns the current instance for chaining further method calls
        return $this;
    }

    /**
     * Set last name.
     *
     * This method sets the customer's last name, which is often used for formal communication.
     *
     * @param string $lastname The last name to be set.
     *
     * @return $this Returns the current instance to allow method chaining.
     */
    public function setLastname($lastname)
    {
        // Sets the customer's last name in the data array
        $this->setData(CustomerInterface::LASTNAME, $lastname);

        // Returns the current instance for chaining further method calls
        return $this;
    }

    /**
     * Set middle name.
     *
     * This method sets the customer's middle name, if available, to provide full name details.
     *
     * @param string $middlename The middle name to be set.
     *
     * @return $this Returns the current instance to allow method chaining.
     */
    public function setMiddlename($middlename)
    {
        // Sets the customer's middle name in the data array
        $this->setData(CustomerInterface::MIDDLENAME, $middlename);

        // Returns the current instance for chaining further method calls
        return $this;
    }

    /**
     * Set prefix.
     *
     * This method sets the customer's prefix (e.g., Mr., Mrs., Dr.) for formal addressing.
     *
     * @param string $prefix The prefix to be set.
     *
     * @return $this Returns the current instance to allow method chaining.
     */
    public function setPrefix($prefix)
    {
        // Stores the prefix in the data array
        $this->setData(CustomerInterface::PREFIX, $prefix);

        // Returns the current instance for chaining further method calls
        return $this;
    }

    /**
     * Set suffix.
     *
     * This method sets the customer's suffix (e.g., Jr., Sr., III) to indicate generational or professional titles.
     *
     * @param string $suffix The suffix to be set.
     *
     * @return $this Returns the current instance to allow method chaining.
     */
    public function setSuffix($suffix)
    {
        // Sets the customer's suffix in the data array
        $this->setData(CustomerInterface::SUFFIX, $suffix);

        // Returns the current instance for chaining further method calls
        return $this;
    }

    /**
     * Set tax Vat.
     *
     * This method sets the customer's VAT tax ID, typically used for businesses or international transactions.
     *
     * @param string $taxvat The VAT tax ID to be set.
     *
     * @return $this Returns the current instance to allow method chaining.
     */
    public function setTaxvat($taxvat)
    {
        // Sets the VAT tax ID in the data array
        $this->setData(CustomerInterface::TAXVAT, $taxvat);

        // Returns the current instance for chaining further method calls
        return $this;
    }

    /**
     * Set website id.
     *
     * This method sets the website ID where the customer is associated.
     * The website ID is important for identifying the customer's specific website or store view.
     *
     * @param int $websiteId The website ID to be set.
     *
     * @return $this Returns the current instance to allow method chaining.
     */
    public function setWebsiteId($websiteId)
    {
        // Sets the website ID in the data array using the constant WEBSITE_ID
        $this->setData(CustomerInterface::WEBSITE_ID, $websiteId);

        // Returns the current instance for chaining further method calls
        return $this;
    }

    /**
     * Set customer addresses.
     *
     * This method sets the list of addresses associated with the customer.
     * The addresses are stored as an array of AddressInterface objects.
     *
     * @param AddressInterface[] $addresses The addresses array to be set.
     *
     * @return $this Returns the current instance to allow method chaining.
     */
    public function setAddresses(?array $addresses = null)
    {
        // Sets the customer addresses in the data array using the constant KEY_ADDRESSES
        $this->setData(CustomerInterface::KEY_ADDRESSES, $addresses);

        // Returns the current instance for chaining further method calls
        return $this;
    }

    /**
     * Set disable auto group change flag.
     *
     * This method sets the flag that determines whether the customer's group auto-change feature is disabled.
     * When this flag is set, the customer will not automatically switch groups based on specific criteria.
     *
     * @param int $disableAutoGroupChange The flag indicating if auto-group change is disabled.
     *
     * @return $this Returns the current instance to allow method chaining.
     */
    public function setDisableAutoGroupChange($disableAutoGroupChange)
    {
        // Sets the auto group change flag in the data array using the constant DISABLE_AUTO_GROUP_CHANGE
        $this->setData(CustomerInterface::DISABLE_AUTO_GROUP_CHANGE, $disableAutoGroupChange);

        // Returns the current instance for chaining further method calls
        return $this;
    }
}
