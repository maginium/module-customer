<?php

declare(strict_types=1);

namespace Maginium\Customer\Dtos;

use Maginium\Framework\Dto\Attributes\MapFrom;
use Maginium\Framework\Dto\Attributes\Validation\Text;
use Maginium\Framework\Dto\DataTransferObject;

/**
 * Class CustomerAddressCityDto.
 *
 * Data Transfer Object (DTO) that encapsulates the customer city address details.
 * Provides a structured way to manage and transfer the city-related address data across systems.
 */
class CustomerAddressCityDto extends DataTransferObject
{
    /**
     * @var string|null Name of the city (e.g., "Louisville").
     * This field represents the name of the city in the address.
     */
    #[Text]
    #[MapFrom('city')]
    public ?string $name = null;

    /**
     * @var string|null Province or state within the city (e.g., "Kentucky").
     * This field represents the province, state, or region within the city.
     */
    #[Text]
    public ?string $province = null;

    /**
     * @var string|null Postal code (e.g., "40202").
     * This field stores the postal or ZIP code associated with the city.
     */
    #[Text]
    #[MapFrom('postcode')]
    public ?string $postal_code = null;
}
