<?php

declare(strict_types = 1);

namespace SandwaveIo\Freshdesk\Enum;

enum TicketStatus: int
{
    case OPEN = 2;
    case PENDING = 3;
    case RESOLVED = 4;
    case CLOSED = 5;
}
