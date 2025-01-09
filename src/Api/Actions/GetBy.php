<?php

declare(strict_types=1);

namespace Maginium\CustomerApi\Actions;

use Maginium\CustomerApi\Interfaces\GetByInterface;
use Maginium\CustomerElasticIndexer\Interfaces\Services\CustomerServiceInterface;
use Maginium\Framework\Crud\Actions\GetBy as BaseGetBy;
use Maginium\Framework\Support\Facades\Log;

/**
 * Class GetByAction.
 *
 * This action retrieves an model based on specific criteria.
 */
class GetBy extends BaseGetBy implements GetByInterface
{
    /**
     * @var CustomerServiceInterface
     */
    protected $service;

    /**
     * GetByAction constructor.
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
