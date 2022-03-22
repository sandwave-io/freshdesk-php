<?php

declare(strict_types = 1);

namespace SandwaveIo\Freshdesk\Entity;

use DateTimeImmutable;
use JMS\Serializer\Annotation as Serializer;
use SandwaveIo\Freshdesk\ValueObject\Avatar;
use SandwaveIo\Freshdesk\ValueObject\Company;

/**
 * @see https://developers.freshdesk.com/api/#contacts
 */
class Contact
{
    /**
     * ID of the contact.
     *
     * @var int|null
     * @Serializer\SerializedName("id")
     * @Serializer\Type("int")
     * @Serializer\Groups({"read"})
     */
    public ?int $id = null;

    /**
     * Set to true if the contact has been verified.
     *
     * @var bool
     * @Serializer\SerializedName("active")
     * @Serializer\Type("bool")
     * @Serializer\Groups({"read"})
     */
    public bool $isActive = false;

    /**
     * Address of the contact.
     *
     * @var string|null
     * @Serializer\SerializedName("address")
     * @Serializer\Type("string")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?string $address = null;

    /**
     * ID of the primary company to which this contact belongs.
     *
     * @var int|null
     * @Serializer\SerializedName("company_id")
     * @Serializer\Type("int")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?int $companyId = null;

    /**
     * Set to true if the contact can see all tickets that are associated with the company to which he belong.
     *
     * @var bool
     * @Serializer\SerializedName("view_all_tickets")
     * @Serializer\Type("bool")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public bool $canViewAllTickets = false;

    /**
     * Set to true if the contact has been deleted. Note that this attribute will only be present for deleted contacts.
     *
     * @var bool
     * @Serializer\SerializedName("deleted")
     * @Serializer\Type("bool")
     * @Serializer\Groups({"read"})
     */
    public bool $isDeleted = false;

    /**
     * A short description of the contact.
     *
     * @var string|null
     * @Serializer\SerializedName("description")
     * @Serializer\Type("string")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?string $description = null;

    /**
     * Primary email address of the contact. If you want to associate additional email(s) with this contact, use the other_emails attribute.
     *
     * @var string|null
     * @Serializer\SerializedName("email")
     * @Serializer\Type("string")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?string $email = null;

    /**
     * Job title of the contact.
     *
     * @var string|null
     * @Serializer\SerializedName("job_title")
     * @Serializer\Type("string")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?string $jobTitle = null;

    /**
     * Language of the contact. Default language is "en". This attribute can only be updated if the Multiple Language feature is enabled (Garden plan and above).
     *
     * @var string|null
     * @Serializer\SerializedName("language")
     * @Serializer\Type("string")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?string $language = null;

    /**
     * Mobile number of the contact.
     *
     * @var string|null
     * @Serializer\SerializedName("mobile")
     * @Serializer\Type("string")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?string $mobile = null;

    /**
     * Name of the contact.
     *
     * @var string|null
     * @Serializer\SerializedName("name")
     * @Serializer\Type("string")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?string $name = null;

    /**
     * Additional emails associated with the contact.
     *
     * @var array|null
     * @Serializer\SerializedName("other_emails")
     * @Serializer\Type("array")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?array $otherEmails = null;

    /**
     * Telephone number of the contact.
     *
     * @var string|null
     * @Serializer\SerializedName("phone")
     * @Serializer\Type("string")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?string $phone = null;

    /**
     * Tags associated with this contact.
     *
     * @var array|null
     * @Serializer\SerializedName("tags")
     * @Serializer\Type("array")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?array $tags = null;

    /**
     * Time zone in which the contact resides.
     *
     * @var string|null
     * @Serializer\SerializedName("time_zone")
     * @Serializer\Type("string")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?string $timeZone = null;

    /**
     * Twitter handle of the contact.
     *
     * @var string|null
     * @Serializer\SerializedName("twitter_id")
     * @Serializer\Type("string")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?string $twitterId = null;

    /**
     * External ID of the contact.
     *
     * @var string|null
     * @Serializer\SerializedName("unique_external_id")
     * @Serializer\Type("string")
     * @Serializer\Groups({"update", "create"})
     */
    public ?string $uniqueExternalId = null;

    /**
     * Additional companies associated with the contact.
     *
     * @var Company[]null
     * @Serializer\SerializedName("other_companies")
     * @Serializer\Type("array<SandwaveIo\Freshdesk\ValueObject\Company>")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?array $otherCompanies = null;

    /**
     * Contact creation timestamp.
     *
     * @var DateTimeImmutable|null
     * @Serializer\SerializedName("created_at")
     * @Serializer\Type("DateTimeImmutable")
     * @Serializer\Groups({"read"})
     */
    public ?DateTimeImmutable $createdAt = null;

    /**
     * Contact updated timestamp.
     *
     * @var DateTimeImmutable|null
     * @Serializer\SerializedName("updated_at")
     * @Serializer\Type("DateTimeImmutable")
     * @Serializer\Groups({"read"})
     */
    public ?DateTimeImmutable $updatedAt = null;

    /**
     * Avatar image of the contact The maximum file size is 5MB and the supported file types are .jpg, .jpeg, .jpe, and .png.
     *
     * @var Avatar|null
     * @Serializer\SerializedName("avatar")
     * @Serializer\Type("SandwaveIo\Freshdesk\ValueObject\Avatar")
     * @Serializer\Groups({"read"})
     */
    public ?Avatar $avatar = null;

    /**
     * Key value pairs containing the name and value of the custom field.
     * Only dates in the format YYYY-MM-DD are accepted as input for custom date fields.
     *
     * @var array|null
     * @Serializer\SerializedName("custom_fields")
     * @Serializer\Type("array")
     * @Serializer\Groups({"read"})
     */
    public ?array $customFields = null;
}
