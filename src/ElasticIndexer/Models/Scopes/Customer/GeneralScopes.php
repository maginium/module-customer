<?php

declare(strict_types=1);

namespace Maginium\CustomerElasticIndexer\Models\Scopes\Customer;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Trait CustomerScopes.
 *
 * This trait provides scope methods specifically for filtering customer-based data,
 * such as by `customer_id`, `customer_code`, and other customer-related attributes.
 *
 * @method AbstractCollection scopeCustomerId(int $customerId) Find customers by their `customer_id`.
 */
trait GeneralScopes
{
}
