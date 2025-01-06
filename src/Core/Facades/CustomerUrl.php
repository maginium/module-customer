<?php

declare(strict_types=1);

namespace Maginium\Customer\Facades;

use Magento\Customer\Model\Url as CustomerUrlManager;
use Maginium\Framework\Support\Facade;

/**
 * Class CustomerUrl.
 *
 * Facade for interacting with the Customer URL Manager, which provides URLs for various customer-related actions.
 *
 * @method static int|null getSortOrder() Get the sort order of the customer.
 * @method static string getLoginUrl() Get the URL for the customer login page.
 * @method static array getLoginUrlParams() Get the parameters for the customer login URL.
 * @method static string getLoginPostUrl() Get the URL for the customer login POST action.
 * @method static string getLogoutUrl() Get the URL for the customer logout action.
 * @method static string getDashboardUrl() Get the URL for the customer dashboard.
 * @method static string getAccountUrl() Get the URL for the customer account page.
 * @method static string getRegisterUrl() Get the URL for the customer registration page.
 * @method static string getRegisterPostUrl() Get the URL for the customer registration POST action.
 * @method static string getEditUrl() Get the URL for editing customer account information.
 * @method static string getEditPostUrl() Get the URL for the customer account edit POST action.
 * @method static string getForgotPasswordUrl() Get the URL for the forgot password page.
 * @method static string getEmailConfirmationUrl(string|null $email = null) Get the URL for email confirmation, optionally for a specific email.
 *
 * @see CustomerUrlManager
 */
class CustomerUrl extends Facade
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
        return CustomerUrlManager::class;
    }
}
