<?php

declare(strict_types=1);

use Maginium\Framework\Component\Module;

/**
 * Registers multiple modules based on a list of namespaces and their respective paths.
 *
 * This script registers each module by iterating over an associative array of module namespaces
 * and their corresponding directory paths. The `Module::register` method is called
 * for each module to register it within the application.
 *
 * @param array $extensions An associative array where each key is a fully-qualified module namespace
 *                           (e.g., 'Maginium_Framework'), and the value is the absolute file path
 *                           to the module's directory (e.g., __DIR__).
 */
$extensions = [
    // Core customer modules
    'Maginium_Customer' => __DIR__ . '/Core',
    'Maginium_CustomerApi' => __DIR__ . '/Api',
    'Maginium_CustomerAuth' => __DIR__ . '/Auth',
    'Maginium_CustomerElasticIndexer' => __DIR__ . '/ElasticIndexer',

    // Customer group modules
    'Maginium_CustomerGroup' => __DIR__ . '/Extensions/Group',
    'Maginium_CustomerGroupType' => __DIR__ . '/Extensions/GroupType',

    // Rest of the extensions
    'Maginium_CustomerStats' => __DIR__ . '/Extensions/Stats',
    'Maginium_CustomerDOBInput' => __DIR__ . '/Extensions/DOB',
    'Maginium_CustomerRevenue' => __DIR__ . '/Extensions/Revenue',
    'Maginium_CustomerProfileTabs' => __DIR__ . '/Extensions/ProfileTabs',
    'Maginium_CustomerRelatedOrders' => __DIR__ . '/Extensions/RelatedOrders',
    'Maginium_CustomerPaymentPreference' => __DIR__ . '/Extensions/PaymentPreference',
];

// Register each module using the provided extensions list.
Module::registerModules($extensions);
