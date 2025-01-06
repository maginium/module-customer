<?php

declare(strict_types=1);

namespace Maginium\CustomerAuth\Dtos\Attributes;

use Attribute;
use Maginium\Framework\Actions\Concerns\AsAction;
use Maginium\Framework\Dto\Attributes\Validation\Email;
use Maginium\Framework\Dto\Attributes\Validation\PhoneNumber;
use Maginium\Framework\Dto\Interfaces\ValidatorInterface;
use Maginium\Framework\Dto\Validation\ValidationResult;
use Maginium\Framework\Support\Validator;

/**
 * The `Identifier` attribute validates if a value is a valid email address or phone number.
 * It ensures the value adheres to proper email format or starts with a valid country dial code
 * and has a length between 10 and 15 digits for phone numbers.
 *
 * Example usage:
 *
 * #[Identifier]
 * public string $identifier;
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Identifier implements ValidatorInterface
{
    // Using the AsAction trait to add common action functionality
    use AsAction;

    /**
     * Validates the given value.
     *
     * Checks if the provided value is either a valid email address or a valid phone number.
     *
     * @param mixed $value The value to validate.
     *
     * @return ValidationResult The result of the validation, indicating whether
     *                          the value is valid or invalid.
     */
    public function validate(mixed $value): ValidationResult
    {
        // Ensure the value is a string.
        if (! Validator::isString($value)) {
            return ValidationResult::invalid('The provided value must be a valid string.');
        }

        // Validate as email
        $emailValidation = Email::make()->validate($value);

        if ($emailValidation->isValid) {
            return ValidationResult::valid();
        }

        // Validate as phone number
        $phoneValidation = PhoneNumber::make()->validate($value);

        if ($phoneValidation->isValid) {
            return ValidationResult::valid();
        }

        // If neither validation passes, return an invalid result.
        return ValidationResult::invalid(
            'The value must be a valid email address or a phone number with a valid country dial code.',
        );
    }
}
