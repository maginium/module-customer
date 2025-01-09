<?php

declare(strict_types=1);

namespace Maginium\CustomerElasticIndexer\Models;

use Maginium\Customer\Models\Attributes\CustomerAttributes;
use Maginium\CustomerElasticIndexer\Interfaces\Data\CustomerInterface;
use Maginium\CustomerElasticIndexer\Models\Scopes\CustomerScopes;
use Maginium\Foundation\Enums\DataType;
use Maginium\Framework\Database\Enums\SearcherEngines;
use Maginium\Framework\Elasticsearch\Eloquent\Model;

/**
 * Customer Model.
 *
 * Represents the Customer model with attributes and relationships mapped
 * to the `customers` table and Elasticsearch for indexing purposes.
 *
 * @mixin Model
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
    protected $index = CustomerInterface::TABLE_NAME;

    /**
     * Primary key for the Customer model.
     *
     * @var string
     */
    protected $primaryKey = CustomerInterface::ID;

    /**
     * The "type" of the primary key ID.
     *
     * This is usually 'int', but could be 'string' for UUIDs.
     *
     * @var string
     */
    protected $keyType = DataType::INT;

    /**
     * The attributes that should be cast to specific data types.
     *
     * This array defines how the attributes should be automatically cast to the correct types when accessed.
     * For example, 'int' ensures the attribute is cast to an integer.
     *
     * @var array
     */
    protected $casts = [
        CustomerInterface::GENDER => DataType::INT,
        CustomerInterface::GROUP_ID => DataType::INT,
        CustomerInterface::STORE_ID => DataType::INT,
        CustomerInterface::IS_ACTIVE => DataType::INT,
        CustomerInterface::WEBSITE_ID => DataType::INT,
        CustomerInterface::FAILURES_NUM => DataType::INT,
        CustomerInterface::DEFAULT_BILLING => DataType::INT,
        CustomerInterface::DEFAULT_SHIPPING => DataType::INT,
        CustomerInterface::DISABLE_AUTO_GROUP_CHANGE => DataType::INT,
    ];

    /**
     * The attributes that should be treated as dates.
     *
     * These attributes will be automatically cast to Carbon instances.
     *
     * @var array
     */
    protected $dates = [
        CustomerInterface::DOB,
        CustomerInterface::LOCK_EXPIRES,
        CustomerInterface::FIRST_FAILURE,
        CustomerInterface::RP_TOKEN_CREATED_AT,
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * This array defines which attributes should not be included when converting the model to an array or JSON.
     *
     * @var array
     */
    protected $hidden = [
        CustomerInterface::RP_TOKEN,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * This list defines the attributes that can be filled using mass-assignment, allowing attributes
     * to be set directly through the model.
     *
     * @var array
     */
    protected $fillable = [
        CustomerInterface::DOB,
        CustomerInterface::EMAIL,
        CustomerInterface::PREFIX,
        CustomerInterface::SUFFIX,
        CustomerInterface::TAXVAT,
        CustomerInterface::GENDER,
        CustomerInterface::GROUP_ID,
        CustomerInterface::STORE_ID,
        CustomerInterface::LASTNAME,
        CustomerInterface::RP_TOKEN,
        CustomerInterface::IS_ACTIVE,
        CustomerInterface::FIRSTNAME,
        CustomerInterface::CREATED_IN,
        CustomerInterface::MIDDLENAME,
        CustomerInterface::WEBSITE_ID,
        CustomerInterface::INCREMENT_ID,
        CustomerInterface::CONFIRMATION,
        CustomerInterface::FAILURES_NUM,
        CustomerInterface::LOCK_EXPIRES,
        CustomerInterface::PASSWORD_HASH,
        CustomerInterface::FIRST_FAILURE,
        CustomerInterface::DEFAULT_BILLING,
        CustomerInterface::DEFAULT_SHIPPING,
        CustomerInterface::RP_TOKEN_CREATED_AT,
        CustomerInterface::DISABLE_AUTO_GROUP_CHANGE,
    ];

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
