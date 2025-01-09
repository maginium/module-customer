<?php

declare(strict_types=1);

namespace Maginium\Customer\Setup\Patch\Schema;

use Magento\Framework\Config\File\ConfigFilePool;
use Maginium\Framework\Database\Interfaces\RevertablePatchInterface;
use Maginium\Framework\Database\Setup\Migration\Migration;
use Maginium\Framework\Support\Arr;

/**
 * Class DisableModules.
 *
 * This class disables specific Magento modules by modifying the application
 * configuration file. It extends the Migration class to support schema-related
 * changes and implements RevertablePatchInterface, allowing the changes to be
 * rolled back if needed.
 */
class DisableModules extends Migration implements RevertablePatchInterface
{
    /**
     * An array of module names to disable, with each module set to a value of `0`.
     *
     * This array contains the modules targeted for disabling, where the module
     * name is the key, and the integer `0` indicates that the module should
     * be disabled within the configuration. This list is referenced in both
     * the `up` and `down` methods to manage the enable/disable status.
     *
     * @var array<string, int> Associative array with module names as keys and `0` as values.
     */
    protected array $modules = [
        'Swissup_Ignition' => 0, // Disable Magento_CustomerSegment
        'Magento_CustomerSegment' => 0, // Disable Magento_CustomerSegment
        'Magento_CustomerCustomAttributes' => 0, // Disable Magento_CustomerCustomAttributes
        'Magento_CustomAttributeManagement' => 0, // Disable Magento_CustomAttributeManagement
    ];

    /**
     * Executes the process of disabling specified modules by updating the configuration.
     *
     * The `up` method is automatically triggered during the migration process, updating
     * the application's configuration to disable each module listed in `$modules`.
     * This is achieved by calling the `save` method on the `AdminConfig` service,
     * which directly modifies the relevant configuration file.
     *
     * @return void
     */
    public function up(): void
    {
        // Retrieve the AdminConfig service to handle configuration updates.
        // This service allows saving the configuration settings for module states.
        $config = $this->context->getAdminConfig();

        // Update the application's configuration to disable the specified modules.
        // The configuration path is set to `ConfigFilePool::APP_CONFIG` to target
        // the main application configuration, specifically within the `modules` section.
        // The modules in `$modules` are set to `0` to indicate they should be disabled.
        $config->save([
            ConfigFilePool::APP_CONFIG => [
                'modules' => $this->modules, // Disable each module by setting its status to 0
            ],
        ]);
    }

    /**
     * Reverts the module status changes applied in the `up` method, enabling the modules again.
     *
     * The `down` method reverses the effects of `up`, restoring each module's status to `1`
     * (enabled) in the configuration. This is useful for undoing the schema changes if the
     * patch needs to be rolled back.
     *
     * @return void
     */
    public function down(): void
    {
        // Retrieve the AdminConfig service to restore the module configuration settings.
        // This enables the previously disabled modules by updating their status.
        $config = $this->context->getAdminConfig();

        // Re-enable the modules by setting each to `1` in the configuration.
        // The Arr::fill_keys function generates an array with each module name in
        // `$modules` as a key and `1` as the value, effectively setting each to enabled.
        $config->save([
            ConfigFilePool::APP_CONFIG => [
                'modules' => Arr::fillKeys(Arr::keys($this->modules), 1), // Enable each module
            ],
        ]);
    }
}
