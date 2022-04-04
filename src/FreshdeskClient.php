<?php

declare(strict_types = 1);

namespace SandwaveIo\Freshdesk;

use SandwaveIo\Freshdesk\Client\CompanyClient;
use SandwaveIo\Freshdesk\Client\ContactClient;
use SandwaveIo\Freshdesk\Client\RestClientInterface;
use SandwaveIo\Freshdesk\Client\TicketClient;

final class FreshdeskClient
{
    private TicketClient $ticketClient;
    private ContactClient $contactClient;
    private CompanyClient $companyClient;

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

    public function getCompanyClient(): CompanyClient
    {
        return $this->companyClient;
    }

    private function setClient(RestClientInterface $client): void
    {
        $this->ticketClient = new TicketClient($client);
        $this->contactClient = new ContactClient($client);
        $this->companyClient = new CompanyClient($client);
    }
}
