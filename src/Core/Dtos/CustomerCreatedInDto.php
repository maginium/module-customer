<?php

declare(strict_types=1);

namespace Maginium\Customer\Dtos;

use Maginium\Customer\Facades\Customer;
use Maginium\Framework\Dto\Attributes\MapFrom;
use Maginium\Framework\Dto\Attributes\MapInputName;
use Maginium\Framework\Dto\Attributes\Validation\Integer;
use Maginium\Framework\Dto\Attributes\Validation\Required;
use Maginium\Framework\Dto\DataTransferObject;
use Maginium\Framework\Dto\Mappers\CamelCaseMapper;

/**
 * Class CustomerCreatedInDto.
 *
 * Data Transfer Object (DTO) for encapsulating customer creation data.
 * Ensures a structured way to manage and transfer customer-related data.
 * Implements ExtensibleDataInterface for future extensibility.
 */
#[MapInputName(CamelCaseMapper::class)]
class CustomerCreatedInDto extends DataTransferObject
{
    /**
     * @var int|null Customer's unique identifier for the website.
     */
    #[Required]
    #[Integer]
    #[MapFrom('website_id')]
    public ?int $websiteId;

    /**
     * @var int|null Customer's unique identifier for the store.
     */
    #[Required]
    #[Integer]
    #[MapFrom('store_id')]
    public ?int $storeId;
}
