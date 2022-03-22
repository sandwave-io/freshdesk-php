<?php

declare(strict_types = 1);

namespace SandwaveIo\Freshdesk\Tests\Integration;

use PHPUnit\Framework\TestCase;
use SandwaveIo\Freshdesk\Entity\Ticket;
use SandwaveIo\Freshdesk\Enum\TicketStatus;
use SandwaveIo\Freshdesk\SerializerFactory;

class EnumSerializerHandlerTest extends TestCase
{
    public function testSerializer(): void
    {
        $object = new Ticket();
        $object->status = TicketStatus::OPEN();

        $serializer = SerializerFactory::create();
        $serialized = $serializer->serialize($object, 'json');

        self::assertJson($serialized);

        $deserialized = json_decode($serialized, false, 512, JSON_THROW_ON_ERROR);
        self::assertSame(TicketStatus::OPEN()->getValue(), $deserialized->status);
    }

    public function testDeserializer(): void
    {
        $object = new Ticket();
        $object->status = TicketStatus::OPEN();

        $serialized = json_encode($object, JSON_THROW_ON_ERROR);

        $serializer = SerializerFactory::create();
        $deserialized = $serializer->deserialize($serialized, Ticket::class, 'json');

        self::assertSame(TicketStatus::OPEN()->getValue(), $deserialized->status->getValue());
    }
}
