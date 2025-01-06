<?php

declare(strict_types=1);

namespace Maginium\Customer\Models\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Maginium\Customer\Enums\ContactPreferences;
use Maginium\Foundation\Abstracts\AbstractSource;

/**
 * Class ContactPreference.
 *
 * Implements specific input type options for date of birth (DOB) configuration.
 * Inherits base behavior from AbstractOptionSource.
 */
class ContactPreference extends AbstractSource implements OptionSourceInterface
{
    /**
     * Retrieve options in a "key-value" format.
     *
     * Defines specific configuration options for input types.
     *
     * @return array An associative array of options in "key => value" format.
     *               - 'default': Standard single field input for DOB.
     *               - 'separated-input': Input fields separated for day, month, and year.
     */
    public function toArray(): array
    {
        return ContactPreferences::asSelectArray();
    }
}
