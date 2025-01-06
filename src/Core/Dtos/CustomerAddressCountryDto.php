<?php

declare(strict_types=1);

namespace Maginium\Customer\Dtos;

use Maginium\Framework\Dto\Attributes\MapFrom;
use Maginium\Framework\Dto\Attributes\Validation\Text;
use Maginium\Framework\Dto\DataTransferObject;

/**
 * Class CustomerAddressCountryDto.
 *
 * Data Transfer Object (DTO) that encapsulates the country-related address details for a customer.
 * This class provides a structured way to manage and transfer information about the customer's country,
 * including the country name, country code, and province code.
 */
class CustomerAddressCountryDto extends DataTransferObject
{
    /**
     * @var string|null Name of the country (e.g., "United States").
     * This field stores the full name of the country associated with the customer's address.
     */
    #[Text]
    #[MapFrom('country_id')]
    public ?string $name;

    /**
     * @var string|null Country code (e.g., "US").
     * This field stores the ISO 3166-1 alpha-2 country code.
     */
    #[Text]
    #[MapFrom('country_id')]
    public ?string $country_code;

    /**
     * @var string|null Province code (e.g., "KY").
     * This field stores the province or state code associated with the country.
     */
    #[Text]
    #[MapFrom('region')]
    public ?string $province_code;
}
