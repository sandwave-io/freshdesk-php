<?php

declare(strict_types = 1);

namespace SandwaveIo\Freshdesk\ValueObject;

use JMS\Serializer\Annotation as Serializer;

class Company
{
    /**
     * @var int|null
     *
     * @Serializer\SerializedName("company_id")
     *
     * @Serializer\Type("int")
     *
     * @Serializer\Groups({"read", "update", "create"})
     */
    public ?int $companyId = null;

    /**
     * Set to true if the contact can see all tickets for this company.
     *
     * @var bool
     *
     * @Serializer\SerializedName("view_all_tickets")
     *
     * @Serializer\Type("bool")
     *
     * @Serializer\Groups({"read", "update", "create"})
     */
    public bool $canViewAllTickets = false;
}
