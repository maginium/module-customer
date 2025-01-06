<?php

declare(strict_types=1);

namespace Maginium\Customer\Models\Attributes;

use Maginium\Customer\Models\Attributes\Customer\GetterAttributes;
use Maginium\Customer\Models\Attributes\Customer\SetterAttributes;
use Maginium\Framework\Crud\Concerns\Customer\HasAvatar;
use Maginium\Framework\Crud\Concerns\Customer\HasDateOfBirth;
use Maginium\Framework\Crud\Concerns\Customer\HasGender;
use Maginium\Framework\Database\Concerns\HasCustomAttributes;
use Maginium\Framework\Database\Concerns\HasCustomerGroups;
// use Maginium\Framework\Database\Concerns\HasMetadata;
use Maginium\Framework\Database\Concerns\HasStatus;
use Maginium\Framework\Database\Concerns\HasTimestamps;
use Maginium\Store\Models\Concerns\HasStores;
use Maginium\Website\Models\Concerns\HasWebsites;

/**
 * Trait CustomerAttributes.
 *
 * Defines attributes for the Customer model, including getters, setters,
 * and common customer-related functionalities like date of birth, gender, status, etc.
 */
trait CustomerAttributes
{
    // Getters for customer attributes (e.g., name, email)
    use GetterAttributes;
    // Methods for managing customer avatar (e.g., profile picture)
    use HasAvatar;
    // Methods to manage custom attributes
    // use HasCustomAttributes;
    // Methods to manage customer groups
    // use HasCustomerGroups;
    // Methods for handling date of birth
    use HasDateOfBirth;
    // Methods for handling gender
    use HasGender;
    // Methods for managing metadata
    // use HasMetadata;
    // Methods to manage customer status
    use HasStatus;
    // Methods for managing stores associated with the customer
    use HasStores;
    // Methods to handle timestamps (created_at, updated_at)
    use HasTimestamps;
    // Methods to manage websites associated with the customer
    use HasWebsites;
    // Setters for customer attributes (e.g., name, email)
    use SetterAttributes;
}
