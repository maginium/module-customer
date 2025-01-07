<?php

declare(strict_types=1);

namespace Maginium\Customer\Setup\Patch\Data;

use Maginium\Framework\Database\Enums\AttributeInputType;
use Maginium\Framework\Database\Facades\CustomerAttribute;
use Maginium\Framework\Database\Schema\AttributeBlueprint;
use Maginium\Framework\Database\Setup\Migration\Attribute\Migration;
use Maginium\Framework\Support\Facades\AttributeSchema;

/**
 * Class CustomerMobileNumberAttribute.
 *
 * Defines and configures a custom attribute for storing the customer's mobile number.
 * Implements an attribute migration to create this attribute in the database and make it
 * available in specified customer-related forms.
 */
class CustomerMobileNumberAttribute extends Migration
{
    /**
     * The code identifier for the attribute.
     */
    public static string $attribute = 'mobile_number';

    /**
     * The sort order for the mobile number attribute.
     * Defines the attribute's position when displayed relative to other attributes.
     */
    public const ATTRIBUTE_SORT_ORDER = 85;

    /**
     * The display position for the mobile number attribute.
     * Used to determine the attribute's placement in forms or grids.
     */
    public const ATTRIBUTE_POSITION = 85;

    /**
     * The label for the mobile number attribute.
     */
    public const ATTRIBUTE_LABEL = 'Mobile Number';

    /**
     * Forms where the mobile number attribute will be used.
     * This array specifies the forms where the attribute will appear.
     *
     * @var array
     */
    public const USED_IN_FORMS = [
        'adminhtml_customer',
        'adminhtml_checkout',
        'customer_address_edit',
        'customer_register_address',
        'adminhtml_customer_address',
    ];

    /**
     * Define the schema and settings for the "mobile_number" attribute.
     *
     * This method implements the abstract method `up` from AttributeMigration, configuring
     * various properties of the mobile number attribute using the `AttributeBlueprint`.
     * This includes the input type, validation rules, visibility settings, and other
     * options required for the attribute.
     *
     * @return void
     */
    public function up(): void
    {
        // Create the attribute with the specified schema
        AttributeSchema::create(static::$attribute, function(AttributeBlueprint $attribute): void {
            $attribute->asVarchar()
                // Set the attribute model type
                ->for(CustomerAttribute::ENTITY_TYPE)

                // Set the input type to text for entering a mobile number
                ->input(AttributeInputType::TEXT)

                // Set a label that will appear in forms
                ->label(self::ATTRIBUTE_LABEL)

                // Set various options for attribute behavior
                ->unique(true) // Ensure the attribute value is unique across customers
                ->system(false) // Set as non-system attribute, allowing user modification
                ->visible(true) // Make the attribute visible in forms
                ->required(false) // Not mandatory; customers can leave it blank
                ->userDefined(true) // Make the attribute definded by user

                // Set options specific to grid views
                ->usedInGrid(true) // Enable use of this attribute in admin grid views
                ->visibleInGrid(true) // Make the attribute visible in grid views
                ->filterableInGrid(true) // Allow filtering by this attribute in grids
                ->searchableInGrid(true) // Enable search by this attribute in grids

                // Define the system and segmentation properties
                ->isSystem(true) // Mark as a system attribute
                ->isUserDefined(true) // Allow users to define the attribute's value
                ->isUsedForCustomerSegment(true) // Enable segmentation based on this attribute

                // Add validation rules to ensure valid mobile number lengths
                ->validateRules(['max_text_length:15', 'min_text_length:10'])

                // Set display properties such as position and sort order in forms
                ->position(self::ATTRIBUTE_POSITION)
                ->sortOrder(self::ATTRIBUTE_SORT_ORDER)

                 // Set attribute forms
                ->usedInForms(self::USED_IN_FORMS);
        });
    }
}
