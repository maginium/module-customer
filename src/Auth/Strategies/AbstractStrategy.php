<?php

declare(strict_types=1);

namespace Maginium\CustomerAuth\Strategies;

use Exception;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Exception\EmailNotConfirmedException;
use Magento\Integration\Model\Oauth\Token\RequestThrottler;
use Magento\Persistent\Helper\Data as PersistentHelper;
use Magento\Persistent\Helper\Session as PersistentSession;
use Maginium\Customer\Facades\CustomerSession;
use Maginium\Customer\Facades\CustomerUrl;
use Maginium\Customer\Interfaces\Data\CustomerInterface;
use Maginium\Customer\Interfaces\Repositories\CustomerRepositoryInterface;
use Maginium\CustomerAuth\Dtos\LoginDto;
use Maginium\CustomerAuth\Interfaces\LoginInterface;
use Maginium\Foundation\Enums\HttpStatusCode;
use Maginium\Foundation\Exceptions\AuthenticationException;
use Maginium\Foundation\Exceptions\LocalizedException;
use Maginium\Foundation\Exceptions\NotFoundException;
use Maginium\Framework\Actions\Concerns\AsAction;
use Maginium\Framework\Support\Arr;
use Maginium\Framework\Support\Facades\Defer;
use Maginium\Framework\Support\Facades\Escaper;
use Maginium\Framework\Support\Facades\Event;
use Maginium\Framework\Support\Facades\Log;
use Maginium\Framework\Support\Facades\Token;

/**
 * Class AbstractStrategy.
 * Abstract strategy for handling customer login and related operations.
 */
abstract class AbstractStrategy
{
    // Using the AsAction trait to add common action functionality
    use AsAction;

    /**
     * Event triggered before customer authentication.
     */
    public const CUSTOMER_AUTHENTICATION_BEFORE = 'customer_authentication_before';

    /**
     * Event name for customer authentication.
     */
    public const EVENT_CUSTOMER_AUTHENTICATED = 'customer_authenticated';

    /**
     * Event triggered after customer authentication.
     */
    public const CUSTOMER_AUTHENTICATION_AFTER = 'customer_authentication_after';

    /**
     * @var CustomerRepositoryInterface
     */
    protected CustomerRepositoryInterface $customerRepository;

    /**
     * @var PersistentSession
     */
    private PersistentSession $persistentSession;

    /**
     * @var PersistentHelper
     */
    private PersistentHelper $persistentHelper;

    /**
     * @var CheckoutSession
     */
    private CheckoutSession $checkoutSession;

    /**
     * @var RequestThrottler
     */
    private RequestThrottler $requestThrottler;

    /**
     * Constructor.
     *
     * Initializes dependencies for the login strategy.
     *
     * @param CheckoutSession $checkoutSession Manages checkout session data.
     * @param RequestThrottler $requestThrottler Manages request throttling.
     * @param PersistentHelper $persistentHelper Provides helper methods for persistence logic.
     * @param PersistentSession $persistentSession Handles persistent customer sessions.
     * @param CustomerRepositoryInterface $customerRepository Retrieves customer data.
     */
    public function __construct(
        CheckoutSession $checkoutSession,
        RequestThrottler $requestThrottler,
        PersistentHelper $persistentHelper,
        PersistentSession $persistentSession,
        CustomerRepositoryInterface $customerRepository,
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->requestThrottler = $requestThrottler;
        $this->persistentHelper = $persistentHelper;
        $this->persistentSession = $persistentSession;
        $this->customerRepository = $customerRepository;

        Log::setClassName(static::class);
    }

