<?php

declare(strict_types=1);

namespace Maginium\Customer\Dtos;

use Maginium\Framework\Dto\Attributes\MapFrom;
use Maginium\Framework\Dto\Attributes\Validation\Text;
use Maginium\Framework\Dto\DataTransferObject;

/**
 * Class CustomerAddressContactDto.
 *
 * Data Transfer Object (DTO) that encapsulates the customer contact information.
 * This class provides a structured way to manage and transfer the customer's contact details, such as company, phone, and email.
 */
class CustomerAddressContactDto extends DataTransferObject
{
    /**
     * @var string|null Company name associated with the customer.
     * This field represents the company where the customer is affiliated, if applicable.
     */
    #[Text]
    public ?string $company = null;

    /**
     * @var string|null Customer's phone number.
     * This field stores the customer's contact phone number.
     */
    #[Text]
    #[MapFrom('telephone')]
    public ?string $phone = null;

    /**
     * @var string|null Customer's email address.
     * This field stores the customer's email address for communication.
     */
    #[Text]
    public ?string $email = null;
}
