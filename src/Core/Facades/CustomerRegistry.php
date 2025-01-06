<?php

declare(strict_types=1);

namespace Maginium\Customer\Facades;

use Magento\Customer\Model\Data\CustomerSecure;
use Maginium\Customer\Models\Customer;
use Maginium\Customer\Models\CustomerRegistry as CustomerRegistryManager;
use Maginium\Framework\Support\Facade;

/**
 * Facade for interacting with the Customer Session service.
 *
 * @method static void remove(string $customerId) Removes a customer from the registry using the customer ID.
 * @method static Customer retrieve(string $customerId) Retrieves a customer by their unique customer ID.
 * @method static CustomerSecure retrieveSecureData(string $customerId) Retrieves secure data associated with a customer.
 * @method static Customer retrieveByEmail(string $customerEmail, ?sint $websiteId = null) Retrieves a customer by their email address and optionally website ID.
 * @method static Customer retrieveByMobileNumber(string $customerMobileNumber, ?sint $websiteId = null) Retrieves a customer by their mobile number and optionally website ID.
 * @method static void push(Customer $customer) Adds or updates a customer in the registry.
 * @method static void _resetState() Clears all customer registry data to reset the state.
 * @method static Customer retrieveByIdentifier(string $identifier, ?int $websiteId = null) Retrieves a Customer model by a given identifier (ID, email, or mobile number).
 *
 * @see CustomerRegistryManager
 */
class CustomerRegistry extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getAccessor(): string
    {
        return CustomerRegistryManager::class;
    }
}
