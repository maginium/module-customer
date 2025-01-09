<?php

declare(strict_types=1);

namespace Maginium\CustomerApi\Actions;

use Maginium\CustomerApi\Interfaces\SearchInterface;
use Maginium\CustomerElasticIndexer\Interfaces\Services\CustomerServiceInterface;
use Maginium\Framework\Crud\Actions\Search as BaseSearch;
use Maginium\Framework\Support\Facades\Log;

/**
 * Class SearchAction.
 *
 * This action searches for models in the service based on search criteria.
 */
class Search extends BaseSearch implements SearchInterface
{
    /**
     * @var CustomerServiceInterface
     */
    protected $service;

    /**
     * SearchAction constructor.
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
