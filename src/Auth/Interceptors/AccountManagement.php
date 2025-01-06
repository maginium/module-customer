<?php

declare(strict_types=1);

namespace Maginium\CustomerAuth\Interceptors;

use Magento\Customer\Model\AccountManagement as BaseAccountManagement;
use Magento\Framework\App\Area;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Config\ScopeInterface;
use Magento\Framework\Exception\SecurityViolationException;
use Magento\Security\Model\PasswordResetRequestEvent;
use Magento\Security\Model\SecurityManager;

/**
 * Decorator for AccountManagement.
 *
 * This class intercepts and enhances the password reset process by adding additional
 * security checks for specific areas (e.g., REST API or Admin Panel).
 */
class AccountManagement
{
    /**
     * @var RequestInterface The HTTP request instance.
     */
    protected RequestInterface $request;

    /**
     * @var SecurityManager Handles security checks for various operations.
     */
    protected SecurityManager $securityManager;

    /**
     * @var int Represents the type of password reset request.
     */
    protected int $passwordResetRequestEvent;

    /**
     * @var ScopeInterface Provides access to the current application scope.
     */
    private ScopeInterface $scope;

    /**
     * Constructor.
     *
     * Initializes the interceptor with required dependencies, including the request,
     * security manager, and optional scope configuration.
     *
     * @param RequestInterface $request The HTTP request instance.
     * @param SecurityManager $securityManager The security manager instance.
     * @param ScopeInterface|null $scope Optional scope configuration. Defaults to the current scope.
     * @param int $passwordResetRequestEvent The event type for password reset.
     */
    public function __construct(
        ?ScopeInterface $scope,
        RequestInterface $request,
        SecurityManager $securityManager,
        int $passwordResetRequestEvent = PasswordResetRequestEvent::CUSTOMER_PASSWORD_RESET_REQUEST,
    ) {
        $this->scope = $scope;
        $this->request = $request;
        $this->securityManager = $securityManager;
        $this->passwordResetRequestEvent = $passwordResetRequestEvent;
    }

    /**
     * Intercepts the password reset initiation process.
     *
     * This method performs a security check when the request is initiated from specific
     * application areas (e.g., REST API) or if the password reset request is of a particular type.
     *
     * @param BaseAccountManagement $accountManagement The original account management class.
     * @param string $email The customer's email address.
     * @param string $template The email template identifier.
     * @param int|null $websiteId Optional website ID for the password reset request.
     *
     * @throws SecurityViolationException If the security check fails.
     *
     * @return array The arguments to pass to the original method.
     */
    public function beforeInitiatePasswordReset(
        BaseAccountManagement $accountManagement,
        string $email,
        string $template,
        ?int $websiteId = null,
    ): array {
        // Perform security checks only for specific application areas or request types
        if (
            $this->scope->getCurrentScope() === Area::AREA_WEBAPI_REST
            || $this->passwordResetRequestEvent === PasswordResetRequestEvent::ADMIN_PASSWORD_RESET_REQUEST
        ) {
            // Invoke the security manager to validate the request
            $this->securityManager->performSecurityCheck(
                $this->passwordResetRequestEvent,
                $email,
            );
        }

        // Return the original parameters for the password reset process
        return [$email, $template, $websiteId];
    }
}
