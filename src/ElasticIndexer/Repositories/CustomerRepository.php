<?php

declare(strict_types=1);

namespace Maginium\CustomerElasticIndexer\Repositories;

use Maginium\CustomerElasticIndexer\Interfaces\Data\CustomerInterface;
use Maginium\Framework\Crud\Interfaces\Repositories\RepositoryInterface;
use Maginium\Framework\Crud\Repository;

/**
 * Class CustomerRepository.
 *
 * This class extends the base `CustomerRepository` and implements custom functionality for handling customers.
 */
class CustomerRepository extends Repository implements RepositoryInterface
{
    /**
     * The repository identifier.
     *
     * @var string
     */
    protected string $repositoryId = 'customer';

    /**
     * CustomerRepository constructor.
     *
     * @param CustomerInterface $model The customer model interface.
     */
    public function __construct(CustomerInterface $model)
    {
        parent::__construct($model);
    }
}
