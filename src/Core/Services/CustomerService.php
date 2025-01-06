<?php

declare(strict_types=1);

namespace Maginium\Customer\Services;

use Maginium\Customer\Interfaces\Repositories\CustomerRepositoryInterface;
use Maginium\Customer\Interfaces\Services\CustomerServiceInterface;
use Maginium\Framework\Crud\Service;

/**
 * Class CustomerService.
 *
 * This class extends the base `CustomerService` and implements custom functionality for handling customers.
 */
class CustomerService extends Service implements CustomerServiceInterface
{
    /**
     * CustomerService constructor.
     *
     * @param CustomerRepositoryInterface $customerRepository The customer repository interface.
     */
    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        parent::__construct($customerRepository);
    }
}
