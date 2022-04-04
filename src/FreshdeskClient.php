<?php

declare(strict_types = 1);

namespace SandwaveIo\Freshdesk;

use SandwaveIo\Freshdesk\Client\ContactClient;
use SandwaveIo\Freshdesk\Client\GroupClient;
use SandwaveIo\Freshdesk\Client\RestClientInterface;
use SandwaveIo\Freshdesk\Client\TicketClient;

final class FreshdeskClient
{
    private TicketClient $ticketClient;

    private ContactClient $contactClient;

    private GroupClient $groupClient;

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

    public function getGroupClient(): GroupClient
    {
        return $this->groupClient;
    }

    private function setClient(RestClientInterface $client): void
    {
        $this->ticketClient = new TicketClient($client);
        $this->contactClient = new ContactClient($client);
        $this->groupClient = new GroupClient($client);
    }
}
