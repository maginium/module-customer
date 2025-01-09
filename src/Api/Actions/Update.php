<?php

declare(strict_types=1);

namespace Maginium\CustomerApi\Actions;

use Maginium\CustomerApi\Interfaces\UpdateInterface;
use Maginium\CustomerElasticIndexer\Interfaces\Services\CustomerServiceInterface;
use Maginium\Framework\Crud\Actions\Update as BaseUpdate;
use Maginium\Framework\Support\Facades\Log;

/**
 * Class UpdateAction.
 *
 * This action updates an existing model in the service.
 */
class Update extends BaseUpdate implements UpdateInterface
{
    /**
     * @var CustomerServiceInterface
     */
    protected $service;

    /**
     * UpdateAction constructor.
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
