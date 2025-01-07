<?php

declare(strict_types=1);

namespace Maginium\CustomerElasticIndexer\Models;

use Maginium\Customer\Interfaces\Data\CustomerInterface;
use Maginium\Customer\Models\Attributes\CustomerAttributes;
use Maginium\CustomerElasticIndexer\Models\Scopes\CustomerScopes;
use Maginium\Foundation\Enums\DataType;
use Maginium\Framework\Database\EloquentModel;
use Maginium\Framework\Database\Enums\SearcherEngines;
use Maginium\Framework\Elasticsearch\Eloquent\Model;

/**
 * Customer Model.
 *
 * Represents the Customer model with attributes and relationships mapped
 * to the `customers` table and Elasticsearch for indexing purposes.
 *
 * @mixin EloquentModel
 */
class Customer extends Model implements CustomerInterface
{
    // Trait for handling attributes
    use CustomerAttributes;
    // Trait for handling scopes
    use CustomerScopes;

    /**
     * Connection name for the database.
     *
     * @var string
     */
    protected $connection = SearcherEngines::ELASTIC_SEARCH;

    /**
     * Elasticsearch index name.
     *
     * @var string
     */
    protected $index = self::TABLE_NAME;

    /**
     * Primary key for the Customer model.
     *
     * @var string
     */
    protected $primaryKey = self::ID;

    /**
     * The "type" of the primary key ID.
     *
     * This is usually 'int', but could be 'string' for UUIDs.
     *
     * @var string
     */
    protected $keyType = DataType::INT;

    /**
     * Get the preferred locale of the model.
     *
     * This method should be implemented by any class that needs to provide information
     * about the preferred locale setting of the model. The locale is typically a string
     * such as 'en_US', 'fr_FR', etc. It can return null if no preference is set.
     *
     * @return string|null The preferred locale of the model, or null if not set.
     */
    public function preferredLocale(): ?string
    {
        return $this->getLocale();
    }
}
