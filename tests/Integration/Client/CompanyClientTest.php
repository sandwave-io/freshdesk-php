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
use SandwaveIo\Freshdesk\Entity\Company;
use SandwaveIo\Freshdesk\FreshdeskClient;
use SandwaveIo\Freshdesk\SerializerFactory;

class CompanyClientTest extends TestCase
{
    public function testGet(): void
    {
        $jsonResponse = (string) file_get_contents(__DIR__ . '/../../data/get_company.json');

        $mockHandler = new MockHandler(
            [new Response(200, [], $jsonResponse)]
        );
        $stack = HandlerStack::create($mockHandler);
        $guzzle = new Client(['handler' => $stack]);

        $serializer = SerializerFactory::create();
        $restClient = new RestClient($guzzle, $serializer);
        $freshdeskClient = new FreshdeskClient($restClient);

        $company = $freshdeskClient->getCompanyClient()->get(12345);

        $this->assertDeserializedCompany($company);
    }

    public function testUpdate(): void
    {
        $jsonResponse = (string) file_get_contents(__DIR__ . '/../../data/get_company.json');

        $contact = new Company();
        $contact->id = 1234;
        $contact->name = 'johndoecompany';
        $contact->description = 'some description';

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
        $return = $freshdeskClient->getCompanyClient()->update(14, $contact);

        $this->assertDeserializedCompany($return);
    }

    public function testCreate(): void
    {
        $jsonResponse = (string) file_get_contents(__DIR__ . '/../../data/get_company.json');

        $contact = new Company();
        $contact->id = 1234;
        $contact->name = 'johndoecompany';
        $contact->description = 'johndoecompany description';
        $contact->note = 'active customer';
        $contact->industry = 'business';

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
        $return = $freshdeskClient->getCompanyClient()->create($contact);

        $this->assertDeserializedCompany($return);
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
        $freshdeskClient->getCompanyClient()->delete(123);
    }

    private function assertDeserializedCompany(Company $company): void
    {
        self::assertSame(8, $company->id);
        self::assertSame('SuperNova', $company->name);
        self::assertSame('Spaceship Manufacturing Company', $company->description);
        self::assertSame('Happy', $company->healthScore);
        self::assertSame('Premium', $company->accountTier);
        self::assertSame(['supernova', 'nova'], $company->domains);

        self::assertInstanceOf(DateTimeImmutable::class, $company->createdAt);
        self::assertInstanceOf(DateTimeImmutable::class, $company->updatedAt);
        self::assertInstanceOf(DateTimeImmutable::class, $company->renewalDate);
        self::assertSame('2014-01-08 09:08:53', $company->createdAt->format('Y-m-d H:i:s'));
        self::assertSame('2014-01-08 09:08:53', $company->updatedAt->format('Y-m-d H:i:s'));
        self::assertSame('2020-12-31 00:00:00', $company->renewalDate->format('Y-m-d H:i:s'));

        self::assertNull($company->customFields);
        self::assertNull($company->note);
        self::assertNull($company->industry);
    }
}
