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
use SandwaveIo\Freshdesk\Entity\Contact;
use SandwaveIo\Freshdesk\FreshdeskClient;
use SandwaveIo\Freshdesk\SerializerFactory;
use SandwaveIo\Freshdesk\ValueObject\Avatar;
use SandwaveIo\Freshdesk\ValueObject\Company;

class ContactClientTest extends TestCase
{
    public function testGet(): void
    {
        $jsonResponse = (string) file_get_contents(__DIR__ . '/../../data/get_contact.json');

        $mockHandler = new MockHandler(
            [new Response(200, [], $jsonResponse)]
        );
        $stack = HandlerStack::create($mockHandler);
        $guzzle = new Client(['handler' => $stack]);

        $serializer = SerializerFactory::create();
        $restClient = new RestClient($guzzle, $serializer);
        $freshdeskClient = new FreshdeskClient($restClient);

        $contact = $freshdeskClient->getContactClient()->get(12345);

        $this->assertDeserializedContact($contact);
    }

    public function testUpdate(): void
    {
        $jsonResponse = (string) file_get_contents(__DIR__ . '/../../data/get_contact.json');

        $contact = new Contact();
        $contact->id = 1234;
        $contact->name = 'johndoe';
        $contact->isActive = true;

        $mockHandler = new MockHandler(
            [new Response(200, [], $jsonResponse)]
        );
        $stack = HandlerStack::create($mockHandler);
        $stack->push(function (callable $handler) {
            return function (RequestInterface $request, $options) use ($handler) {
                $body = $request->getBody()->getContents();
                $this->assertJson($body);

                $decoded = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
                self::assertArrayHasKey('name', $decoded);
                self::assertArrayNotHasKey('id', $decoded);
                self::assertArrayNotHasKey('active', $decoded);

                return $handler($request, $options);
            };
        });

        $guzzle = new Client(['handler' => $stack]);

        $serializer = SerializerFactory::create();
        $restClient = new RestClient($guzzle, $serializer);
        $freshdeskClient = new FreshdeskClient($restClient);
        $return = $freshdeskClient->getContactClient()->update(14, $contact);

        $this->assertDeserializedContact($return);
    }

    public function testCreate(): void
    {
        $jsonResponse = (string) file_get_contents(__DIR__ . '/../../data/get_contact.json');

        $contact = new Contact();
        $contact->id = 1234;
        $contact->name = 'johndoe';
        $contact->isActive = true;

        $mockHandler = new MockHandler(
            [new Response(200, [], $jsonResponse)]
        );
        $stack = HandlerStack::create($mockHandler);
        $stack->push(function (callable $handler) {
            return function (RequestInterface $request, $options) use ($handler) {
                $body = $request->getBody()->getContents();
                $this->assertJson($body);

                $decoded = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
                self::assertArrayHasKey('name', $decoded);
                self::assertArrayNotHasKey('active', $decoded);

                return $handler($request, $options);
            };
        });

        $guzzle = new Client(['handler' => $stack]);

        $serializer = SerializerFactory::create();
        $restClient = new RestClient($guzzle, $serializer);
        $freshdeskClient = new FreshdeskClient($restClient);
        $return = $freshdeskClient->getContactClient()->create($contact);

        $this->assertDeserializedContact($return);
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
        $freshdeskClient->getContactClient()->delete(123);
    }

    private function assertDeserializedContact(Contact $contact): void
    {
        self::assertSame(434, $contact->id);
        self::assertSame([], $contact->tags);
        self::assertSame('greenlantern@freshdesk.com', $contact->email);

        self::assertInstanceOf(DateTimeImmutable::class, $contact->createdAt);
        self::assertSame('2015-08-28 10:27:58', $contact->createdAt->format('Y-m-d H:i:s'));

        self::assertIsArray($contact->otherCompanies);
        self::assertContainsOnlyInstancesOf(Company::class, $contact->otherCompanies);
        self::assertSame(25, $contact->otherCompanies[0]->companyId);
        self::assertTrue($contact->otherCompanies[0]->canViewAllTickets);

        self::assertInstanceOf(Avatar::class, $contact->avatar);
        self::assertSame('<AVATAR_URL>', $contact->avatar->url);
        self::assertSame('application/octet-stream', $contact->avatar->contentType);
        self::assertSame(4, $contact->avatar->id);
        self::assertSame('rails.png', $contact->avatar->name);
        self::assertSame(13036, $contact->avatar->size);

        self::assertInstanceOf(DateTimeImmutable::class, $contact->avatar->createdAt);
        self::assertInstanceOf(DateTimeImmutable::class, $contact->avatar->updatedAt);
        self::assertSame('2015-08-28 10:27:58', $contact->createdAt->format('Y-m-d H:i:s'));
        self::assertSame('2015-08-28 10:27:58', $contact->createdAt->format('Y-m-d H:i:s'));

        self::assertIsArray($contact->customFields);
        self::assertArrayHasKey('department', $contact->customFields);
        self::assertSame('Operations', $contact->customFields['department']);
    }
}
