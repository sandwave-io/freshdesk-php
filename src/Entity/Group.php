<?php

declare(strict_types = 1);

namespace SandwaveIo\Freshdesk\Entity;

use DateTimeImmutable;
use JMS\Serializer\Annotation as Serializer;

/**
 * @see https://developers.freshdesk.com/api/#groups
 */
class Group
{
    /**
     * Array of agent user ids.
     *
     * @see https://support.freshdesk.com/support/solutions/articles/197883-finding-user-id-responder-id-of
     *
     * @var int[]|null
     * @Serializer\SerializedName("agent_ids")
     * @Serializer\Type("array")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?array $agentIds = null;

    /**
     * Describes the type of automatic ticket assignment set for the group.
     * Automatic ticket assignment is only available on certain plans.
     * The accepted values are 0 and 1. The default value is 0.
     *
     * @var int|null
     * @Serializer\SerializedName("auto_ticket_assign")
     * @Serializer\Type("int")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?int $autoTicketAssign = 0;

    /**
     * Unique ID of the business hour associated with the group.
     *
     * @var int|null
     * @Serializer\SerializedName("business_hour_id")
     * @Serializer\Type("int")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?int $businessHourId = null;

    /**
     * Description of the group.
     *
     * @var string|null
     * @Serializer\SerializedName("description")
     * @Serializer\Type("string")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?string $description = null;

    /**
     * The ID of the user to whom an escalation email is sent if a ticket is unassigned.
     * To create/update a group with an escalate_to value of 'none', please set the value of this parameter to 'null'.
     *
     * @var int|null
     * @Serializer\SerializedName("escalate_to")
     * @Serializer\Type("int")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?int $escalateTo = null;

    /**
     * Unique ID of the group.
     *
     * @var int|null
     * @Serializer\SerializedName("id")
     * @Serializer\Type("int")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?int $id;

    /**
     * Name of the group.
     *
     * @var string|null
     * @Serializer\SerializedName("name")
     * @Serializer\Type("string")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?string $name = null;

    /**
     * The time after which an escalation email is sent if a ticket remains unassigned.
     * The accepted values are "30m" for 30 minutes, "1h" for 1 hour, "2h" for 2 hours,
     * "4h" for 4 hours, "8h" for 8 hours, "12h" for 12 hours, "1d" for 1 day,
     * "2d" for 2 days, and "3d" for 3 days.
     *
     * @var string|null
     * @Serializer\SerializedName("unassigned_for")
     * @Serializer\Type("string")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?string $unassignedFor = null;

    /**
     * Group creation timestamp.
     *
     * @var DateTimeImmutable|null
     * @Serializer\SerializedName("created_at")
     * @Serializer\Type("DateTimeImmutable")
     * @Serializer\Groups({"read"})
     */
    public ?DateTimeImmutable $createdAt;

    /**
     * Group updated timestamp.
     *
     * @var DateTimeImmutable|null
     * @Serializer\SerializedName("updated_at")
     * @Serializer\Type("DateTimeImmutable")
     * @Serializer\Groups({"read"})
     */
    public ?DateTimeImmutable $updatedAt;
}
