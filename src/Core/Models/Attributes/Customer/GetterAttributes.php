<?php

declare(strict_types=1);

namespace Maginium\Customer\Models\Attributes\Customer;

use Magento\Customer\Api\Data\AddressInterface;
use Magento\Customer\Api\Data\CustomerExtensionInterface;
use Maginium\Customer\Dtos\CustomerAddressCityDto;
use Maginium\Customer\Dtos\CustomerAddressContactDto;
use Maginium\Customer\Dtos\CustomerAddressCountryDto;
use Maginium\Customer\Dtos\CustomerAddressDto;
use Maginium\Customer\Dtos\CustomerAddressInfoDto;
use Maginium\Customer\Dtos\CustomerAddressStreetDto;
use Maginium\Customer\Facades\CustomerLogger;
use Maginium\Customer\Interfaces\Data\CustomerInterface;
use Maginium\Framework\Support\Arr;
use Maginium\Framework\Support\Facades\Date;
use Maginium\Framework\Support\Php;
use Maginium\Framework\Support\Str;

/**
 * Trait GetterAttributes.
 *
 * This trait provides getter methods for customer group type attributes and other customer-related data.
 *
 * @method string getName() Get the formatted full name of the customer, including prefix and suffix.
 * @method string getFullName() Get the full name of the customer (first name and last name).
 * @method bool isLocked() Check if the customer account is locked.
 * @method string getLastActivity() Get the formatted date of the last login activity, or "Never" if no activity is found.
 * @method string getMobileNumber() Get the mobile number of the customer.
 * @method int getWebsiteId() Get the website ID associated with the customer.
 * @method string getEmail() Get the email address of the customer.
 * @method mixed getDisableAutoGroupChange() Get the value indicating if auto group change is disabled for the customer.
 * @method string getPasswordHash() Get the hashed password of the customer.
 * @method string getConfirmation() Get the customer's confirmation token (used for account confirmation).
 * @method int|null getId() Get the customer ID.
 * @method string|null getDefaultBilling() Get the default billing address ID for the customer.
 * @method string|null getDefaultShipping() Get the default shipping address ID for the customer.
 * @method string|null getCreatedIn() Get the area in which the customer account was created.
 * @method string|null getDob() Get the customer's date of birth.
 * @method string getFirstname() Get the first name of the customer.
 * @method string getLastname() Get the last name of the customer.
 * @method string|null getMiddlename() Get the middle name of the customer.
 * @method string|null getPrefix() Get the prefix associated with the customer's name.
 * @method string|null getSuffix() Get the suffix associated with the customer's name.
 * @method string|null getTaxvat() Get the tax VAT number associated with the customer.
 * @method AddressInterface[]|null getAllAddresses() Get the addresses associated with the customer.
 */
trait GetterAttributes
{
    /**
     * Get the formatted full name of the customer including prefix and suffix.
     *
     * @return string|null The formatted full name of the customer
     */
    public function getName(): ?string
    {
        // Retrieve the prefix, first name, last name, and suffix of the customer
        $suffix = $this->getSuffix();
        $prefix = $this->getPrefix();
        $lastName = $this->getLastname();
        $firstName = $this->getFirstname();

        // Initialize an empty array to store the name parts
        $nameParts = [];

        // Add the prefix to the name parts array if it's not empty
        if (! empty($prefix)) {
            $nameParts[] = Str::capital($prefix);
        }

        // Add the first name to the name parts array if it's not empty
        if (! empty($firstName)) {
            $nameParts[] = $firstName;
        }

        // Add the last name to the name parts array if it's not empty
        if (! empty($lastName)) {
            $nameParts[] = $lastName;
        }

        // Add the suffix to the name parts array if it's not empty
        if (! empty($suffix)) {
            $nameParts[] = Str::capital($suffix);
        }

        // Concatenate all the name parts with a space in between
        $formattedFullName = Php::implode(' ', $nameParts);

        // Return the formatted full name or null if no name parts are available
        return ! empty($nameParts) ? $formattedFullName : null;
    }

    /**
     * Get the full name of the customer.
     *
     * @return string|null The full name of the customer
     */
    public function getFullName(): ?string
    {
        // Retrieve the first name and last name of the customer
        $firstName = $this->getFirstname();
        $lastName = $this->getLastname();

        // Check if both first name and last name are not empty
        if (! empty($firstName) && ! empty($lastName)) {
            // Concatenate the first name and last name with a space in between
            return $firstName . ' ' . $lastName;
        }

        // Return null if either first name or last name is empty
        return null;
    }

