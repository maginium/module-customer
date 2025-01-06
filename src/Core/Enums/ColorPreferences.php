<?php

declare(strict_types=1);

namespace Maginium\Customer\Enums;

use Maginium\Framework\Enum\Attributes\Description;
use Maginium\Framework\Enum\Attributes\Label;
use Maginium\Framework\Enum\Enum;
use Maginium\Framework\Enum\Interfaces\LocalizedEnum;

/**
 * Enum representing themes for the application.
 * Used to specify the user's theme preference.
 *
 * @method static self DARK() Dark theme.
 * @method static self LIGHT() Light theme.
 * @method static self SYSTEM() System default theme.
 */
class ColorPreferences extends Enum implements LocalizedEnum
{
    /**
     * Dark theme.
     * Often preferred by users who want a darker interface to reduce eye strain.
     */
    #[Label('Dark')]
    #[Description('Represents the dark theme for the application. Often preferred for low-light environments or users who prefer a darker interface.')]
    public const DARK = 'dark';

    /**
     * Light theme.
     * Commonly used for brighter environments or users who prefer a lighter interface.
     */
    #[Label('Light')]
    #[Description('Represents the light theme for the application. Commonly used for brighter environments or users who prefer a lighter interface.')]
    public const LIGHT = 'light';

    /**
     * System default theme.
     * The theme that follows the user's system settings.
     */
    #[Label('System (Default)')]
    #[Description('Represents the system default theme. The theme follows the user\'s operating system settings, which may be dark or light depending on the system configuration.')]
    public const SYSTEM = 'system';
}
