<?php

declare(strict_types=1);

namespace Maginium\Customer\Facades;

use Maginium\Customer\Interfaces\Data\CustomerInterface;
use Maginium\Framework\Database\Interfaces\Data\ModelInterface;
use Maginium\Framework\Support\Facade;

/**
 * Class Customer.
 *
 * Facade for interacting with the Customer Repository, which retrieves country information.
 *
 * @method static int|null getSortOrder() Get the sort order of the customer.
 * @method static void setSortOrder(int $sortOrder) Set the sort order of the customer.
 * @method static bool isDefault() Check if the customer is the default customer.
 * @method static void setIsDefault(bool $isDefault) Set whether the customer is the default.
 * @method static int getId() Get the customer ID.
 * @method static void setId(int $id) Set the customer ID.
 * @method static string getCode() Get the customer code.
 * @method static void setCode(string $code) Set the customer code.
 * @method static string getName() Get the customer name.
 * @method static void setName(string $name) Set the customer name.
 * @method static int getDefaultGroupId() Get the default group ID for the customer.
 * @method static void setDefaultGroupId(int $defaultGroupId) Set the default group ID for the customer.
 * @method static \Magento\Store\Api\Data\CustomerExtensionInterface|null getExtensionAttributes() Retrieve the customer's extension attributes.
 * @method static void setExtensionAttributes(\Magento\Store\Api\Data\CustomerExtensionInterface $extensionAttributes) Set the customer's extension attributes.
 *
 * @see CustomerInterface
 * @see ModelInterface
 */
class Customer extends Facade
{
    /**
     * Get the accessor for the facade.
     *
     * This method must be implemented by subclasses to return the accessor string
     * corresponding to the underlying service or class the facade represents.
     *
     * @return string The accessor for the facade.
     */
    protected static function getAccessor(): string
    {
        return CustomerInterface::class;
    }
}
