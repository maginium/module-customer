<?php

declare(strict_types=1);

namespace Maginium\CustomerApi\Actions;

use Maginium\CustomerApi\Interfaces\GetByIdInterface;
use Maginium\CustomerElasticIndexer\Interfaces\Services\CustomerServiceInterface;
use Maginium\Framework\Crud\Actions\GetById as BaseGetById;
use Maginium\Framework\Support\Facades\Log;

/**
 * Class GetByIdAction.
 *
 * This action retrieves an model by its ID.
 */
class GetById extends BaseGetById implements GetByIdInterface
{
    /**
     * @var CustomerServiceInterface
     */
    protected $service;

    /**
     * GetByIdAction constructor.
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
