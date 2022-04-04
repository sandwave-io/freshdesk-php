<?php

declare(strict_types = 1);

namespace SandwaveIo\Freshdesk\Client;

use SandwaveIo\Freshdesk\Entity\Group;
use SandwaveIo\Freshdesk\Exception\FreshdeskException;

final class GroupClient
{
    private const URL_GROUP = 'groups/';
    private const URL_GROUP_ID = 'groups/%s';

    private RestClientInterface $client;

    public function __construct(RestClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @throws FreshdeskException
     */
    public function get(int $groupId): Group
    {
        return $this->client->get(sprintf(self::URL_GROUP_ID, $groupId), Group::class);
    }

    /**
     * @throws FreshdeskException
     */
    public function update(int $groupId, Group $group): Group
    {
        return $this->client->put(sprintf(self::URL_GROUP_ID, $groupId), $group, Group::class);
    }

    /**
     * @throws FreshdeskException
     */
    public function create(Group $group): Group
    {
        return $this->client->post(self::URL_GROUP, $group, Group::class);
    }

    /**
     * @throws FreshdeskException
     */
    public function delete(int $groupId): void
    {
        $this->client->delete(sprintf(self::URL_GROUP_ID, $groupId));
    }
}
