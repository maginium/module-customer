<?php

declare(strict_types=1);

namespace Maginium\Customer\Models\Scopes\Customer;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Maginium\Customer\Interfaces\Data\CustomerInterface;
use Maginium\Foundation\Exceptions\LocalizedException;

/**
 * Trait MobileNumberScopes.
 *
 * Defines methods related to mobile number operations for Customer.
 *
 * @method AbstractCollection|null scopeFindByMobileNumber(string|null $mobileNumber) Finds a customer by their mobile number.
 * @method bool scopeExistsByMobileNumber(string $mobileNumber) Checks if a customer exists with a specific mobile number.
 * @method int scopeCountByMobileNumber(string $mobileNumber) Counts customers by a specific mobile number.
 * @method AbstractCollection scopeFindByPartialMobileNumber(string $partialMobileNumber) Retrieves customers matching a partial mobile number.
 * @method AbstractCollection scopeFindByMobilePrefix(string $prefix) Retrieves customers whose mobile number starts with a given prefix.
 */
trait MobileNumberScopes
{
    /**
     * Finds a customer by their mobile number.
     *
     * @param string|null $mobileNumber The mobile number to search for.
     *
     * @throws LocalizedException If the mobile number is not provided.
     *
     * @return AbstractCollection|null The customer, or null if not found.
     */
    public function scopeFindByMobileNumber(?string $mobileNumber): ?AbstractCollection
    {
        if (! $this->validateMobileNumber($mobileNumber)) {
            throw LocalizedException::make(__('Mobile number cannot be null.'));
        }

        // Retrieve a single customer by mobile number using a fluent interface.
        return $this->getCollection()
            ->addAttributeToFilter(CustomerInterface::MOBILE_NUMBER, ['eq' => $mobileNumber])
            ->addAttributeToSelect('*');
    }

    /**
     * Checks if a customer exists with a specific mobile number.
     *
     * @param string $mobileNumber The mobile number to check.
     *
     * @return bool True if a customer exists with the provided mobile number.
     */
    public function scopeExistsByMobileNumber(string $mobileNumber): bool
    {
        // Check if any customer matches the mobile number using a fluent interface.
        return (bool)$this->getCollection()
            ->addAttributeToFilter(CustomerInterface::MOBILE_NUMBER, ['eq' => $mobileNumber])
            ->getSize();
    }

    /**
     * Counts customers by a specific mobile number.
     *
     * @param string $mobileNumber The mobile number for counting.
     *
     * @return int The count of customers with the specified mobile number.
     */
    public function scopeCountByMobileNumber(string $mobileNumber): int
    {
        // Count customers matching the mobile number using a fluent interface.
        return (int)$this->getCollection()
            ->addAttributeToFilter(CustomerInterface::MOBILE_NUMBER, ['eq' => $mobileNumber])
            ->getSize();
    }

    /**
     * Retrieves customers matching a partial mobile number.
     *
     * @param string $partialMobileNumber The partial number to match against.
     *
     * @return AbstractCollection The collection of customers matching the partial number.
     */
    public function scopeFindByPartialMobileNumber(string $partialMobileNumber): AbstractCollection
    {
        // Filters customers by partial mobile number using a fluent interface.
        return $this->getCollection()
            ->addAttributeToFilter(CustomerInterface::MOBILE_NUMBER, ['like' => '%' . $partialMobileNumber . '%'])
            ->addAttributeToSelect('*');
    }

    /**
     * Retrieves customers whose mobile number starts with a given prefix.
     *
     * @param string $prefix The prefix to match.
     *
     * @return AbstractCollection The collection of customers with matching prefixes.
     */
    public function scopeFindByMobilePrefix(string $prefix): AbstractCollection
    {
        // Filters customers by mobile number prefix using a fluent interface.
        return $this->getCollection()
            ->addAttributeToFilter(CustomerInterface::MOBILE_NUMBER, ['like' => $prefix . '%'])
            ->addAttributeToSelect('*');
    }

    /**
     * Validates that a mobile number is not null or empty.
     *
     * @param string|null $mobileNumber The mobile number to validate.
     *
     * @return bool True if the mobile number is valid; otherwise, false.
     */
    private function validateMobileNumber(?string $mobileNumber): bool
    {
        return ! empty($mobileNumber);
    }
}
