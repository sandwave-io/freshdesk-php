<?php

declare(strict_types = 1);

namespace SandwaveIo\Freshdesk\Enum;

use MyCLabs\Enum\Enum;

/**
 * @method static TicketPriority LOW()
 * @method static TicketPriority MEDIUM()
 * @method static TicketPriority HIGH()
 * @method static TicketPriority URGENT()
 */
class TicketPriority extends Enum
{
    private const LOW = 1;
    private const MEDIUM = 2;
    private const HIGH = 3;
    private const URGENT = 4;
}
