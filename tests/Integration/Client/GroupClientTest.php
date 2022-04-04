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
use SandwaveIo\Freshdesk\Entity\Group;
use SandwaveIo\Freshdesk\FreshdeskClient;
use SandwaveIo\Freshdesk\SerializerFactory;

class GroupClientTest extends TestCase
{
    public function testGet(): void
    {
        $jsonResponse = (string) file_get_contents(__DIR__ . '/../../data/get_group.json');

        $mockHandler = new MockHandler(
            [new Response(200, [], $jsonResponse)]
        );
        $stack = HandlerStack::create($mockHandler);
        $guzzle = new Client(['handler' => $stack]);

        $serializer = SerializerFactory::create();
        $restClient = new RestClient($guzzle, $serializer);
        $freshdeskClient = new FreshdeskClient($restClient);

        $group = $freshdeskClient->getGroupClient()->get(12345);

        $this->assertDeserializedGroup($group);
    }

    public function testUpdate(): void
    {
        $jsonResponse = (string) file_get_contents(__DIR__ . '/../../data/get_group.json');

        $group = new Group();
        $group->id = 1234;
        $group->name = 'johndoe';
        $group->description = 'test';

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
                self::assertArrayHasKey('description', $decoded);
                self::assertArrayNotHasKey('created_at', $decoded);

                return $handler($request, $options);
            };
        });

        $guzzle = new Client(['handler' => $stack]);

        $serializer = SerializerFactory::create();
        $restClient = new RestClient($guzzle, $serializer);
        $freshdeskClient = new FreshdeskClient($restClient);
        $return = $freshdeskClient->getGroupClient()->update(14, $group);

        $this->assertDeserializedGroup($return);
    }

    public function testCreate(): void
    {
        $jsonResponse = (string) file_get_contents(__DIR__ . '/../../data/get_group.json');

        $group = new Group();
        $group->id = 1234;
        $group->name = 'johndoe';
        $group->description = 'test';

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
                self::assertArrayHasKey('description', $decoded);
                self::assertArrayNotHasKey('created_at', $decoded);

                return $handler($request, $options);
            };
        });

        $guzzle = new Client(['handler' => $stack]);

        $serializer = SerializerFactory::create();
        $restClient = new RestClient($guzzle, $serializer);
        $freshdeskClient = new FreshdeskClient($restClient);
        $return = $freshdeskClient->getGroupClient()->create($group);

        $this->assertDeserializedGroup($return);
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
        $freshdeskClient->getGroupClient()->delete(123);
    }

    private function assertDeserializedGroup(Group $group): void
    {
        self::assertSame(5, $group->id);
        self::assertSame('Entertainment', $group->name);

        self::assertInstanceOf(DateTimeImmutable::class, $group->createdAt);
        self::assertInstanceOf(DateTimeImmutable::class, $group->updatedAt);
        self::assertSame('2014-01-08 07:53:41', $group->createdAt->format('Y-m-d H:i:s'));
        self::assertSame('2014-01-08 07:53:41', $group->updatedAt->format('Y-m-d H:i:s'));
    }
}
