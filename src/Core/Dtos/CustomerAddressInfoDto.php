<?php

declare(strict_types=1);

namespace Maginium\Customer\Dtos;

use Maginium\Framework\Dto\Attributes\MapFrom;
use Maginium\Framework\Dto\Attributes\Validation\Text;
use Maginium\Framework\Dto\DataTransferObject;

/**
 * Class CustomerAddressInfoDto.
 *
 * Data Transfer Object (DTO) that encapsulates additional information about the customer's address.
 * This class holds details such as the name and label associated with the customer's address information.
 */
class CustomerAddressInfoDto extends DataTransferObject
{
    /**
     * @var string|null The name associated with the address information.
     * This field stores a name or identifier that is related to the specific address details.
     */
    #[Text]
    #[MapFrom('address_name')]
    public ?string $name;

    /**
     * @var string|null The label associated with the address information.
     * This field stores a descriptive label for the address, which can help in categorizing or identifying the address.
     */
    #[Text]
    #[MapFrom('address_label')]
    public ?string $label;
}
