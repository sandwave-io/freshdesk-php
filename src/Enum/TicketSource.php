<?php

declare(strict_types = 1);

namespace SandwaveIo\Freshdesk\Enum;

enum TicketSource: int
{
    case EMAIL = 1;
    case PORTAL = 2;
    case PHONE = 3;
    case CHAT = 7;
    case FEEDBACK_WIDGET = 9;
    case OUTBOUND_EMAIL = 10;
}
