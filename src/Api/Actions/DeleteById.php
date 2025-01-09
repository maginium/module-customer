<?php

declare(strict_types=1);

namespace Maginium\CustomerApi\Actions;

use Maginium\CustomerApi\Interfaces\DeleteInterface;
use Maginium\CustomerElasticIndexer\Interfaces\Services\CustomerServiceInterface;
use Maginium\Framework\Crud\Actions\DeleteById as BaseDeleteById;
use Maginium\Framework\Support\Facades\Log;

/**
 * Class DeleteByIdAction.
 *
 * This action deletes an model by its ID.
 */
class DeleteById extends BaseDeleteById implements DeleteInterface
{
    /**
     * @var CustomerServiceInterface
     */
    protected $service;

    /**
     * DeleteByIdAction constructor.
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
