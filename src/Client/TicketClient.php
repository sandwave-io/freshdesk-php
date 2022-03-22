<?php

declare(strict_types = 1);

namespace SandwaveIo\Freshdesk\Client;

use SandwaveIo\Freshdesk\Entity\Ticket;
use SandwaveIo\Freshdesk\Exception\FreshdeskException;

final class TicketClient
{
    private const URL_TICKET = 'tickets/';
    private const URL_TICKET_ID = 'tickets/%s';

    private RestClientInterface $client;

    public function __construct(RestClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @throws FreshdeskException
     */
    public function get(int $ticketId): Ticket
    {
        return $this->client->get(sprintf(self::URL_TICKET_ID, $ticketId), Ticket::class);
    }

    /**
     * @throws FreshdeskException
     */
    public function update(int $ticketId, Ticket $ticket): Ticket
    {
        return $this->client->put(sprintf(self::URL_TICKET_ID, $ticketId), $ticket, Ticket::class);
    }

    /**
     * @throws FreshdeskException
     */
    public function create(Ticket $ticket): Ticket
    {
        return $this->client->post(self::URL_TICKET, $ticket, Ticket::class);
    }

    /**
     * @throws FreshdeskException
     */
    public function delete(int $ticketId): void
    {
        $this->client->delete(sprintf(self::URL_TICKET_ID, $ticketId));
    }
}
