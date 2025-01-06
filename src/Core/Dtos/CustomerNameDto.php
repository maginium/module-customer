<?php

declare(strict_types=1);

namespace Maginium\Customer\Dtos;

use Maginium\Customer\Facades\Customer;
use Maginium\Framework\Dto\Attributes\MapFrom;
use Maginium\Framework\Dto\Attributes\MapInputName;
use Maginium\Framework\Dto\Attributes\Validation\Required;
use Maginium\Framework\Dto\Attributes\Validation\Text;
use Maginium\Framework\Dto\DataTransferObject;
use Maginium\Framework\Dto\Mappers\CamelCaseMapper;

/**
 * Class CustomerNameDto.
 *
 * Data Transfer Object (DTO) for Customer model.
 * Encapsulates customer-related data and ensures a structured way to manage and transfer it.
 * Implements ExtensibleDataInterface to support extensibility if needed.
 */
#[MapInputName(CamelCaseMapper::class)]
class CustomerNameDto extends DataTransferObject
{
    /**
     * @var string Customer's first name.
     */
    #[Required]
    #[Text]
    #[MapFrom('firstname')]
    public ?string $first_name;

    /**
     * @var string Customer's last name.
     */
    #[Required]
    #[Text]
    #[MapFrom('lastname')]
    public ?string $last_name;

    /**
     * @var string|null Customer's full name, typically a concatenation of first and last names.
     */
    public ?string $full_name = null;

    /**
     * @var string|null Customer's formatted name, optimized for display.
     */
    #[Text]
    public ?string $formatted_name = null;
}
