<?php

declare(strict_types=1);

namespace Maginium\CustomerApi\Actions;

use Maginium\CustomerApi\Interfaces\GetListInterface;
use Maginium\CustomerElasticIndexer\Interfaces\Services\CustomerServiceInterface;
use Maginium\Framework\Crud\Actions\GetList as BaseGetList;
use Maginium\Framework\Support\Facades\Log;

/**
 * Class GetListAction.
 *
 * This action retrieves a list of models from the service.
 */
class GetList extends BaseGetList implements GetListInterface
{
    /**
     * @var CustomerServiceInterface
     */
    protected $service;

    /**
     * GetListAction constructor.
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
