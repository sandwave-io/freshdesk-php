<?php

declare(strict_types = 1);

namespace SandwaveIo\Freshdesk\Enum;

use MyCLabs\Enum\Enum;

/**
 * @method static TicketStatus OPEN()
 * @method static TicketStatus PENDING()
 * @method static TicketStatus RESOLVED()
 * @method static TicketStatus CLOSED()
 */
class TicketStatus extends Enum
{
    private const OPEN = 2;
    private const PENDING = 3;
    private const RESOLVED = 4;
    private const CLOSED = 5;
}
