<?php

declare(strict_types=1);

namespace Maginium\Customer\Setup\Patch\Data;

use Maginium\Customer\Models\Config\Source\ColorPreference;
use Maginium\Framework\Database\Enums\AttributeInputType;
use Maginium\Framework\Database\Facades\CustomerAttribute;
use Maginium\Framework\Database\Schema\AttributeBlueprint;
use Maginium\Framework\Database\Setup\Migration\Attribute\Migration;
use Maginium\Framework\Support\Debug\ConsoleOutput;
use Maginium\Framework\Support\Facades\AttributeSchema;

/**
 * Class CustomerColorPreferenceAttribute.
 *
 * Defines and configures a custom attribute for storing the customer's color preference.
 * Implements an attribute migration to create this attribute in the database and make it
 * available in specified customer-related forms.
 */
class CustomerColorPreferenceAttribute extends Migration
{
    /**
     * The code identifier for the attribute.
     */
    public static string $attribute = 'color_preference';

    /**
     * The sort order for the attribute.
     * Defines the attribute's position when displayed relative to other attributes.
     */
    public const ATTRIBUTE_SORT_ORDER = 85;

    /**
     * The display position for the attribute.
     * Used to determine the attribute's placement in forms or grids.
     */
    public const ATTRIBUTE_POSITION = 85;

    /**
     * The label for the attribute.
     */
    public const ATTRIBUTE_LABEL = 'Color Preference';

    /**
     * The default value for the attribute.
     */
    public const ATTRIBUTE_DEFAULT_VALUE = 'system';

    /**
     * Forms where the attribute will be used.
     * This array specifies the forms where the attribute will appear.
     *
     * @var array
     */
    public const ATTRIBUTE_FORMS = [
        'adminhtml_customer',
    ];

    /**
     * Define the schema and settings for the "ATTRIBUTE" attribute.
     *
     * This method implements the abstract method `up` from AttributeMigration, configuring
     * various properties of the attribute using the `AttributeBlueprint`.
     * This includes the input type, validation rules, visibility settings, and other
     * options required for the attribute.
     *
     * @return void
     */
    public function up(): void
    {
        // Create the attribute with the specified schema
        AttributeSchema::create(static::$attribute, function(AttributeBlueprint $attribute): void {
            // Log the start of the migration
            ConsoleOutput::info('🔨 Starting migration for attribute: "' . static::$attribute . '"...', false);

            $attribute->asVarchar()
                // Set the attribute model type
                ->for(CustomerAttribute::ENTITY_TYPE)

                // Set the input type to select for choosing an attribute
                ->input(AttributeInputType::SELECT)

                // Set a label that will appear in forms
                ->label(self::ATTRIBUTE_LABEL)

                // Set various options for attribute behavior
                ->required(false)// Not mandatory; customers can leave it blank
                ->system(false)// Set as non-system attribute, allowing user modification
                ->visible(true)// Make the attribute visible in forms
                ->unique(false)// Not unique across customers

                // Set options specific to grid views
                ->usedInGrid(true)// Enable use of this attribute in admin grid views
                ->visibleInGrid(true)// Make the attribute visible in grid views
                ->filterableInGrid(true)// Allow filtering by this attribute in grids
                ->searchableInGrid(true)// Enable search by this attribute in grids

                // Set the default value
                ->default(self::ATTRIBUTE_DEFAULT_VALUE)

                 // Set display properties such as position and sort order in forms
                ->position(self::ATTRIBUTE_POSITION)
                ->sortOrder(self::ATTRIBUTE_SORT_ORDER)

                // Set attribute forms
                ->usedInForms(self::ATTRIBUTE_FORMS)

                // Set the sourece class
                ->source(ColorPreference::class);
        });
    }
}
