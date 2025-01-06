<?php

declare(strict_types=1);

namespace Maginium\Customer\Facades;

use Magento\Customer\Model\Session as CustomerSessionManager;
use Maginium\Customer\Interfaces\Data\CustomerInterface;
use Maginium\Framework\Support\Facade;

/**
 * Facade for interacting with the Customer Session service.
 *
 * @method static array getCustomerConfigShare() Retrieves customer configuration related to shared session settings.
 * @method static void setCustomerData(CustomerInterface $customer) Sets the customer data object.
 * @method static CustomerInterface getCustomerData() Retrieves the customer data object.
 * @method static CustomerInterface getCustomerDataObject() Retrieves the customer data as an object.
 * @method static void setCustomerDataObject(CustomerInterface $customerData) Sets the customer data object.
 * @method static void setCustomer(Customer $customerModel) Sets the customer model.
 * @method static CustomerInterface getCustomer() Retrieves the customer model.
 * @method static void setCustomerId($id) Sets the customer ID.
 * @method static int|null getCustomerId() Retrieves the customer ID.
 * @method static int|null getId() Alias for retrieving the customer ID.
 * @method static void setId($customerId) Alias for setting the customer ID.
 * @method static void setCustomerGroupId($id) Sets the customer group ID.
 * @method static int|null getCustomerGroupId() Retrieves the customer group ID.
 * @method static void _resetState() Resets the session state to its initial configuration.
 * @method static bool isLoggedIn() Checks if a customer is currently logged in.
 * @method static bool checkCustomerId($customerId) Verifies the provided customer ID against the session.
 * @method static void setCustomerAsLoggedIn(Customer $customer) Marks the provided customer as logged in.
 * @method static void setCustomerDataAsLoggedIn(CustomerInterface $customer) Marks the customer data as logged in.
 * @method static bool loginById($customerId) Logs in the customer using their ID.
 * @method static void logout() Logs out the current customer.
 * @method static bool authenticate(string $loginUrl = null) Authenticates the customer session. Optionally redirects to a login URL.
 * @method static void setBeforeAuthUrl(string $url) Sets the URL to redirect to before authentication.
 * @method static void setAfterAuthUrl(string $url) Sets the URL to redirect to after authentication.
 * @method static void regenerateId() Regenerates the session ID.
 * @method static void writeClose() Closes the session and writes any changes to the session data.
 * @method static void __call(string $method, array $args) Magic method for calling methods on the underlying session service.
 * @method static void start() Starts a new session.
 * @method static void registerShutdown() Registers the session for shutdown processing.
 * @method static bool isSessionExists() Checks if a session exists.
 * @method static mixed getData(string $key = '', bool $clear = false) Retrieves session data by key, optionally clearing it.
 * @method static string getSessionId() Retrieves the current session ID.
 * @method static string getName() Retrieves the session name.
 * @method static void setName(string $name) Sets the session name.
 * @method static void destroy(array $options = null) Destroys the current session with optional options.
 * @method static void clearStorage() Clears session storage.
 * @method static string getCookieDomain() Retrieves the domain for the session cookie.
 * @method static string getCookiePath() Retrieves the path for the session cookie.
 * @method static int getCookieLifetime() Retrieves the lifetime for the session cookie.
 * @method static void setSessionId(string $sessionId) Sets the session ID.
 * @method static string getSessionIdForHost(string $urlHost) Retrieves the session ID for a specific host.
 * @method static bool isValidForHost(string $host) Checks if the session is valid for a specific host.
 * @method static bool isValidForPath(string $path) Checks if the session is valid for a specific path.
 * @method static void expireSessionCookie() Expires the session cookie.
 *
 * @see CustomerSessionManager
 */
class CustomerSession extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getAccessor(): string
    {
        return CustomerSessionManager::class;
    }
}