    /**
     * Checks if the customer account is locked.
     *
     * @return bool Returns true if the account is locked, false otherwise.
     */
    public function isLocked(): bool
    {
        // Retrieve the lock expiry date from the customer model
        $lockExpires = $this->getData(CustomerInterface::LOCK_EXPIRES);

        // Check if lock expiry date is set and not expired
        if ($lockExpires) {
            $lockExpires = Date::parse($lockExpires);

            // Compare the lock expiry date with the current time
            if ($lockExpires->isFuture()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Retrieves the last activity of the customer.
     *
     * @return string
     *     Returns the formatted date of the last login activity or "Never" if no activity found.
     */
    public function getLastActivity(): string
    {
        // Get the customer log model by fetching the log associated with the customer's ID
        $customerLog = CustomerLogger::get($this->getId());

        // Retrieve the last login date from the customer log
        $date = $customerLog->getLastLoginAt();

        // Get the locale for the user (this will determine the date formatting)
        $locale = $this->getLocale();

        // Check if the last login date is available
        // If available, format the date using Carbon (localized format)
        // If not available, return the string "Never" from the CustomerInterface
        return $date
            ? Date::parse($date) // Parse the date using Carbon
                ->locale($locale) // Set the locale for proper translation/formatting
                // ->isoFormat('ll') // Format the date in ISO format ('ll' gives short localized date)
            : __(CustomerInterface::NEVER)->getText(); // Return "Never" if no date found
    }

    /**
     * Get the customer's mobile number.
     *
     * Retrieves the mobile number associated with the customer's account.
     *
     * @return string|null The mobile number of the customer or null if not set.
     */
    public function getMobileNumber(): ?string
    {
        // Retrieve the mobile number from the customer model
        return $this->getMeta(CustomerInterface::MOBILE_NUMBER);
    }

    /**
     * Get the default billing address ID for the customer.
     *
     * Retrieves the ID of the default billing address associated with the customer.
     *
     * @return string|null The default billing address ID or null if not set.
     */
    public function getDefaultBilling()
    {
        // Retrieve the default billing address ID from the customer model
        return $this->getData(CustomerInterface::DEFAULT_BILLING);
    }

    /**
     * Get the default shipping address ID for the customer.
     *
     * Retrieves the ID of the default shipping address associated with the customer.
     *
     * @return string|null The default shipping address ID or null if not set.
     */
    public function getDefaultShipping()
    {
        // Retrieve the default shipping address ID from the customer model
        return $this->getData(CustomerInterface::DEFAULT_SHIPPING);
    }

    /**
     * Get the confirmation code associated with the customer's account.
     *
     * Retrieves the confirmation code used for account verification or activation.
     *
     * @return string|null The confirmation code or null if not set.
     */
    public function getConfirmation()
    {
        // Retrieve the confirmation code from the customer model
        return $this->getData(CustomerInterface::CONFIRMATION);
    }

    /**
     * Get the area in which the customer account was created.
     *
     * Retrieves the location or area where the customer account was created.
     *
     * @return string|null The creation area or null if not set.
     */
    public function getCreatedIn()
    {
        // Retrieve the creation area from the customer model
        return $this->getData(CustomerInterface::CREATED_IN);
    }

    /**
     * Get the customer's date of birth.
     *
     * Retrieves the date of birth of the customer.
     *
     * @return string|null The date of birth or null if not set.
     */
    public function getDob()
    {
        // Retrieve the date of birth from the customer model
        return $this->getData(CustomerInterface::DOB);
    }

    /**
     * Get the email address associated with the customer account.
     *
     * Retrieves the email address of the customer.
     *
     * @return string The email address of the customer.
     */
    public function getEmail()
    {
        // Retrieve the email address from the customer model
        return $this->getData(CustomerInterface::EMAIL);
    }

    /**
     * Get the first name of the customer.
     *
     * Retrieves the first name of the customer.
     *
     * @return string The first name of the customer.
     */
    public function getFirstname()
    {
        // Retrieve the first name from the customer model
        return $this->getData(CustomerInterface::FIRSTNAME);
    }

    /**
     * Get the last name of the customer.
     *
     * Retrieves the last name of the customer.
     *
     * @return string The last name of the customer.
     */
    public function getLastname()
    {
        // Retrieve the last name from the customer model
        return $this->getData(CustomerInterface::LASTNAME);
    }

    /**
     * Get the middle name of the customer.
     *
     * Retrieves the middle name of the customer, if set.
     *
     * @return string|null The middle name of the customer or null if not set.
     */
    public function getMiddlename()
    {
        // Retrieve the middle name from the customer model
        return $this->getData(CustomerInterface::MIDDLENAME);
    }

    /**
     * Get the prefix associated with the customer's name.
     *
     * Retrieves the prefix (e.g., Mr., Mrs.) of the customer, if set.
     *
     * @return string|null The prefix of the customer or null if not set.
     */
    public function getPrefix()
    {
        // Retrieve the prefix from the customer model
        return $this->getData(CustomerInterface::PREFIX);
    }

    /**
     * Get the suffix associated with the customer's name.
     *
     * Retrieves the suffix (e.g., Jr., Sr.) of the customer, if set.
     *
     * @return string|null The suffix of the customer or null if not set.
     */
    public function getSuffix()
    {
        // Retrieve the suffix from the customer model
        return $this->getData(CustomerInterface::SUFFIX);
    }

    /**
     * Get the tax VAT number associated with the customer.
     *
     * Retrieves the tax VAT number for the customer, if set.
     *
     * @return string|null The tax VAT number or null if not set.
     */
    public function getTaxvat()
    {
        // Retrieve the tax VAT number from the customer model
        return $this->getData(CustomerInterface::TAXVAT);
    }

    /**
     * Get the website ID associated with the customer account.
     *
     * Retrieves the ID of the website the customer is associated with.
     *
     * @return int|null The website ID or null if not set.
     */
    public function getWebsiteId()
    {
        // Retrieve the website ID from the customer model
        return (int)$this->getData(CustomerInterface::WEBSITE_ID);
    }

    /**
     * Get the addresses associated with the customer.
     *
     * Retrieves a list of addresses (billing and shipping) associated with the customer.
     *
     * @return array|null The list of address DTOs or null if not set.
     */
    public function getAllAddresses()
    {
        // Retrieve all customer addresses
        $addresses = $this->getAddressesCollection()->getItems();

        // Convert the array to a collection and map over it
        return collect($addresses)->map(function($address) {
            $addressData = $address->toArray();

            // Determine if the address is the default billing or default shipping address
            $isDefaultBilling = (int)$address->getId() === (int)$this->getDefaultBilling();
            $isDefaultShipping = (int)$address->getId() === (int)$this->getDefaultShipping();

            // Set the default flag to true if both billing and shipping addresses are default
            $default = $isDefaultBilling && $isDefaultShipping;

            // Otherwise, if the address is the default shipping address, set default to true
            if (! $default && $isDefaultShipping) {
                $default = true;
            }

            // Create DTOs for address components (e.g., city, info, street, etc.)
            $city = CustomerAddressCityDto::make($addressData);
            $info = CustomerAddressInfoDto::make($addressData);
            $street = CustomerAddressStreetDto::make($addressData);
            $country = CustomerAddressCountryDto::make($addressData);
            $contact = CustomerAddressContactDto::make($addressData);

            // Get the customer ID to pass along to the address DTO
            $customer_id = $this->getId();

            // Create the full address DTO with the calculated 'default' flag
            $addressDto = CustomerAddressDto::make(Arr::merge($addressData, [
                'info' => $info,
                'city' => $city,
                'street' => $street,
                'country' => $country,
                'contact' => $contact,
                'customer_id' => $customer_id,
                'default' => $default,
            ]));

            // Convert the DTO to an array and return
            return $addressDto->toArray();
        })->values()->toArray(); // Reset the array keys after mapping
    }

    /**
     * Get the flag indicating whether auto group change is disabled for the customer.
     *
     * Retrieves the flag that indicates if the automatic group change for the customer is disabled.
     *
     * @return int|null The disable auto group change flag or null if not set.
     */
    public function getDisableAutoGroupChange()
    {
        // Retrieve the disable auto group change flag from the customer model
        return $this->getData(CustomerInterface::DISABLE_AUTO_GROUP_CHANGE);
    }

    /**
     * Get the extension attributes associated with the customer.
     *
     * Retrieves the extension attributes, which provide additional data specific to the customer.
     *
     * @return CustomerExtensionInterface|null The extension attributes or null if not set.
     */
    public function getExtensionAttributes()
    {
        // Retrieve the extension attributes from the customer model
        return $this->getDataExtensionAttributes();
    }
}
