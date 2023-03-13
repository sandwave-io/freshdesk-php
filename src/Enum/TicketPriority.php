<?php

declare(strict_types = 1);

namespace SandwaveIo\Freshdesk\Enum;

enum TicketPriority: int
{
    case LOW = 1;
    case MEDIUM = 2;
    case HIGH = 3;
    case URGENT = 4;
}
