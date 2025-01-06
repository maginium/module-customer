<?php

declare(strict_types=1);

namespace Maginium\CustomerAuth\Enums;

use Maginium\Framework\Enum\Attributes\Description;
use Maginium\Framework\Enum\Attributes\Label;
use Maginium\Framework\Enum\Enum;

/**
 * Enum representing different authentication strategies.
 *
 * @method static self EMAIL() Email authentication type.
 * @method static self PHONE() Phone authentication type.
 * @method static self MAGIC_LINK() Magic Link authentication type.
 * @method static self APPLE() Apple authentication type.
 * @method static self GOOGLE() Google authentication type.
 */
class Strategies extends Enum
{
    /**
     * Email authentication type.
     */
    #[Label('Email')]
    #[Description('Authentication using email address and password.')]
    public const EMAIL = 'email';

    /**
     * Phone authentication type.
     */
    #[Label('Phone')]
    #[Description('Authentication using phone number, often with SMS verification.')]
    public const PHONE = 'phone';

    /**
     * Magic Link authentication type.
     */
    #[Label('Magic Link')]
    #[Description("Authentication using a time-sensitive link sent to the user's email.")]
    public const MAGIC_LINK = 'magic_link';

    /**
     * Apple authentication type.
     */
    #[Label('Apple')]
    #[Description('Authentication using Apple ID.')]
    public const APPLE = 'apple';

    /**
     * Google authentication type.
     */
    #[Label('Google')]
    #[Description('Authentication using Google account.')]
    public const GOOGLE = 'google';
}
