<?php

declare(strict_types = 1);

namespace SandwaveIo\Freshdesk\Tests\Integration\Client;

use DateTimeImmutable;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use SandwaveIo\Freshdesk\Client\RestClient;
use SandwaveIo\Freshdesk\ValueObject\Dictionary;
use SandwaveIo\Freshdesk\Entity\Ticket;
use SandwaveIo\Freshdesk\Enum\TicketPriority;
use SandwaveIo\Freshdesk\Enum\TicketSource;
use SandwaveIo\Freshdesk\Enum\TicketStatus;
use SandwaveIo\Freshdesk\FreshdeskClient;
use SandwaveIo\Freshdesk\SerializerFactory;

class TicketClientTest extends TestCase
{
    public function testGet(): void
    {
        $jsonResponse = (string) file_get_contents(__DIR__ . '/../../data/get_ticket.json');

        $mockHandler = new MockHandler(
            [new Response(200, [], $jsonResponse)]
        );
        $stack = HandlerStack::create($mockHandler);
        $guzzle = new Client(['handler' => $stack]);

        $serializer = SerializerFactory::create();
        $restClient = new RestClient($guzzle, $serializer);
        $freshdeskClient = new FreshdeskClient($restClient);

        $ticket = $freshdeskClient->getTicketClient()->get(12345);

        $this->assertDeserializedTicket($ticket);
    }

    public function testUpdate(): void
    {
        $jsonResponse = (string) file_get_contents(__DIR__ . '/../../data/get_ticket.json');

        $ticket = new Ticket();
        $ticket->id = 14;
        $ticket->status = TicketStatus::PENDING();
        $ticket->descriptionText = 'shouldnotbeused';
        $ticket->customFields = new Dictionary(['cf_customkey1' => 'value', 'cf_customkey2' => true]);

        $mockHandler = new MockHandler(
            [new Response(200, [], $jsonResponse)]
        );
        $stack = HandlerStack::create($mockHandler);
        $stack->push(function (callable $handler) use ($ticket) {
            return function (RequestInterface $request, $options) use ($handler, $ticket) {
                $body = $request->getBody()->getContents();
                $this->assertJson($body);

                $decoded = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
                self::assertArrayHasKey('status', $decoded);
                self::assertArrayNotHasKey('description_text', $decoded);

                self::assertSame($ticket->status->getValue(), TicketStatus::PENDING()->getValue());

                self::assertSame($ticket->customFields->toArray(), $decoded['custom_fields']);

                return $handler($request, $options);
            };
        });

        $guzzle = new Client(['handler' => $stack]);

        $serializer = SerializerFactory::create();
        $restClient = new RestClient($guzzle, $serializer);
        $freshdeskClient = new FreshdeskClient($restClient);
        $ticket = $freshdeskClient->getTicketClient()->update(14, $ticket);

        $this->assertDeserializedTicket($ticket);
    }

    public function testCreate(): void
    {
        $jsonResponse = (string) file_get_contents(__DIR__ . '/../../data/get_ticket.json');

        $ticket = new Ticket();
        $ticket->id = 14;
        $ticket->status = TicketStatus::PENDING();
        $ticket->descriptionText = 'shouldnotbeused';
        $ticket->customFields = new Dictionary(['cf_customkey1' => 'value', 'cf_customkey2' => true]);

        $mockHandler = new MockHandler(
            [new Response(200, [], $jsonResponse)]
        );
        $stack = HandlerStack::create($mockHandler);
        $stack->push(function (callable $handler) use ($ticket) {
            return function (RequestInterface $request, $options) use ($handler, $ticket) {
                $body = $request->getBody()->getContents();
                $this->assertJson($body);

                $decoded = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
                self::assertArrayHasKey('status', $decoded);
                self::assertArrayNotHasKey('description_text', $decoded);

                self::assertSame($ticket->status->getValue(), TicketStatus::PENDING()->getValue());

                self::assertSame($ticket->customFields->toArray(), $decoded['custom_fields']);

                return $handler($request, $options);
            };
        });

        $guzzle = new Client(['handler' => $stack]);

        $serializer = SerializerFactory::create();
        $restClient = new RestClient($guzzle, $serializer);
        $freshdeskClient = new FreshdeskClient($restClient);
        $ticket = $freshdeskClient->getTicketClient()->create($ticket);

        $this->assertDeserializedTicket($ticket);
    }

    public function testDelete(): void
    {
        $mockHandler = new MockHandler(
            [new Response(204, [], '')]
        );
        $stack = HandlerStack::create($mockHandler);
        $stack->push(function (callable $handler) {
            return function (RequestInterface $request, $options) use ($handler) {
                $this->assertSame('DELETE', $request->getMethod());

                return $handler($request, $options);
            };
        });

        $guzzle = new Client(['handler' => $stack]);

        $serializer = SerializerFactory::create();
        $restClient = new RestClient($guzzle, $serializer);
        $freshdeskClient = new FreshdeskClient($restClient);
        $freshdeskClient->getTicketClient()->delete(123);
    }

    private function assertDeserializedTicket(Ticket $ticket): void
    {
        self::assertSame(14, $ticket->id);
        self::assertSame(1080000528068, $ticket->groupId);
        self::assertSame(50000009103717, $ticket->requesterId);
        self::assertSame(1080031140182, $ticket->responderId);
        self::assertSame('Lorem ipsum do remano', $ticket->subject);

        self::assertSame(['test'], $ticket->tags);
        self::assertSame([], $ticket->ccEmails);
        self::assertSame([], $ticket->forwardEmails);
        self::assertSame([], $ticket->replyCcEmails);

        self::assertTrue($ticket->firstResponseIsEscalated);
        self::assertFalse($ticket->isSpam);

        self::assertNull($ticket->emailConfigId);

        self::assertInstanceOf(DateTimeImmutable::class, $ticket->firstResponseDueBy);
        self::assertInstanceOf(DateTimeImmutable::class, $ticket->dueBy);

        self::assertInstanceOf(TicketPriority::class, $ticket->priority);
        self::assertInstanceOf(TicketStatus::class, $ticket->status);
        self::assertInstanceOf(TicketSource::class, $ticket->source);

        self::assertSame(TicketPriority::LOW()->getValue(), $ticket->priority->getValue());
        self::assertSame(TicketStatus::OPEN()->getValue(), $ticket->status->getValue());
        self::assertSame(TicketSource::PHONE()->getValue(), $ticket->source->getValue());

        self::assertInstanceOf(DateTimeImmutable::class, $ticket->createdAt);
        self::assertInstanceOf(DateTimeImmutable::class, $ticket->updatedAt);

        self::assertSame('2022-02-22 10:03:42', $ticket->createdAt->format('Y-m-d H:i:s'));
        self::assertSame('2022-02-22 18:13:11', $ticket->updatedAt->format('Y-m-d H:i:s'));

        $customFields = [
            'cf_custom1' => false,
            'cf_custom2' => 'Custom value',
        ];
        self::assertInstanceOf(Dictionary::class, $ticket->customFields);
        self::assertSame($customFields, $ticket->customFields->toArray());
    }
}
