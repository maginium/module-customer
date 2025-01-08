<?php

declare(strict_types=1);

namespace Maginium\Customer\Dtos;

use Maginium\Framework\Dto\Attributes\MapFrom;
use Maginium\Framework\Dto\Attributes\MapInputName;
use Maginium\Framework\Dto\Attributes\Validation\Integer;
use Maginium\Framework\Dto\Attributes\Validation\Required;
use Maginium\Framework\Dto\Attributes\Validation\Text;
use Maginium\Framework\Dto\DataTransferObject;
use Maginium\Framework\Dto\Mappers\CamelCaseMapper;

/**
 * Class CustomerAddressDto.
 *
 * Data Transfer Object (DTO) that encapsulates the customer address and related details.
 * Provides a structured way to manage and transfer customer address information across systems.
 * Supports extensibility for additional address-related attributes.
 */
#[MapInputName(CamelCaseMapper::class)]
class CustomerAddressDto extends DataTransferObject
{
    /**
     * @var int Unique identifier for the address record.
     * This ID is used to distinguish one address from another.
     */
    #[Required]
    #[Integer]
    #[MapFrom('entity_id')]
    public ?int $id;

    /**
     * @var int Identifier for the associated customer.
     * This ID links the address to a specific customer in the system.
     */
    #[Required]
    #[Integer]
    public ?int $customer_id;

    /**
     * @var string Timestamp when the address was created (ISO 8601 format).
     * This indicates when the address record was initially created in the system.
     */
    #[Required]
    #[Text]
    public ?string $created_at;

    /**
     * @var string|null Timestamp when the address was last updated (ISO 8601 format).
     * This timestamp is optional and is set when the address is modified.
     */
    #[Text]
    public ?string $updated_at = null;

    /**
     * @var CustomerAddressStreetDto Customer's street address details.
     * This object contains the details of the street (e.g., street name, number).
     */
    #[Required]
    public ?CustomerAddressStreetDto $street;

    /**
     * @var CustomerAddressCityDto Customer's city address details.
     * This object contains the city's name, province, and postal code.
     */
    #[Required]
    public ?CustomerAddressCityDto $city;

    /**
     * @var CustomerAddressCountryDto Customer's country address details.
     * This object includes the country's name, country code, and province code.
     */
    #[Required]
    public ?CustomerAddressCountryDto $country;

    /**
     * @var CustomerAddressInfoDto Customer's address information details.
     * This object contains additional information like the name and label associated with the address.
     */
    public ?CustomerAddressInfoDto $info;

    /**
     * @var CustomerAddressContactDto Customer's address contact details.
     * This object contains contact details, such as the phone number and email for the address.
     */
    public ?CustomerAddressContactDto $contact;

    /**
     * @var array|null Additional metadata or custom attributes for the customer address.
     * This field is optional and can store extra information related to the address.
     */
    public ?array $metadata = null;

    /**
     * @var bool Whether this address is the default address for the customer.
     * If true, this address is considered the default address for the customer.
     */
    #[Required]
    public bool $default = false;
}
