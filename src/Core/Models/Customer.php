<?php

declare(strict_types=1);

namespace Maginium\Customer\Models;

use Magento\Customer\Model\Customer as BaseCustomer;
use Maginium\Customer\Dtos\CustomerDto;
use Maginium\Customer\Facades\AccountConfirmation;
use Maginium\Customer\Interfaces\Data\CustomerInterface;
use Maginium\Customer\Models\Attributes\CustomerAttributes;
use Maginium\Customer\Models\Scopes\CustomerScopes;
use Maginium\Foundation\Enums\DataType;
use Maginium\Foundation\Enums\Locale;
use Maginium\Framework\Database\Concerns\HasEnhancedModel;
use Maginium\Framework\Database\Interfaces\SearchableInterface;
use Maginium\Framework\Support\Facades\StoreManager;

/**
 * Class Customer.
 *
 * Model for handling countries.
 * This model interacts with the corresponding resource model
 * for database operations and provides methods to access and modify
 * the data fields.
 *
 * @template TKey of array-key
 * @template TValue
 */
class Customer extends BaseCustomer implements CustomerInterface, SearchableInterface
{
    // Trait for handling attributes
    use CustomerAttributes;
    // Trait for handling scopes
    use CustomerScopes;
    // Include additional database handling utilities.
    use HasEnhancedModel;

    /**
     * The table associated with the model.
     *
     * @var string|null
     */
    public static string $table = self::TABLE_NAME;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    public static string $primaryKey = self::ID;

    /**
     * The "type" of the primary key ID.
     *
     * This is usually 'int', but could be 'string' for UUIDs.
     *
     * @var string
     */
    public static string $keyType = DataType::INT;

    /**
     * The status column key associated with the model.
     *
     * @var string
     */
    public static string $statusKey = self::IS_ACTIVE;

    /**
     * Get the customer's preferred locale.
     */
    public function preferredLocale(): string
    {
        // Get the store by identifier
        $store = StoreManager::getStore($this->getStoreId());

        // Return the contact preference value if it exists, otherwise return a default value
        return $store->getlocale() ?? Locale::EN_US;
    }

    /**
     * Retrieve the attributes that should be indexed for the model.
     *
     * This method allows the model to specify which attributes should be included in the search index.
     * It's useful for models with a large number of attributes where you may want to control which fields are searchable.
     *
     * @return array<string> List of attribute names to be indexed.
     */
    public function getSearchableAttributes(): array
    {
        return CustomerDto::asOptionArray();
    }

    /**
     * Get the instance as an array.
     *
     * @param array $keys Optional keys to include in the resulting array.
     *
     * @return array<TKey, TValue>
     */
    public function toDataArray(array $keys = ['*']): array
    {
        // Build an array with key-value pairs representing different properties of the Entity
        $this->dataArray = [
            // Basic information
            'id' => (int)$this->getId(),
            'created_at' => $this->getCreatedAt(),
            'updated_at' => $this->getUpdatedAt(),
            'created_in' => [
                'store_id' => $this->getStoreId(),
                'website_id' => $this->getWebsiteId(),
            ],

            // Name details
            'name' => [
                'first' => $this->getFirstname(),
                'last' => $this->getLastname(),
                'full' => $this->getFullName(),
                'formatted' => $this->getName(),
            ],

            // Communication details
            'email' => $this->getEmail(),
            'phone' => $this->getMobileNumber(),

            // Personal information
            'birthdate' => $this->getDateOfBirth(),
            'gender' => $this->getGender(),
            'locale' => $this->preferredLocale(),
            'avatar' => $this->getAvatar(),
            'status' => $this->getStatus(),
            'is_locked' => $this->isLocked(),
            'last_login_at' => $this->getLastActivity(),
            'group' => $this->getGroup()?->toDataArray(),

            // Verification information
            'verification' => AccountConfirmation::isCustomerVerified($this),

            // Tax and VAT information
            'taxvat' => [
                'class' => $this->getTaxClassId(),
                'tax_exempt' => false,
            ],

            // Marketing consent information
            // TODO: CHECK IF WE NEED TO IMPLEMENT OR NOT
            'email_marketing_consent' => [
                'state' => 'not_subscribed',
                'opt_in_level' => null,
                'consent_updated_at' => '2004-06-13T11:57:11-04:00',
            ],
            'sms_marketing_consent' => [
                'state' => 'not_subscribed',
                'opt_in_level' => 'single_opt_in',
                'consent_updated_at' => '2024-11-05T14:04:06-05:00',
                'consent_collected_from' => 'OTHER',
            ],

            // Addresses
            'addresses' => $this->getAllAddresses(),

            // Metadata
            'metadata' => $this->getMetadata(),
        ];

        // Convert the response array to a collection for easier manipulation and filtering
        $dataArray = collect($this->dataArray);

        // If no specific keys are provided, or if '*' is included, include all response data
        if (empty($keys) || in_array('*', $keys, true)) {
            // Return the full response as an array
            return $dataArray->toArray();
        }

        // If specific keys are provided, filter the response to include only those keys
        // Return only the specified keys from the response
        return $dataArray->only($keys)->toArray();
    }
}
