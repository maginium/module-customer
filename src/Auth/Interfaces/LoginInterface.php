<?php

declare(strict_types=1);

namespace Maginium\CustomerAuth\Interfaces;

use Maginium\CustomerAuth\Dtos\LoginDto;
use Maginium\Foundation\Exceptions\InvalidCredentialsException;
use Maginium\Foundation\Exceptions\LocalizedException;
use Maginium\Foundation\Exceptions\NotFoundException;

/**
 * Defines the contract for the customer login process.
 *
 * Includes methods for verifying customer credentials and handling the login operation.
 * Upon successful authentication, generates a token and triggers a login event.
 */
interface LoginInterface
{
    /**
     * Key used to store the customer authentication token.
     */
    public const TOKEN = 'token';

    /**
     * Handles the customer login process.
     *
     * Verifies the provided identifier (e.g., email, mobile number) and password.
     * If valid, returns a success response with a generated customer token.
     * Triggers appropriate exceptions for errors or invalid credentials.
     *
     * @param LoginDto $data Data Transfer Object containing login credentials.
     *
     * @throws NotFoundException If no customer is found with the provided identifier.
     * @throws InvalidCredentialsException If the provided password is incorrect.
     * @throws LocalizedException For unexpected errors during the login process.
     *
     * @return array<string, mixed> Response containing a success message, token,
     *                              and HTTP status code.
     */
    public function handle(LoginDto $data): array;
}
