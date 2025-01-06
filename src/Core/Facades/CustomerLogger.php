<?php

declare(strict_types=1);

namespace Maginium\Customer\Facades;

use Magento\Customer\Model\Log;
use Magento\Customer\Model\Logger as CustomerLoggerManager;
use Maginium\Framework\Support\Facade;

/**
 * Facade for interacting with the Customer Logger service.
 *
 * @method static void log(int $customerId, array $data) Save or update log for the given customer.
 * @method static Log get(int $customerId = null) Load the log for the specified customer ID.
 *
 * @see CustomerLoggerManager
 */
class CustomerLogger extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getAccessor(): string
    {
        return CustomerLoggerManager::class;
    }
}
