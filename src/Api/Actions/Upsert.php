<?php

declare(strict_types=1);

namespace Maginium\CustomerApi\Actions;

use Maginium\CustomerApi\Interfaces\UpsertInterface;
use Maginium\CustomerElasticIndexer\Interfaces\Services\CustomerServiceInterface;
use Maginium\Framework\Crud\Actions\Upsert as BaseUpsert;
use Maginium\Framework\Support\Facades\Log;

/**
 * Class UpsertAction.
 *
 * This action upserts (updates or inserts) an model in the service.
 */
class Upsert extends BaseUpsert implements UpsertInterface
{
    /**
     * @var CustomerServiceInterface
     */
    protected $service;

    /**
     * UpsertAction constructor.
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
