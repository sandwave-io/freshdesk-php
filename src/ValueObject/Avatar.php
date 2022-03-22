<?php

declare(strict_types = 1);

namespace SandwaveIo\Freshdesk\ValueObject;

use DateTimeImmutable;
use JMS\Serializer\Annotation as Serializer;

class Avatar
{
    /**
     * @var string|null
     * @Serializer\SerializedName("avatar_url")
     * @Serializer\Type("string")
     * @Serializer\Groups({"read"})
     */
    public ?string $url = null;

    /**
     * @var string|null
     * @Serializer\SerializedName("content_type")
     * @Serializer\Type("string")
     * @Serializer\Groups({"read"})
     */
    public ?string $contentType = null;

    /**
     * @var int|null
     * @Serializer\SerializedName("id")
     * @Serializer\Type("int")
     * @Serializer\Groups({"read"})
     */
    public ?int $id = null;

    /**
     * @var string|null
     * @Serializer\SerializedName("name")
     * @Serializer\Type("string")
     * @Serializer\Groups({"read"})
     */
    public ?string $name = null;

    /**
     * @var int|null
     * @Serializer\SerializedName("size")
     * @Serializer\Type("int")
     * @Serializer\Groups({"read"})
     */
    public ?int $size = null;

    /**
     * @var DateTimeImmutable|null
     * @Serializer\SerializedName("created_at")
     * @Serializer\Type("DateTimeImmutable")
     * @Serializer\Groups({"read"})
     */
    public ?DateTimeImmutable $createdAt = null;

    /**
     * @var DateTimeImmutable|null
     * @Serializer\SerializedName("updated_at")
     * @Serializer\Type("DateTimeImmutable")
     * @Serializer\Groups({"read"})
     */
    public ?DateTimeImmutable $updatedAt = null;
}
