<?php

declare(strict_types=1);

namespace Maginium\CustomerAuth\Dtos;

use Maginium\Framework\Dto\Attributes\Validation\Email;
use Maginium\Framework\Dto\Attributes\Validation\Required;
use Maginium\Framework\Dto\Attributes\Validation\Text;
use Maginium\Framework\Dto\DataTransferObject;

/**
 * Class RegisterDto.
 *
 * Data Transfer Object (DTO) for user registration.
 * Used to encapsulate the data of a user's registration and provide a structured way to interact with it.
 * Implements ExtensibleDataInterface to support extensibility if needed.
 */
class RegisterDto extends DataTransferObject
{
    /**
     * @var string
     *
     * The user's email address.
     */
    #[Required]
    #[Email]
    public string $email;

    /**
     * @var string|null
     *
     * The user's phone number.
     */
    #[Text]
    public ?string $phone = null;

    /**
     * @var string
     *
     * The user's password.
     */
    #[Text]
    public ?string $password = null;

    /**
     * @var string
     *
     * The user's last name.
     */
    #[Required]
    #[Text]
    public string $lastname;

    /**
     * @var string
     *
     * The user's first name.
     */
    #[Required]
    #[Text]
    public string $firstname;

    /**
     * @var string|null
     *
     * The group the user belongs to, optional.
     */
    #[Text]
    public ?string $group = null;

    /**
     * @var string|null
     *
     * The user's date of birth, optional.
     */
    #[Text]
    public ?string $dob = null;

    /**
     * @var string|null
     *
     * The user's gender, optional.
     */
    #[Text]
    public ?string $gender = null;

    /**
     * @var string|null
     *
     * The user's prefix, optional.
     */
    #[Text]
    public ?string $prefix = null;

    /**
     * @var string|null
     *
     * The user's suffix, optional.
     */
    #[Text]
    public ?string $suffix = null;

    /**
     * @var string|null
     *
     * The user's tax identification number, optional.
     */
    #[Text]
    public ?string $taxvat = null;
}
