<?php

declare(strict_types=1);

namespace Maginium\CustomerAuth\Actions;

use Maginium\CustomerAuth\Dtos\LoginDto;
use Maginium\CustomerAuth\Enums\Strategies;
use Maginium\CustomerAuth\Interfaces\LoginInterface;
use Maginium\CustomerAuth\Strategies\Email;
use Maginium\CustomerAuth\Strategies\Phone;
use Maginium\CustomerMagicLinkAuth\Strategies\MagicLink;
use Maginium\CustomerSocialLoginAuth\Strategies\Apple;
use Maginium\CustomerSocialLoginAuth\Strategies\Google;
use Maginium\Foundation\Exceptions\BadRequestException;
use Maginium\Foundation\Exceptions\LocalizedException;
use Maginium\Foundation\Exceptions\NotFoundException;
use Maginium\Framework\Actions\Concerns\AsAction;
use Maginium\Framework\Support\Facades\Log;
use Maginium\Framework\Support\Str;

/**
 * Class Login.
 *
 * This class handles the login process for customers, validating their credentials
 * against different authentication strategies (e.g., email, phone, social logins).
 * If the customer is already logged in, a response with the customer data is returned.
 * Otherwise, it delegates to specific strategies based on the provided login method.
 */
class Login implements LoginInterface
{
    // Using the AsAction trait to add common action functionality
    use AsAction;

    /**
     * Constructor to initialize dependencies for the Login action.
     */
    public function __construct()
    {
        // Sets the class name for logging purposes
        Log::setClassName(static::class);
    }

    /**
     * Handles the customer login process.
     *
     * This method verifies the provided login credentials (e.g., email, phone number, password)
     * and returns a response with a success message and customer token if the login is successful.
     * If the customer is already logged in, it returns the customer data without requiring re-authentication.
     *
     * @param LoginDto $data The Data Transfer Object containing the login credentials (e.g., strategy, credentials).
     *
     * @throws NotFoundException If no customer is found matching the provided credentials.
     * @throws LocalizedException For unexpected errors that occur during the login process.
     * @throws BadRequestException If the provided login strategy is unsupported.
     *
     * @return array<string, mixed> The response array containing success message, token, and HTTP status code.
     */
    public function handle(LoginDto $data): array
    {
        // If not logged in, determine the strategy to use for authentication
        // Convert the strategy to lowercase to handle case-insensitivity
        return $this->authenticateWithStrategy(Str::lower($data->getStrategy()), $data);
    }

    /**
     * Handles the authentication based on the provided strategy.
     *
     * This method delegates the authentication process to the appropriate strategy
     * based on the login method (e.g., email, phone, Google, Apple, etc.).
     *
     * @param string $strategy The authentication strategy (e.g., 'email', 'phone', etc.).
     * @param LoginDto $data The Data Transfer Object containing login credentials.
     *
     * @throws BadRequestException If the strategy is unsupported.
     *
     * @return array<string, mixed> The response array containing the result of the authentication process.
     */
    private function authenticateWithStrategy(string $strategy, LoginDto $data): array
    {
        // Use a match expression to delegate to the correct strategy based on the provided login method
        return match ($strategy) {
            // If the strategy is EMAIL, call the Email strategy's run method
            Strategies::EMAIL => Email::run($data),

            // If the strategy is PHONE, call the Phone strategy's run method
            Strategies::PHONE => Phone::run($data),

            // If the strategy is GOOGLE, call the Google strategy's run method
            Strategies::GOOGLE => Google::run($data),

            // If the strategy is APPLE, call the Apple strategy's run method
            Strategies::APPLE => Apple::run($data),

            // If the strategy is MAGIC_LINK, call the MagicLink strategy's run method
            Strategies::MAGIC_LINK => MagicLink::run($data),

            // If the strategy is not recognized, throw a BadRequestException
            // with a message indicating that the strategy is unsupported
            default => throw new BadRequestException(__('Unsupported login strategy')),
        };
    }
}
