<?php

declare(strict_types = 1);

namespace SandwaveIo\Freshdesk;

use SandwaveIo\Freshdesk\Client\ContactClient;
use SandwaveIo\Freshdesk\Client\RestClientInterface;
use SandwaveIo\Freshdesk\Client\TicketClient;

final class FreshdeskClient
{
    private TicketClient $ticketClient;

    private ContactClient $contactClient;

    public function __construct(RestClientInterface $restClient)
    {
        $this->setClient($restClient);
    }

    public function getTicketClient(): TicketClient
    {
        return $this->ticketClient;
    }

    public function getContactClient(): ContactClient
    {
        return $this->contactClient;
    }

    private function setClient(RestClientInterface $client): void
    {
        $this->ticketClient = new TicketClient($client);
        $this->contactClient = new ContactClient($client);
    }
}