    /**
     * Handles customer login and response.
     *
     * This method verifies the customer's credentials, processes the login,
     * and generates the necessary authentication token.
     *
     * @param LoginDto $data The login data containing the customer's identifier and password.
     *
     * @throws NotFoundException If no customer is found with the provided identifier.
     * @throws InvalidCredentialsException If the provided password is incorrect.
     * @throws LocalizedException If an unexpected error occurs during the login process.
     *
     * @return array The login result containing customer data, token, and status.
     */
    public function handle(LoginDto $data): ?array
    {
        try {
            // Dispatch an event before authentication starts
            Event::dispatchNow(self::CUSTOMER_AUTHENTICATION_BEFORE, ['data' => $data]);

            // Throttle login attempts to prevent brute-force attacks
            $this->throttleLoginAttempts($data);

            // Begin store emulation to match the customer's store environment
            $this->startEmulation();

            // Retrieve the website ID for the current request
            $websiteId = $this->getWebsiteId();

            // Authenticate the customer using the provided credentials
            $customer = $this->authenticate($data, $websiteId);

            // Dispatch an event after the authentication process
            Event::dispatchNow(self::CUSTOMER_AUTHENTICATION_AFTER, ['data' => $customer]);

            // Validate the authentication result to ensure the customer is active and authorized
            $this->validate($customer, $websiteId);

            // Generate a unique token for the authenticated customer
            $token = $this->generateCustomerToken($customer);

            // Schedule the "Remember Me" status setting to be executed later
            Defer::execute(fn() => $this->setRememberMe($data->getRememberMe()));

            // Merge customer data with the generated token for the response payload
            $customerData = Arr::merge($customer->toDataArray(), $token);

            // Prepare the response object with payload, HTTP status code, success message, and metadata
            $response = $this->response()
                ->setPayload($customerData) // Include the customer data and token
                ->setStatusCode(HttpStatusCode::OK) // Set the HTTP status code to 200 (OK)
                ->setMessage(__('Customer logged in successfully')); // Include a localized success message

            // Return the response as an associative array
            return $response->toArray();
        } catch (EmailNotConfirmedException $e) {
            // Handle scenarios where the customer's email is not confirmed
            return $this->handleEmailNotConfirmed($data);
        } catch (AuthenticationException|LocalizedException $e) {
            // Reset failed login attempts in case of authentication or localized errors
            $this->resetFailedAttempts($data);

            // Rethrow the exception for further handling
            throw $e;
        } catch (Exception $e) {
            // Reset failed login attempts for generic exceptions
            $this->resetFailedAttempts($data);

            // Wrap and rethrow the exception with additional details and an HTTP 500 status code
            throw LocalizedException::make(
                __('An error occurred during customer login: %1', $e->getMessage()), // Localized error message with details
                $e, // Attach the original exception
                HttpStatusCode::INTERNAL_SERVER_ERROR, // Set the HTTP status code to 500 (Internal Server Error)
            );
        } finally {
            // Ensure the customer's username is always set in the session, regardless of success or failure
            CustomerSession::setUsername($data->getIdentifier());
        }
    }

    /**
     * Dispatch the customer authenticated event.
     *
     * This method dispatches the `customer_authenticated` event immediately.
     *
     * @return void
     */
    protected function dispatchCustomerAuthenticatedEvent(CustomerInterface $model, string $password): void
    {
        Event::dispatchNow(self::EVENT_CUSTOMER_AUTHENTICATED, ['model' => $model, 'password' => $password]);
    }

    /**
     * Throttles login attempts to prevent brute-force attacks.
     *
     * This method limits the number of login attempts a user can make within a specified time frame to
     * prevent brute-force attacks and protect user accounts.
     *
     * @param LoginDto $data The login data containing the customer's identifier.
     */
    private function throttleLoginAttempts(LoginDto $data): void
    {
        // Check if the identifier is present in the login data.
        if ($data->getIdentifier()) {
            // Call the throttle method to limit the login attempts for the given identifier.
            $this->requestThrottler->throttle($data->getIdentifier(), RequestThrottler::USER_TYPE_CUSTOMER);
        }
    }

    /**
     * Validates the authentication result for the customer.
     *
     * This method checks if the customer is authenticated, if the customer is associated with the correct website,
     * and if there is no ongoing password reset process.
     *
     * @param CustomerInterface|false $customer The authenticated customer instance.
     * @param int $websiteId The website ID to check for the customer's association.
     *
     * @throws AuthenticationException If authentication fails or the customer is not associated with the website.
     */
    private function validate($customer, int $websiteId): void
    {
        // Check if the customer authentication failed.
        if ($customer === false) {
            // Throw an exception if authentication failed.
            throw AuthenticationException::make(__('Authentication failed. Please check your credentials.'));
        }

        // Check if the customer is associated with the correct website.
        if ((int)$customer->getWebsiteId() !== $websiteId) {
            // Throw an exception if the customer is not associated with the current website.
            throw AuthenticationException::make(__('Customer is not associated with the current website.'));
        }

        // Check if the customer has requested a password reset.
        if ($customer->getRpTokenCreatedAt() !== null) {
            // Throw an exception if a password reset request is in progress.
            throw AuthenticationException::make(__('Password reset token already requested. Please reset your password.'));
        }
    }

