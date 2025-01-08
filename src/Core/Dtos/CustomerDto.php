<?php

declare(strict_types=1);

namespace Maginium\Customer\Dtos;

use Maginium\Framework\Dto\Attributes\MapFrom;
use Maginium\Framework\Dto\Attributes\MapInputName;
use Maginium\Framework\Dto\Attributes\Validation\Email;
use Maginium\Framework\Dto\Attributes\Validation\Integer;
use Maginium\Framework\Dto\Attributes\Validation\Required;
use Maginium\Framework\Dto\Attributes\Validation\Text;
use Maginium\Framework\Dto\DataTransferObject;
use Maginium\Framework\Dto\Mappers\CamelCaseMapper;

/**
 * Class CustomerDto.
 *
 * Data Transfer Object (DTO) for Customer model.
 * Encapsulates customer-related data and ensures a structured way to manage and transfer it.
 * Implements ExtensibleDataInterface to support extensibility if needed.
 */
#[MapInputName(CamelCaseMapper::class)]
class CustomerDto extends DataTransferObject
{
    /**
     * @var int Customer's unique identifier.
     */
    #[Required]
    #[Integer]
    #[MapFrom('entity_id')]
    public ?int $id;

    /**
     * @var string Timestamp when the customer was created (ISO 8601 format).
     */
    #[Required]
    #[Text]
    public ?string $created_at;

    /**
     * @var string|null Timestamp when the customer record was last updated (ISO 8601 format).
     */
    #[Text]
    public ?string $updated_at = null;

    /**
     * @var int|null Identifier of the website where the customer was created.
     */
    public ?CustomerCreatedInDto $created_in = null;

    /**
     * @var string Customer's first name.
     */
    #[Required]
    public ?CustomerNameDto $name;

    /**
     * @var string|null Customer's birth date in YYYY-MM-DD format.
     */
    // #[DateTime]
    public ?string $birthdate = null;

    /**
     * @var string|null Customer's gender.
     */
    #[Text]
    public ?string $gender = null;

    /**
     * @var string|null Customer's preferred locale (e.g., en-US, ar-SA).
     */
    #[Text]
    public ?string $locale = null;

    /**
     * @var string|null Group or category the customer belongs to.
     */
    public ?array $group = null;

    /**
     * @var string|null URL or path to the customer's avatar image.
     */
    public ?array $avatar = null;

    /**
     * @var string|null Status of the customer (e.g., active, inactive).
     */
    #[Text]
    public ?string $status = null;

    /**
     * @var bool|null Indicates whether the customer account is locked.
     */
    public ?bool $is_locked = null;

    /**
     * @var string|null Customer's tax or VAT identification number.
     */
    public ?array $taxvat = null;

    /**
     * @var string|null Confirmation status or code for account verification.
     */
    #[Text]
    public ?string $confirmation = null;

    /**
     * @var string|null Timestamp of the customer's last login activity (ISO 8601 format).
     */
    // #[DateTime]
    public ?string $last_login_at = null;

    /**
     * @var string Customer's email address.
     */
    #[Required]
    #[Email]
    public ?string $email;

    /**
     * @var string|null Customer's mobile phone number.
     */
    #[Text]
    public ?string $mobile_number = null;

    /**
     * @var array|null List of addresses associated with the customer.
     */
    public ?array $addresses = null;

    /**
     * @var array|null Additional metadata or custom attributes for the customer.
     */
    public ?array $metadata = null;
}
