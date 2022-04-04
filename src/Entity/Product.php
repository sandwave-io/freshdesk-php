<?php

declare(strict_types = 1);

namespace SandwaveIo\Freshdesk\Entity;

use DateTimeImmutable;
use JMS\Serializer\Annotation as Serializer;

/**
 * @see https://developers.freshdesk.com/api/#products
 */
class Product
{
    /**
     * Description of the product.
     *
     * @var string|null
     * @Serializer\SerializedName("description")
     * @Serializer\Type("string")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?string $description = null;

    /**
     * Unique ID of the product.
     *
     * @var int|null
     * @Serializer\SerializedName("id")
     * @Serializer\Type("int")
     * @Serializer\Groups({"read"})
     */
    public ?int $id;

    /**
     * Name of the product.
     *
     * @var string|null
     * @Serializer\SerializedName("name")
     * @Serializer\Type("string")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?string $name = null;

    /**
     * Product creation timestamp.
     *
     * @var DateTimeImmutable|null
     * @Serializer\SerializedName("created_at")
     * @Serializer\Type("DateTimeImmutable")
     * @Serializer\Groups({"read"})
     */
    public ?DateTimeImmutable $createdAt = null;

    /**
     * Product updated timestamp.
     *
     * @var DateTimeImmutable|null
     * @Serializer\SerializedName("updated_at")
     * @Serializer\Type("DateTimeImmutable")
     * @Serializer\Groups({"read"})
     */
    public ?DateTimeImmutable $updatedAt = null;
}
