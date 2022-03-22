<?php

declare(strict_types = 1);

namespace SandwaveIo\Freshdesk\Entity;

use DateTimeImmutable;
use JMS\Serializer\Annotation as Serializer;
use SandwaveIo\Freshdesk\Enum\TicketPriority;
use SandwaveIo\Freshdesk\Enum\TicketSource;
use SandwaveIo\Freshdesk\Enum\TicketStatus;

/**
 * @see https://developers.freshdesk.com/api/#tickets
 */
class Ticket
{
    /**
     * @var int|null
     * @Serializer\SerializedName("id")
     * @Serializer\Type("int")
     * @Serializer\Groups({"read"})
     */
    public ?int $id = null;

    /**
     * Ticket attachments. The total size of these attachments cannot exceed 20MB.
     *
     * @var array|null
     * @Serializer\SerializedName("attachments")
     * @Serializer\Type("array")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?array $attachments = null;

    /**
     * Email address added in the 'cc' field of the incoming ticket email.
     *
     * @var array|null
     * @Serializer\SerializedName("cc_emails")
     * @Serializer\Type("array")
     * @Serializer\Groups({"read", "create"})
     */
    public ?array $ccEmails = null;

    /**
     * ID of the company to which this ticket belongs.
     *
     * @var int|null
     * @Serializer\SerializedName("company_id")
     * @Serializer\Type("int")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?int $companyId = null;

    /**
     * Set to true if the ticket has been deleted/trashed. Deleted tickets will not be displayed in any views except the "deleted" filter.
     *
     * @var bool
     * @Serializer\SerializedName("deleted")
     * @Serializer\Type("bool")
     * @Serializer\Groups({"read"})
     */
    public bool $isDeleted = false;

    /**
     * HTML content of the ticket.
     *
     * @var string|null
     * @Serializer\SerializedName("description")
     * @Serializer\Type("string")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?string $description = null;

    /**
     * Content of the ticket in plain text.
     *
     * @var string|null
     * @Serializer\SerializedName("description_text")
     * @Serializer\Type("string")
     * @Serializer\Groups({"read"})
     */
    public ?string $descriptionText = null;

    /**
     * Timestamp that denotes when the ticket is due to be resolved.
     *
     * @var DateTimeImmutable|null
     * @Serializer\SerializedName("due_by")
     * @Serializer\Type("DateTimeImmutable")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?DateTimeImmutable $dueBy = null;

    /**
     * Email address of the requester. If no contact exists with this email address in Freshdesk, it will be added as a new contact.
     *
     * @var string|null
     * @Serializer\SerializedName("email")
     * @Serializer\Type("string")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?string $email = null;

    /**
     * ID of email config which is used for this ticket.
     * (i.e., support@yourcompany.com/sales@yourcompany.com).
     *
     * @var string|null
     * @Serializer\SerializedName("email_config_id")
     * @Serializer\Type("string")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?string $emailConfigId = null;

    /**
     * Facebook ID of the requester. A contact should exist with this facebook_id in Freshdesk.
     *
     * @var string|null
     * @Serializer\SerializedName("facebook_id")
     * @Serializer\Type("string")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?string $facebookId = null;

    /**
     * Timestamp that denotes when the first response is due.
     *
     * @var DateTimeImmutable|null
     * @Serializer\SerializedName("fr_due_by")
     * @Serializer\Type("DateTimeImmutable")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?DateTimeImmutable $firstResponseDueBy = null;

    /**
     * Set to true if the ticket has been escalated as the result of first response time being breached.
     *
     * @var bool
     * @Serializer\SerializedName("fr_escalated")
     * @Serializer\Type("bool")
     * @Serializer\Groups({"read"})
     */
    public bool $firstResponseIsEscalated = false;

    /**
     * Email address(e)s added while forwarding a ticket.
     *
     * @var array|null
     * @Serializer\SerializedName("fwd_emails")
     * @Serializer\Type("array")
     * @Serializer\Groups({"read"})
     */
    public ?array $forwardEmails = null;

    /**
     * Name of the requester.
     *
     * @var int|null
     * @Serializer\SerializedName("group_id")
     * @Serializer\Type("int")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?int $groupId = null;

    /**
     * Set to true if the ticket has been escalated for any reason.
     *
     * @var bool
     * @Serializer\SerializedName("is_escalated")
     * @Serializer\Type("bool")
     * @Serializer\Groups({"read"})
     */
    public bool $isEscalated = false;

