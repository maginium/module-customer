<?php

declare(strict_types=1);

namespace Maginium\CustomerElasticIndexer\Interfaces\Data;

use Maginium\Customer\Interfaces\Data\CustomerInterface as BaseCustomerInterface;

/**
 * Interface CustomerInterface.
 *
 * This interface defines constants used for interacting with the customer model in the Maginium module.
 * It includes table name, event prefix, and field identifiers that are commonly used across models and other parts
 * of the application related to the customer data.
 */
interface CustomerInterface extends BaseCustomerInterface
{
    /**
     * Entity table identifier.
     *
     * This constant represents the name of the table used to store customer data.
     * It is used across models, repositories, and other parts of the application to reference the database table.
     *
     * @var string
     */
    public const INDEX_NAME = 'customers';
}
