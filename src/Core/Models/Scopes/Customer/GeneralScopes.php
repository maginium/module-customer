<?php

declare(strict_types=1);

namespace Maginium\Customer\Models\Scopes\Customer;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Maginium\Customer\Interfaces\Data\CustomerInterface;
use Maginium\Customer\Models\Customer;

/**
 * Trait CustomerScopes.
 *
 * Defines general scopes for customer operations.
 *
 * @method AbstractCollection|null scopeFindById(int $customerId) Finds a customer by their ID.
 * @method AbstractCollection|null scopeFindByEmail(string $email) Finds a customer by their email.
 * @method bool scopeExistsByEmail(string $email) Checks if a customer exists with a specific email.
 * @method int scopeCountByEmail(string $email) Counts customers with a specific email.
 * @method AbstractCollection scopeFindByGroup(int $groupId) Retrieves customers by their customer group.
 * @method AbstractCollection scopeFindByStatus(string $status) Retrieves customers based on their account status.
 * @method AbstractCollection scopeFindByCreatedAt(string $date) Retrieves customers created on or after the given date.
 * @method AbstractCollection scopeFindByUpdatedAt(string $date) Retrieves customers updated on or after the given date.
 */
trait GeneralScopes
{
    /**
     * Finds a customer by their ID.
     *
     * @param int $customerId The customer ID to search for.
     *
     * @return AbstractCollection|null The customer, or null if not found.
     */
    public function scopeFindById(int $customerId): ?AbstractCollection
    {
        return $this->getCollection()
            ->addFieldToFilter('model_id', ['eq' => $customerId])
            ->addAttributeToSelect('*');
    }

    /**
     * Finds a customer by their email.
     *
     * @param string $email The email address to search for.
     *
     * @return CustomerInterface|null The customer, or null if not found.
     */
    public function scopeFindByEmail(string $email): ?AbstractCollection
    {
        return $this->getCollection()
            ->addFieldToFilter('email', ['eq' => $email])
            ->addAttributeToSelect('*');
    }

    /**
     * Checks if a customer exists with a specific email.
     *
     * @param string $email The email address to check.
     *
     * @return bool True if a customer exists with the provided email.
     */
    public function scopeExistsByEmail(string $email): bool
    {
        return (bool)$this->getCollection()
            ->addFieldToFilter('email', ['eq' => $email])
            ->getSize();
    }

    /**
     * Counts customers with a specific email.
     *
     * @param string $email The email address to check.
     *
     * @return int The count of customers with the specified email.
     */
    public function scopeCountByEmail(string $email): int
    {
        return (int)$this->getCollection()
            ->addFieldToFilter('email', ['eq' => $email])
            ->getSize();
    }

    /**
     * Retrieves customers by their customer group.
     *
     * @param int $groupId The customer group ID.
     *
     * @return AbstractCollection The collection of customers in the specified group.
     */
    public function scopeFindByGroup(int $groupId): AbstractCollection
    {
        return $this->getCollection()
            ->addFieldToFilter('group_id', ['eq' => $groupId])
            ->addAttributeToSelect('*');
    }

    /**
     * Retrieves customers based on their account status (e.g., enabled or disabled).
     *
     * @param string $status The status of the account (e.g., 'enabled', 'disabled').
     *
     * @return AbstractCollection The collection of customers with the specified status.
     */
    public function scopeFindByStatus(string $status): AbstractCollection
    {
        return $this->getCollection()
            ->addFieldToFilter('status', ['eq' => $status])
            ->addAttributeToSelect('*');
    }

    /**
     * Retrieves customers created on or after the given date.
     *
     * @param string $date The date to filter customers by.
     *
     * @return AbstractCollection The collection of customers created on or after the specified date.
     */
    public function scopeFindByCreatedAt(string $date): AbstractCollection
    {
        return $this->getCollection()
            ->addFieldToFilter('created_at', ['gteq' => $date])
            ->addAttributeToSelect('*');
    }

    /**
     * Retrieves customers updated on or after the given date.
     *
     * @param string $date The date to filter customers by.
     *
     * @return AbstractCollection The collection of customers updated on or after the specified date.
     */
    public function scopeFindByUpdatedAt(string $date): AbstractCollection
    {
        return $this->getCollection()
            ->addFieldToFilter('updated_at', ['gteq' => $date])
            ->addAttributeToSelect('*');
    }
}
