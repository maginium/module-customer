<?php

declare(strict_types=1);

namespace Maginium\Customer\Interfaces\Repositories;

use Maginium\Customer\Interfaces\Data\CustomerInterface;
use Maginium\Foundation\Exceptions\LocalizedException;
use Maginium\Foundation\Exceptions\NotFoundException;
use Maginium\Framework\Crud\Interfaces\Repositories\RepositoryInterface;

/**
 * Interface CustomerRepositoryInterface.
 *
 * Extends the base `CustomerRepositoryInterface` to allow for custom functionality specific to the project.
 */
interface CustomerRepositoryInterface extends RepositoryInterface
{
    /**
     * Check if a customer exists by identifier (customer name or email) and website ID.
     *
     * @param string $identifier The customer name or email of the customer model to check.
     * @param int|null $websiteId The ID of the website to which the customer belongs. If null, the default website ID is used.
     *
     * @throws NotFoundException If the model with the given identifier does not exist.
     * @throws LocalizedException If an error occurs during the process.
     *
     * @return bool True if the customer exists, false otherwise.
     */
    public function isExists(string $identifier, ?int $websiteId = null): bool;

    /**
     * Authenticate a customer by their identifier and password.
     *
     * @param int|string $identifier The customer email or ID to check or mobile number.
     * @param string $password The password for authentication.
     * @param int|null $websiteId The website ID to which the customer belongs. Defaults to null for the default website.
     *
     * @throws NotFoundException If the model with the given identifier does not exist.
     * @throws LocalizedException If an error occurs during the authentication process.
     *
     * @return CustomerInterface|false The authenticated customer model if successful, or false if authentication fails.
     */
    public function authenticate(int|string $identifier, string $password, ?int $websiteId = null): CustomerInterface|false;

    /**
     * Create a customer account.
     *
     * This method handles the creation of a customer account, performing necessary business operations
     * such as validating the email, creating the account, and performing any additional post-creation tasks.
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer The registration data transfer object containing customer details.
     * @param int|null $websiteId The website ID to associate the customer with, or null for the default.
     *
     * @throws LocalizedException If the email is already associated with an existing customer.
     *
     * @return CustomerInterface|false The created customer object on success, or false on failure.
     */
    public function createAccount(
        \Magento\Customer\Api\Data\CustomerInterface $customer,
        $password = null,
        ?int $websiteId = null,
    ): CustomerInterface|false;

    /**
     * Retrieve a customer model by email.
     *
     * @param string $email The customer email to retrieve.
     * @param int|null $websiteId Optional website ID, defaults to current website if not provided.
     *
     * @return CustomerInterface The customer model.
     */
    public function getByEmail(string $email, ?int $websiteId = null): CustomerInterface;

    /**
     * Retrieve a customer model by mobile number.
     *
     * @param string $mobileNumber The customer mobile number to retrieve.
     * @param int|null $websiteId Optional website ID, defaults to current website if not provided.
     *
     * @return CustomerInterface The customer model.
     */
    public function getByMobileNumber(string $mobileNumber, ?int $websiteId = null): CustomerInterface;
}
