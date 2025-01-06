<?php

declare(strict_types=1);

namespace Maginium\Customer\Facades;

use Magento\Customer\Model\Visitor as VisitorManager;
use Maginium\Framework\Support\Facade;

/**
 * Class Visitor.
 *
 * Facade for interacting with the Visitor management, providing easy access to visitor-related operations,
 * including request handling, customer session binding, and visitor cleanup operations.
 *
 * Methods inherited from VisitorManager:
 *
 * @method static void __construct() Initialize the visitor manager.
 * @method static void setSkipRequestLogging(bool $skipRequestLogging) Set whether request logging should be skipped.
 * @method static void initByRequest(\Magento\Framework\Event\Observer $observer) Initialize visitor data by observing a request.
 * @method static void beforeSave() Perform operations before saving the visitor data.
 * @method static void afterSave() Perform operations after saving the visitor data.
 * @method static void saveByRequest(\Magento\Framework\Event\Observer $observer) Save visitor data based on the current request.
 * @method static bool isModuleIgnored(\Magento\Framework\Event\Observer $observer) Check if the module should ignore the current observer.
 * @method static void bindCustomerLogin(\Magento\Framework\Event\Observer $observer) Bind visitor data to a customer login event.
 * @method static void bindCustomerLogout(\Magento\Framework\Event\Observer $observer) Bind visitor data to a customer logout event.
 * @method static void bindQuoteCreate(\Magento\Framework\Event\Observer $observer) Bind visitor data to a quote creation event.
 * @method static void bindQuoteDestroy(\Magento\Framework\Event\Observer $observer) Bind visitor data to a quote destruction event.
 * @method static int getCleanTime() Retrieve the time configured for cleaning up visitor data.
 * @method static void clean() Perform cleanup operations for visitor data.
 * @method static int getOnlineInterval() Retrieve the time interval for considering a visitor as online.
 *
 * @see VisitorManager
 */
class Visitor extends Facade
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
        return VisitorManager::class;
    }
}