    /**
     * Name of the requester.
     *
     * @var string|null
     * @Serializer\SerializedName("name")
     * @Serializer\Type("string")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?string $name = null;

    /**
     * Phone number of the requester. If no contact exists with this phone number in Freshdesk, it will be added as a new contact.
     * If the phone number is set and the email address is not, then the name attribute is mandatory.
     *
     * @var string|null
     * @Serializer\SerializedName("phone")
     * @Serializer\Type("string")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?string $phone = null;

    /**
     * Priority of the ticket.
     *
     * @var TicketPriority|null
     * @Serializer\SerializedName("priority")
     * @Serializer\Type("Enum<'SandwaveIo\Freshdesk\Enum\TicketPriority'>")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?TicketPriority $priority = null;

    /**
     * ID of the product to which the ticket is associated.
     *
     * @var int|null
     * @Serializer\SerializedName("product_id")
     * @Serializer\Type("int")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?int $productId = null;

    /**
     * ID of the product to which the ticket is associated.
     *
     * @var array|null
     * @Serializer\SerializedName("reply_cc_emails")
     * @Serializer\Type("array")
     * @Serializer\Groups({"read"})
     */
    public ?array $replyCcEmails = null;

    /**
     * User ID of the requester. For existing contacts, the requester_id can be passed instead of the requester's email.
     *
     * @var int|null
     * @Serializer\SerializedName("requester_id")
     * @Serializer\Type("int")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?int $requesterId = null;

    /**
     * ID of the agent to whom the ticket has been assigned.
     *
     * @var int|null
     * @Serializer\SerializedName("responder_id")
     * @Serializer\Type("int")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?int $responderId = null;

    /**
     * The channel through which the ticket was created.
     *
     * @var TicketSource|null
     * @Serializer\SerializedName("source")
     * @Serializer\Type("Enum<'SandwaveIo\Freshdesk\Enum\TicketSource'>")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?TicketSource $source = null;

    /**
     * Set to true if the ticket has been marked as spam.
     *
     * @var bool
     * @Serializer\SerializedName("spam")
     * @Serializer\Type("bool")
     * @Serializer\Groups({"read"})
     */
    public bool $isSpam = false;

    /**
     * Status of the ticket.
     *
     * @var TicketStatus|null
     * @Serializer\SerializedName("status")
     * @Serializer\Type("Enum<'SandwaveIo\Freshdesk\Enum\TicketStatus'>")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?TicketStatus $status = null;

    /**
     * Subject of the ticket.
     *
     * @var string|null
     * @Serializer\SerializedName("subject")
     * @Serializer\Type("string")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?string $subject = null;

    /**
     * Tags that have been associated with the ticket.
     *
     * @var array|null
     * @Serializer\SerializedName("tags")
     * @Serializer\Type("array")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?array $tags = null;

    /**
     * Email addresses to which the ticket was originally sent.
     *
     * @var array|null
     * @Serializer\SerializedName("to_emails")
     * @Serializer\Type("array")
     * @Serializer\Groups({"read"})
     */
    public ?array $toEmails = null;

    /**
     * Twitter handle of the requester. If no contact exists with this handle in Freshdesk, it will be added as a new contact.
     *
     * @var string|null
     * @Serializer\SerializedName("twitter_id")
     * @Serializer\Type("string")
     * @Serializer\Groups({"update", "create"})
     */
    public ?string $twitterId = null;

    /**
     * Helps categorize the ticket according to the different kinds of issues your support team deals with.
     *
     * @var string|null
     * @Serializer\SerializedName("type")
     * @Serializer\Type("string")
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?string $type = null;

    /**
     * Ticket updated timestamp.
     *
     * @var DateTimeImmutable|null
     * @Serializer\SerializedName("updated_at")
     * @Serializer\Type("DateTimeImmutable")
     * @Serializer\Groups({"read"})
     */
    public ?DateTimeImmutable $updatedAt = null;

    /**
     * ID of the internal agent which the ticket should be assigned with.
     *
     * @var int|null
     * @Serializer\SerializedName("internal_agent_id")
     * @Serializer\Type("int")
     * @Serializer\Groups({"update", "create"})
     */
    public ?int $internalAgentId = null;

    /**
     * ID of the internal group to which the ticket should be assigned with.
     *
     * @var int|null
     * @Serializer\SerializedName("internal_group_id")
     * @Serializer\Type("int")
     * @Serializer\Groups({"update", "create"})
     */
    public ?int $internalGroupId = null;

    /**
     * Ticket creation timestamp.
     *
     * @var DateTimeImmutable|null
     * @Serializer\SerializedName("created_at")
     * @Serializer\Type("DateTimeImmutable<'Y-m-d H:i:s', '', 'Y-m-d\TH:i:s\Z'>")
     * @Serializer\Groups({"read"})
     */
    public ?DateTimeImmutable $createdAt = null;
}
