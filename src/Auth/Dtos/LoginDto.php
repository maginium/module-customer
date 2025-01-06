<?php

declare(strict_types=1);

namespace Maginium\CustomerAuth\Dtos;

use Maginium\CustomerAuth\Dtos\Attributes\Identifier;
use Maginium\CustomerAuth\Enums\Strategies;
use Maginium\Framework\Dto\Attributes\Validation\Boolean;
use Maginium\Framework\Dto\Attributes\Validation\Enum;
use Maginium\Framework\Dto\Attributes\Validation\Required;
use Maginium\Framework\Dto\Attributes\Validation\Text;
use Maginium\Framework\Dto\DataTransferObject;

/**
 * Class LoginDto.
 *
 * Data Transfer Object (DTO) for user login.
 * Encapsulates login data and provides a structured, validated representation for different login strategies.
 */
class LoginDto extends DataTransferObject
{
    /**
     *  The login strategy (e.g., email_password, phone_password, google, apple, magic_link).
     * Required field to determine the login type.
     *
     * @var string
     */
    #[Required]
    #[Enum(Strategies::class)]
    public string $strategy = Strategies::EMAIL;

    /**
     *  The email address (required for email-based login).
     * Must be a valid email format when provided.
     *
     * @var string|null
     */
    #[Identifier]
    public ?string $identifier = null;

    /**
     *  The password (required for password-based login).
     *
     * @var string|null
     */
    #[Text]
    public ?string $password = null;

    /**
     *  The OAuth token (required for social login, e.g., Google or Apple).
     *
     * @var string|null
     */
    #[Text]
    public ?string $access_token = null;

    /**
     *  The magic link token (required for magic link login).
     *
     * @var string|null
     */
    #[Text]
    public ?string $magic_link_token = null;

    /**
     *  Whether to remember the user for future sessions.
     * Defaults to false.
     *
     * @var bool
     */
    #[Boolean]
    public ?bool $remember_me = false;
}
