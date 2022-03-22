<?php

declare(strict_types = 1);

namespace SandwaveIo\Freshdesk\Client;

use SandwaveIo\Freshdesk\Entity\Contact;
use SandwaveIo\Freshdesk\Exception\FreshdeskException;

final class ContactClient
{
    private const URL_CONTACT = 'contacts/';
    private const URL_CONTACT_ID = 'contacts/%s';

    private RestClientInterface $client;

    public function __construct(RestClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @throws FreshdeskException
     */
    public function get(int $contactId): Contact
    {
        return $this->client->get(sprintf(self::URL_CONTACT_ID, $contactId), Contact::class);
    }

    /**
     * @throws FreshdeskException
     */
    public function update(int $contactId, Contact $ticket): Contact
    {
        return $this->client->put(sprintf(self::URL_CONTACT_ID, $contactId), $ticket, Contact::class);
    }

    /**
     * @throws FreshdeskException
     */
    public function create(Contact $ticket): Contact
    {
        return $this->client->post(self::URL_CONTACT, $ticket, Contact::class);
    }

    /**
     * @throws FreshdeskException
     */
    public function delete(int $contactId): void
    {
        $this->client->delete(sprintf(self::URL_CONTACT_ID, $contactId));
    }
}
