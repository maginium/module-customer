<?php

declare(strict_types=1);

namespace Maginium\CustomerElasticIndexer\Observers;

use Maginium\Customer\Interfaces\Data\CustomerInterface;
use Maginium\ElasticIndexer\Abstracts\AbstractIndexerObserver;
use Maginium\Framework\Database\Interfaces\Data\ModelInterface;
use Maginium\Framework\Support\Facades\Log;

/**
 * Class CustomerIndexer.
 *
 * This class observes customer-related events such as save, delete, duplicate, and mass actions.
 * It processes the reindexing of categories based on changes made to customer models.
 */
class CustomerIndexer extends AbstractIndexerObserver
{
    /**
     * CustomerIndexer constructor.
     *
     * Initializes the observer with optional dependencies for the model and index repository.
     * Additionally, it sets the class name for logging purposes, allowing for easier tracking of events.
     *
     * @param ModelInterface $model The model for customer models.
     */
    public function __construct(
        CustomerInterface $model,
    ) {
        // Call parent constructor to initialize model and index repository
        parent::__construct($model);

        // Set the logger class name to the current observer's class for better logging context
        Log::setClassName(static::class);
    }

    /**
     * Executes actions after a customer is saved.
     *
     * Logs the event and triggers reindexing for the saved customer model.
     *
     * @param object $model The customer model that was saved.
     *
     * @return void
     */
    protected function saveAfter($model): void
    {
        // Trigger reindexing for the saved customer by its model ID
        $this->reindex([$model->getData($this->model->getKeyName())]);
    }

    /**
     * Executes actions after a customer is deleted.
     *
     * Logs the event and triggers reindexing for the deleted customer model.
     *
     * @param object $model The customer model that was deleted.
     *
     * @return void
     */
    protected function deleteAfter($model): void
    {
        // Trigger reindexing for the deleted customer by its model ID
        $this->reindex([$model->getData($this->model->getKeyName())]);
    }

    /**
     * Executes actions before duplicating a customer.
     *
     * Logs the event and triggers reindexing for the duplicated customer model.
     *
     * @param object $model The customer model to be duplicated.
     *
     * @return void
     */
    protected function duplicateEvent($model): void
    {
        // Trigger reindexing for the duplicated customer by its model ID
        $this->reindex([$model->getData($this->model->getKeyName())]);
    }

    /**
     * Executes actions after mass duplicating categories.
     *
     * Logs the event and triggers reindexing for each duplicated customer model.
     *
     * @param ModelInterface[] $models The array of duplicated customer models.
     *
     * @return void
     */
    protected function massDuplicateAfter(array $models): void
    {
        // Loop through the array of duplicated models and trigger reindexing for each customer
        foreach ($models as $model) {
            // Trigger reindexing for each duplicated customer model
            $this->reindex([$model->getData($this->model->getKeyName())]);
        }
    }

    /**
     * Executes actions after mass deleting categories.
     *
     * Logs the event and triggers reindexing for each deleted customer model.
     *
     * @param ModelInterface[] $models The array of deleted customer models.
     *
     * @return void
     */
    protected function massDeleteAfter(array $models): void
    {
        // Loop through the array of deleted models and trigger reindexing for each customer
        foreach ($models as $model) {
            // Trigger reindexing for each deleted customer model
            $this->reindex([$model->getData($this->model->getKeyName())]);
        }
    }
}
