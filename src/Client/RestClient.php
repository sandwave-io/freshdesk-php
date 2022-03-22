<?php

declare(strict_types = 1);

namespace SandwaveIo\Freshdesk\Client;

use Exception;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\TooManyRedirectsException;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\RequestOptions;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Psr\Http\Message\ResponseInterface;
use ReflectionClass;
use ReflectionException;
use SandwaveIo\Freshdesk\Exception\BadRequestException;
use SandwaveIo\Freshdesk\Exception\DeserializationException;
use SandwaveIo\Freshdesk\Exception\FreshdeskException;
use SandwaveIo\Freshdesk\Exception\NetworkException;
use SandwaveIo\Freshdesk\Exception\RatelimitExceededException;
use SandwaveIo\Freshdesk\Exception\ResourceNotFoundException;
use SandwaveIo\Freshdesk\Exception\ServerException as FreshdeskServerException;
use SandwaveIo\Freshdesk\Exception\UnauthorizedException;

final class RestClient implements RestClientInterface
{
    private const REQUEST_TIMEOUT = 5;

    private ClientInterface $client;

    private SerializerInterface $serializer;

    public function __construct(ClientInterface $client, SerializerInterface $serializer)
    {
        $this->client = $client;
        $this->serializer = $serializer;
    }

    /**
     * @template T of object
     *
     * @param string          $url
     * @param class-string<T> $returnType
     *
     * @throws FreshdeskException
     *
     * @return T
     *
     */
    public function get(string $url, string $returnType): object
    {
        $this->assertValidClass($returnType);
        $response = $this->request('GET', $url);
        $json = $response->getBody()->getContents();

        return $this->serializer->deserialize($json, $returnType, 'json', DeserializationContext::create()->setGroups(['read']));
    }

    /**
     * @template T
     *
     * @param string          $url
     * @param object          $data
     * @param class-string<T> $returnType
     *
     * @return T
     */
    public function post(string $url, object $data, string $returnType)
    {
        $this->assertValidClass($returnType);
        $json = $this->serializer->serialize($data, 'json', SerializationContext::create()->setGroups(['create']));

        $response = $this->request('POST', $url, [
            'body' => $json,
            'headers' => [
                'Content-type' => 'application/json; charset=utf-8',
            ],
        ]);

        return $this->serializer->deserialize($response->getBody()->getContents(), $returnType, 'json');
    }

    /**
     * @template T
     *
     * @param string          $url
     * @param object          $data
     * @param class-string<T> $returnType
     *
     * @return T
     */
    public function put(string $url, object $data, string $returnType)
    {
        $this->assertValidClass($returnType);
        $json = $this->serializer->serialize($data, 'json', SerializationContext::create()->setGroups(['update']));

        $response = $this->request('PUT', $url, [
            'body' => $json,
            'headers' => [
                'Content-type' => 'application/json; charset=utf-8',
            ],
        ]);

        return $this->serializer->deserialize($response->getBody()->getContents(), $returnType, 'json');
    }

    public function delete(string $url): void
    {
        $this->request('DELETE', $url);
    }

    /**
     * @param string $method
     * @param string $url
     * @param array  $options
     *
     * @throws FreshdeskException
     *
     * @return ResponseInterface
     *
     */
    public function request(string $method, string $url, array $options = []): ResponseInterface
    {
        try {
            $response = $this->client->request($method, $url, array_merge($options, $this->getRequestOptions()));
        } catch (TransferException $exception) {
            throw $this->convertException($exception);
        }

        return $response;
    }

    private function getRequestOptions(): array
    {
        return [
            RequestOptions::CONNECT_TIMEOUT => self::REQUEST_TIMEOUT,
        ];
    }

    /**
     * @template T
     *
     * @param class-string<T> $className
     */
    private function assertValidClass(string $className): void
    {
        try {
            new ReflectionClass($className);
        } catch (ReflectionException $exception) {
            throw new DeserializationException(sprintf('Supplied classname %s does not exist', $className));
        }
    }

    private function convertException(Exception $exception): FreshdeskException
    {
        $message = $exception->getMessage();

        if ($exception instanceof ConnectException || $exception instanceof TooManyRedirectsException) {
            return new NetworkException($message, 0, $exception);
        }

        if ($exception instanceof ServerException) {
            // error 500 range
            return new FreshdeskServerException($message, 0, $exception);
        }

        if ($exception instanceof ClientException) {
            if ($exception->getCode() === 404) {
                return new ResourceNotFoundException($message, 0, $exception);
            }

            if ($exception->getCode() === 401) {
                return new UnauthorizedException($message, 0, $exception);
            }

            if ($exception->getCode() === 429) {
                return new RatelimitExceededException($message, 0, $exception);
            }

            // 400 range
            return new BadRequestException($message, 0, $exception);
        }

        return new FreshdeskException($message, 0, $exception);
    }
}
