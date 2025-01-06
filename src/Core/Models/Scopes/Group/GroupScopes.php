<?php

declare(strict_types=1);

namespace Maginium\Customer\Models\Scopes\Group;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Maginium\Foundation\Exceptions\LocalizedException;

/**
 * Trait GroupScopes.
 *
 * Defines methods related to customer group operations for Customer.
 *
 * @method AbstractCollection scopeFindByCustomerGroup(int|null $customerGroupId) Find customers by their customer group ID.
 * @method bool scopeExistsInCustomerGroup(int $customerGroupId) Checks if a customer exists in a specific customer group.
 * @method int scopeCountByCustomerGroup(int $customerGroupId) Counts customers in a specific customer group.
 * @method AbstractCollection scopeFindByCustomerGroups(array $customerGroupIds) Finds customers that belong to one of multiple customer groups.
 */
trait GroupScopes
{
    /**
     * Finds customers by their customer group.
     *
     * @param int|null $customerGroupId The customer group ID to search for.
     *
     * @throws LocalizedException If the customer group ID is not provided.
     *
     * @return AbstractCollection The collection of customers in the specified group.
     */
    public function scopeFindByCustomerGroup(?int $customerGroupId): AbstractCollection
    {
        if (! $this->validateCustomerGroup($customerGroupId)) {
            throw LocalizedException::make(__('Customer group ID cannot be null.'));
        }

        // Retrieve a collection of customers by their customer group ID.
        return $this->getCollection()
            ->addAttributeToFilter('group_id', ['eq' => $customerGroupId])
            ->addAttributeToSelect('*');
    }

    /**
     * Checks if a customer belongs to a specific customer group.
     *
     * @param int $customerGroupId The customer group ID to check.
     *
     * @return bool True if the customer belongs to the specified group.
     */
    public function scopeExistsInCustomerGroup(int $customerGroupId): bool
    {
        // Check if any customer belongs to the specified customer group.
        return (bool)$this->getCollection()
            ->addAttributeToFilter('group_id', ['eq' => $customerGroupId])
            ->getSize();
    }

    /**
     * Counts customers belonging to a specific customer group.
     *
     * @param int $customerGroupId The customer group ID for counting.
     *
     * @return int The count of customers in the specified customer group.
     */
    public function scopeCountByCustomerGroup(int $customerGroupId): int
    {
        // Count the customers belonging to the specified customer group.
        return (int)$this->getCollection()
            ->addAttributeToFilter('group_id', ['eq' => $customerGroupId])
            ->getSize();
    }

    /**
     * Retrieves customers that belong to one of multiple customer groups.
     *
     * @param array $customerGroupIds The customer group IDs to match.
     *
     * @return AbstractCollection The collection of customers in the specified groups.
     */
    public function scopeFindByCustomerGroups(array $customerGroupIds): AbstractCollection
    {
        // Filter customers by one of the provided customer group IDs.
        return $this->getCollection()
            ->addAttributeToFilter('group_id', ['in' => $customerGroupIds])
            ->addAttributeToSelect('*');
    }

    /**
     * Validates that a customer group ID is valid (i.e., not null or negative).
     *
     * @param int|null $customerGroupId The customer group ID to validate.
     *
     * @return bool True if the customer group ID is valid; otherwise, false.
     */
    private function validateCustomerGroup(?int $customerGroupId): bool
    {
        return isset($customerGroupId) && $customerGroupId > 0;
    }
}
