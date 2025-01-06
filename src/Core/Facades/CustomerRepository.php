<?php

declare(strict_types=1);

namespace Maginium\Customer\Facades;

use Maginium\Customer\Interfaces\Repositories\CustomerRepositoryInterface;
use Maginium\Framework\Crud\Interfaces\Repositories\RepositoryInterface;
use Maginium\Framework\Support\Facade;

/**
 * Class CustomerRepository.
 *
 * Facade for interacting with the Customer Repository, which retrieves country information.
 *
 * @method static mixed query(string $query, array $parameters = []) Executes a custom query on the city data.
 * @method static AbstractCollection|null where($value, $attribute = 'id') Retrieves a collection of models matching the given criteria.
 * @method static AbstractCollection|null whereIn(string $attribute, array $values) Retrieves a collection of models where the specified attribute is in the given values.
 * @method static AbstractCollection|null whereNotIn(string $attribute, array $values) Retrieves a collection of models where the specified attribute is not in the given values.
 * @method static AbstractCollection|null find(int $id, array $columns = ['*']) Finds an model by its ID.
 * @method static AbstractCollection findOrFail($id, $columns = ['*']) Finds an model by its ID or throws an exception if not found.
 * @method static AbstractCollection findOrNew($id, $columns = ['*']) Finds an model by its ID or returns a new instance if not found.
 * @method static AbstractCollection|null findFirst() Retrieves the first model from the collection.
 * @method static array findWhere(array $query) Finds models matching the specified conditions.
 * @method static array findWhereIn(string $attribute, array $values) Finds models where the specified attribute is in the given values.
 * @method static array findWhereNotIn(string $attribute, array $values) Finds models where the specified attribute is not in the given values.
 * @method static array findByIds(array $ids) Finds models by their IDs.
 * @method static array findMany($ids, $columns = ['*']) Finds multiple models by their IDs.
 * @method static array findAndCount(array $options = []) Finds models and counts the results.
 * @method static array findAndCountBy(array $where) Finds models and counts the results based on specific conditions.
 * @method static array paginate(array $options) Paginates the results based on the provided options.
 * @method static bool|null exist(array $options = []) Checks if any models exist matching the given options.
 * @method static bool|null existsBy(array $where) Checks if models exist based on specific conditions.
 * @method static int count(array $options = []) Counts the number of models matching the given options.
 * @method static int countBy(array $where) Counts the number of models matching specific conditions.
 * @method static float sum(string $columnName, array $where) Calculates the sum of a column for models matching the given conditions.
 * @method static float average(string $columnName, array $where) Calculates the average of a column for models matching the given conditions.
 * @method static float max(string $columnName, array $where) Finds the maximum value of a column for models matching the given conditions.
 * @method static float min(string $columnName, array $where) Finds the minimum value of a column for models matching the given conditions.
 * @method static mixed getId(AbstractCollection $model) Retrieves the ID of the given model.
 * @method static AbstractCollection create() Creates a new instance of the repository.
 *
 * @see RepositoryInterface
 * @see CustomerRepositoryInterface
 */
class CustomerRepository extends Facade
{
    /**
     * Get the accessor for the facade.
     *
     * @return string The accessor for the facade.
     */
    protected static function getAccessor(): string
    {
        return CustomerRepositoryInterface::class;
    }
}
