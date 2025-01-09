<?php

declare(strict_types=1);

namespace Maginium\CustomerApi\Actions;

use Maginium\CustomerApi\Interfaces\CreateInterface;
use Maginium\CustomerElasticIndexer\Interfaces\Services\CustomerServiceInterface;
use Maginium\Framework\Crud\Actions\Save as BaseSave;
use Maginium\Framework\Support\Facades\Log;

/**
 * Class SaveAction.
 *
 * This action saves a new model into the service.
 */
class Save extends BaseSave implements CreateInterface
{
    /**
     * @var CustomerServiceInterface
     */
    protected $service;

    /**
     * SaveAction constructor.
     *
     * @param CustomerServiceInterface $service
     */
    public function __construct(CustomerServiceInterface $service)
    {
        parent::__construct($service);

        // Set the class name for logging purposes
        Log::setClassName(static::class);
    }
}
