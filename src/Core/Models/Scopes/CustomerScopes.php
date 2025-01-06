<?php

declare(strict_types=1);

namespace Maginium\Customer\Models\Scopes;

use Maginium\Customer\Models\Scopes\Customer\GeneralScopes;

/**
 * Trait CustomerScopes.
 *
 * This trait provides common query scopes for the Customer model, enabling
 * flexible querying options through the use of predefined scopes.
 *
 * It includes general, store, and website scopes to allow querying across
 * different contexts like store and website-specific data.
 */
trait CustomerScopes
{
    // Include general query scopes applicable to the Customer model.
    use GeneralScopes;
}
