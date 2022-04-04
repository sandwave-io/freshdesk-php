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
use SandwaveIo\Freshdesk\Entity\Product;
use SandwaveIo\Freshdesk\FreshdeskClient;
use SandwaveIo\Freshdesk\SerializerFactory;

class ProductClientTest extends TestCase
{
    public function testGet(): void
    {
        $jsonResponse = (string) file_get_contents(__DIR__ . '/../../data/get_product.json');

        $mockHandler = new MockHandler(
            [new Response(200, [], $jsonResponse)]
        );
        $stack = HandlerStack::create($mockHandler);
        $guzzle = new Client(['handler' => $stack]);

        $serializer = SerializerFactory::create();
        $restClient = new RestClient($guzzle, $serializer);
        $freshdeskClient = new FreshdeskClient($restClient);

        $product = $freshdeskClient->getProductClient()->get(12345);

        $this->assertDeserializedProduct($product);
    }

    public function testUpdate(): void
    {
        $jsonResponse = (string) file_get_contents(__DIR__ . '/../../data/get_product.json');

        $product = new Product();
        $product->id = 1234;
        $product->name = 'testproduct';
        $product->description = 'description';

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
                self::assertArrayNotHasKey('id', $decoded);
                self::assertArrayNotHasKey('created_at', $decoded);

                return $handler($request, $options);
            };
        });

        $guzzle = new Client(['handler' => $stack]);

        $serializer = SerializerFactory::create();
        $restClient = new RestClient($guzzle, $serializer);
        $freshdeskClient = new FreshdeskClient($restClient);
        $return = $freshdeskClient->getProductClient()->update(1234, $product);

        $this->assertDeserializedProduct($return);
    }

    public function testCreate(): void
    {
        $jsonResponse = (string) file_get_contents(__DIR__ . '/../../data/get_product.json');

        $product = new Product();
        $product->id = 1234;
        $product->name = 'johndoe';
        $product->description = 'testtest';

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
        $return = $freshdeskClient->getProductClient()->create($product);

        $this->assertDeserializedProduct($return);
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
        $freshdeskClient->getProductClient()->delete(123);
    }

    private function assertDeserializedProduct(Product $product): void
    {
        self::assertSame(1, $product->id);
        self::assertSame('Freshservice', $product->name);
        self::assertSame('Support for IT', $product->description);

        self::assertInstanceOf(DateTimeImmutable::class, $product->createdAt);
        self::assertInstanceOf(DateTimeImmutable::class, $product->updatedAt);
        self::assertSame('2015-07-03 09:08:53', $product->createdAt->format('Y-m-d H:i:s'));
        self::assertSame('2015-07-03 09:08:53', $product->updatedAt->format('Y-m-d H:i:s'));
    }
}
