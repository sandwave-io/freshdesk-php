<?php

declare(strict_types = 1);

namespace SandwaveIo\Freshdesk\Enum;

use MyCLabs\Enum\Enum;

/**
 * @method static TicketSource EMAIL()
 * @method static TicketSource PORTAL()
 * @method static TicketSource PHONE()
 * @method static TicketSource CHAT()
 * @method static TicketSource FEEDBACK_WIDGET()
 * @method static TicketSource OUTBOUND_EMAIL()
 */
class TicketSource extends Enum
{
    private const EMAIL = 1;
    private const PORTAL = 2;
    private const PHONE = 3;
    private const CHAT = 7;
    private const FEEDBACK_WIDGET = 9;
    private const OUTBOUND_EMAIL = 10;
}
