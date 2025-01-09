<?php

declare(strict_types=1);

namespace Maginium\CustomerAuth\Actions;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Model\AccountManagement;
use Magento\Framework\Exception\SecurityViolationException;
use Magento\Framework\Phrase;
use Maginium\Customer\Facades\CustomerSession;
use Maginium\CustomerAuth\Interfaces\ForgetPasswordInterface;
use Maginium\Foundation\Enums\HttpStatusCode;
use Maginium\Foundation\Exceptions\Exception;
use Maginium\Foundation\Exceptions\LocalizedException;
use Maginium\Foundation\Exceptions\NoSuchEntityException;
use Maginium\Framework\Actions\Concerns\AsAction;
use Maginium\Framework\Support\Facades\Escaper;
use Maginium\Framework\Support\Facades\Log;
use Maginium\Framework\Support\Validator;

/**
 * Class ForgetPassword.
 *
 * This class handles the password reset process for customers.
 * It validates the email, generates a password reset token, and sends the reset email.
 */
class ForgetPassword implements ForgetPasswordInterface
{
    // Using the AsAction trait to add common action functionality
    use AsAction;

    /**
     * @var AccountManagementInterface
     */
    protected AccountManagementInterface $customerAccountManagement;

    /**
     * ForgetPassword constructor.
     *
     * @param AccountManagementInterface $customerAccountManagement
     */
    public function __construct(
        AccountManagementInterface $customerAccountManagement,
    ) {
        $this->customerAccountManagement = $customerAccountManagement;

        // Set Logger class name
        Log::setClassName(static::class);
    }

    /**
     * Processes a password reset request for the specified email.
     *
     * This method validates the provided email address, checks if the email is associated
     * with a valid customer, and triggers the sending of a password reset link via email.
     *
     * If the email is invalid or the customer cannot be found, the relevant exceptions
     * (`LocalizedException`, `SecurityViolationException`, or `NoSuchEntityException`) will be thrown.
     *
     * @param string $email The email address of the customer requesting the password reset.
     *
     * @throws LocalizedException If an error occurs during the validation or processing of the email.
     * @throws SecurityViolationException If the email is associated with multiple customer accounts.
     * @throws NoSuchEntityException If no customer exists with the provided email.
     *
     * @return array|null A success message with a status code if the reset request is successful, or null if there's no further action required.
     */
    public function handle(string $email): ?array
    {
        // Validate the email address format
        if (! Validator::isEmail($email)) {
            // Save the invalid email for possible debugging or user feedback
            CustomerSession::setForgottenEmail($email);

            // Throw a localized exception to notify the user that the email format is incorrect
            throw LocalizedException::make(__('The email address is invalid. Please verify the email and try again.'));
        }

        try {
            // Get the website ID for the current store context
            $websiteId = (int)$this->getWebsiteId();

            // Initiate the password reset process for the provided email
            $this->customerAccountManagement->initiatePasswordReset($email, AccountManagement::EMAIL_RESET, $websiteId);

            // Return a successful response with a success message
            return $this->response()
                ->setPayload([]) // Optional: Include specific payload data if needed
                ->setStatusCode(HttpStatusCode::OK) // HTTP 200 (OK)
                ->setMessage($this->getSuccessMessage($email)) // Success message
                ->toArray();
        } catch (NoSuchEntityException $e) {
            // Do nothing, we don't want anyone to use this action to determine which email accounts are registered.
        } catch (LocalizedException|SecurityViolationException $e) {
            // Specific exceptions related to customer issues; rethrow without modification
            throw $e;
        } catch (Exception $e) {
            // Catch any unexpected exceptions and throw a localized error message
            throw LocalizedException::make(
                __('We were unable to process your request. Please try again later.'),
                $e,
                HttpStatusCode::INTERNAL_SERVER_ERROR,
            );
        }
    }

    /**
     * Retrieve success message.
     *
     * @param string $email
     *
     * @return Phrase
     */
    private function getSuccessMessage($email)
    {
        return __(
            'If there is an account associated with %1 you will receive an email with a link to reset your password.',
            Escaper::escapeHtml($email),
        );
    }
}
