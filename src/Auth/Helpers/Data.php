<?php

declare(strict_types=1);

namespace Maginium\CustomerAuth\Helpers;

use Maginium\CustomerAuth\Enums\Strategies;
use Maginium\Framework\Support\Str;

/**
 * Class Data.
 *
 * This helper class provides utility methods for masking sensitive data like phone numbers and emails,
 * validating phone numbers, and generating unique tokens for password reset links.
 */
class Data
{
    /**
     * Mask the input based on the specified type (email or phone).
     *
     * This method hides part of the input for privacy or security purposes,
     * depending on the type of data being masked.
     *
     * @param string $maskInput The input to be masked (email or phone number).
     * @param string|null $type The type of mask ("phone" or "email"). Defaults to null.
     *                          Must be one of Strategies::EMAIL or Strategies::PHONE.
     *
     * @return string The masked version of the input.
     */
    public static function mask($maskInput, $type = null)
    {
        // If the mask type is email, call the maskEmail method
        if ($type === Strategies::EMAIL) {
            return self::maskEmail($maskInput);
        }

        // If the mask type is phone, call the maskPhoneNumber method
        if ($type === Strategies::PHONE) {
            return self::maskPhoneNumber($maskInput);
        }

        // If no specific type is provided or an invalid type is given, return the original input
        return $maskInput;
    }

    /**
     * Mask a phone number by replacing most digits with asterisks.
     *
     * The phone number will have the first digit visible, followed by asterisks, and the last two digits visible.
     *
     * @param string $number The phone number to mask.
     *
     * @return string The masked phone number.
     */
    public static function maskPhoneNumber($number)
    {
        // Keep the first digit visible and mask all but the last two digits with asterisks
        return Str::substr($number, 0, 1) . Str::repeat('*', Str::length($number) - 3) . Str::substr($number, -2);
    }

    /**
     * Mask an email address by hiding part of the username.
     *
     * The email will have the first and last character of the username visible, and the middle part replaced with asterisks.
     *
     * @param string $email The email to mask.
     * @param int $fill The number of characters to keep visible in the username part. Default is 4.
     *                  The number of visible characters is adjusted if necessary.
     *
     * @return string The masked email.
     */
    public static function maskEmail($email, $fill = 4)
    {
        // Extract the user part of the email before the '@'
        $user = mb_strstr($email, '@', true);
        $len = Str::length($user);

        // If the user part is too short, adjust the number of visible characters
        if ($len > $fill + 2) {
            $fill = $len - 2;
        }

        // Construct the masked email by keeping the first and last character visible, and masking the middle part
        return Str::substr($user, 0, 1) . Str::repeat('*', $fill) . Str::substr($user, -1) . mb_strstr($email, '@');
    }

    /**
     * Validate a phone number format.
     *
     * This method ensures that the phone number starts with a plus sign (+)
     * and contains between 1 and 12 digits. The number should only consist of numeric characters
     * and may contain a country code.
     *
     * @param string $phoneNumber The phone number to validate.
     *
     * @return bool True if the phone number is valid, false otherwise.
     */
    public static function isPhoneNumberValid(string $phoneNumber): bool
    {
        // Phone number must start with a '+' followed by up to 12 digits.
        return (bool)preg_match('/^\+\d{1,12}$/', $phoneNumber);
    }
}