    /**
     * Generates a token for the authenticated customer.
     *
     * This method generates an authentication token for the customer to be used in future requests.
     *
     * @param CustomerInterface $customer The authenticated customer instance.
     *
     * @return array The generated token data, including the token for the customer.
     */
    private function generateCustomerToken(CustomerInterface $customer): array
    {
        // Create and return a customer-specific token.
        return [
            LoginInterface::TOKEN => Token::customer()->create((int)$customer->getId()),
        ];
    }

    /**
     * Handles the case where the email is not confirmed.
     *
     * This method prepares a response to inform the user that their email address is not confirmed,
     * including a link to resend the confirmation email.
     *
     * @param LoginDto $data The login data containing the identifier for the customer.
     *
     * @return array The response indicating email confirmation is required.
     */
    private function handleEmailNotConfirmed(LoginDto $data): array
    {
        // Generate the email confirmation URL using the identifier from the login data.
        $url = CustomerUrl::getEmailConfirmationUrl($data->getIdentifier());

        // Prepare a response indicating the email confirmation is required, including the URL.
        $response = $this->response()
            ->setPayload(['url' => $url, 'message' => $this->getEmailConfirmationMessage($url)])
            ->setStatusCode(HttpStatusCode::OK)
            ->setMessage($this->getEmailConfirmationMessage($url)); // Set the message with the URL.

        // Return the formatted result as an associative array
        return $response->toArray();
    }

    /**
     * Resets authentication failure counts after an exception is thrown.
     *
     * This method resets the count of failed login attempts for the user to allow new attempts
     * after an error occurs or an exception is thrown.
     *
     * @param LoginDto $data The login data containing the customer's identifier.
     */
    private function resetFailedAttempts(LoginDto $data): void
    {
        // Check if the identifier is present in the login data.
        if ($data->getIdentifier()) {
            // Reset the count of failed authentication attempts for the customer.
            $this->requestThrottler->resetAuthenticationFailuresCount($data->getIdentifier(), RequestThrottler::USER_TYPE_CUSTOMER);
        }
    }

    /**
     * Retrieves the email confirmation message with a clickable URL.
     *
     * This method generates a message to inform the user that their email is not confirmed,
     * with a clickable link to resend the confirmation email.
     *
     * @param string $url The email confirmation URL to be embedded in the message.
     *
     * @return string The confirmation message with the URL.
     */
    private function getEmailConfirmationMessage(string $url): string
    {
        // Generate the email confirmation message with a link to resend the confirmation email.
        return Escaper::escapeHtml(
            __('You must confirm your account. Please check your email for the confirmation link or <a href="%1">Click here</a> to resend confirmation email.', $url),
            ['a'], // Allow the <a> tag for the hyperlink.
        );
    }

    /**
     * Set the "Remember Me" status in the session.
     *
     * This method ensures that the "Remember Me" functionality is enabled
     * and updates the persistent and checkout sessions accordingly.
     *
     * @param bool $status The "Remember Me" status to set (true to enable, false to disable).
     *
     * @return void
     */
    private function setRememberMe(bool $status): void
    {
        // Check if the persistent "Remember Me" feature is enabled in the configuration
        if (! $this->persistentHelper->isEnabled() || ! $this->persistentHelper->isRememberMeEnabled()) {
            return; // Exit early if "Remember Me" functionality is disabled
        }

        // Update the checkout session with the "Remember Me" status
        $this->checkoutSession->setRememberMeChecked($status);

        // Update the persistent session with the "Remember Me" status
        $this->persistentSession->setRememberMeChecked($status);
    }
}
