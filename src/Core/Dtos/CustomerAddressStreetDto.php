<?php

declare(strict_types=1);

namespace Maginium\Customer\Dtos;

use Maginium\Framework\Dto\Attributes\MapFrom;
use Maginium\Framework\Dto\Attributes\Validation\Text;
use Maginium\Framework\Dto\DataTransferObject;

/**
 * Class CustomerAddressStreetDto.
 *
 * Data Transfer Object (DTO) for encapsulating customer street address details.
 * This DTO holds the primary and secondary street address information of the customer.
 */
class CustomerAddressStreetDto extends DataTransferObject
{
    /**
     * @var string|null The primary street address (e.g., "Chestnut Street 92").
     * This is the main part of the customer's street address.
     * Can be a required field depending on the address format.
     */
    #[Text]
    #[MapFrom('street')]
    public ?string $address1;

    /**
     * @var string|null Secondary street address (optional, may be empty).
     * This is an optional field that can store additional address information such as an apartment or suite number.
     */
    #[Text]
    #[MapFrom('street')]
    public ?string $address2;
}
