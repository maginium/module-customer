<?php

declare(strict_types=1);

namespace Maginium\Customer\Enums;

use Maginium\Framework\Enum\Attributes\Description;
use Maginium\Framework\Enum\Attributes\Label;
use Maginium\Framework\Enum\Enum;

/**
 * Enum representing contact preferences.
 * Used to specify the preferred method of contact.
 *
 * @method static self SMS() SMS contact preference.
 * @method static self EMAIL() Email contact preference (Default).
 */
class ContactPreferences extends Enum
{
    /**
     * SMS contact preference.
     * Represents the preference for receiving messages via SMS.
     */
    #[Label('SMS')]
    #[Description('Represents the contact preference for receiving messages via SMS.')]
    public const SMS = 'sms';

    /**
     * Email contact preference.
     * Represents the preference for receiving messages via email.
     * This option is the default.
     */
    #[Label('Email (Default)')]
    #[Description('Represents the contact preference for receiving messages via email. This option is the default for communication.')]
    public const EMAIL = 'email';
}
