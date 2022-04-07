<?php

declare(strict_types = 1);

namespace SandwaveIo\Freshdesk\Entity;

use DateTimeImmutable;
use JMS\Serializer\Annotation as Serializer;

/**
 * @see https://developers.freshdesk.com/api/#companies
 */
class Company
{
    /**
     * Description of the company.
     *
     * @var string|null
     * @Serializer\SerializedName("description")
     * @Serializer\Type("string")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?string $description = null;

    /**
     * Domains of the company. Email addresses of contacts that contain this domain will be associated with that company automatically.
     *
     * @var string[]|null
     * @Serializer\SerializedName("domains")
     * @Serializer\Type("array")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?array $domains = null;

    /**
     * Unique ID of the company.
     *
     * @var int|null
     * @Serializer\SerializedName("id")
     * @Serializer\Type("int")
     * @Serializer\Groups({"read"})
     */
    public ?int $id;

    /**
     * Any specific note about the company.
     *
     * @var string|null
     * @Serializer\SerializedName("note")
     * @Serializer\Type("string")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?string $note = null;

    /**
     * The strength of your relationship with the company.
     *
     * @var string|null
     * @Serializer\SerializedName("health_score")
     * @Serializer\Type("string")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?string $healthScore = null;

    /**
     * Classification based on how much value the company brings to your business.
     *
     * @var string|null
     * @Serializer\SerializedName("account_tier")
     * @Serializer\Type("string")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?string $accountTier = null;

    /**
     * Name of the company.
     *
     * @var string|null
     * @Serializer\SerializedName("name")
     * @Serializer\Type("string")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?string $name;

    /**
     * The industry the company serves in.
     *
     * @var string|null
     * @Serializer\SerializedName("industry")
     * @Serializer\Type("string")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?string $industry = null;

    /**
     * Company creation timestamp.
     *
     * @var DateTimeImmutable|null
     * @Serializer\SerializedName("created_at")
     * @Serializer\Type("DateTimeImmutable")
     * @Serializer\Groups({"read"})
     */
    public ?DateTimeImmutable $createdAt = null;

    /**
     * Company updated timestamp.
     *
     * @var DateTimeImmutable|null
     * @Serializer\SerializedName("updated_at")
     * @Serializer\Type("DateTimeImmutable")
     * @Serializer\Groups({"read"})
     */
    public ?DateTimeImmutable $updatedAt = null;

    /**
     * Date when your contract or relationship with the company is due for renewal.
     *
     * @var DateTimeImmutable|null
     * @Serializer\SerializedName("renewal_date")
     * @Serializer\Type("DateTimeImmutable")
     * @Serializer\Groups({"read"})
     */
    public ?DateTimeImmutable $renewalDate = null;

    /**
     * Key value pairs containing the name and value of the custom field.
     * Only dates in the format YYYY-MM-DD are accepted as input for custom date fields.
     *
     * @var array|null
     * @Serializer\SerializedName("custom_fields")
     * @Serializer\Type("array")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?array $customFields = null;
}
