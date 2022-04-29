<?php

declare(strict_types = 1);

namespace SandwaveIo\Freshdesk\Client;

use Exception;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\TooManyRedirectsException;
use GuzzleHttp\RequestOptions;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
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

    private LoggerInterface $logger;

    public function __construct(ClientInterface $client, SerializerInterface $serializer, ?LoggerInterface $logger = null)
    {
        $this->client = $client;
        $this->serializer = $serializer;
        $this->logger = $logger ?? new NullLogger();
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
        $json = (string) $response->getBody();

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

        return $this->serializer->deserialize((string) $response->getBody(), $returnType, 'json', DeserializationContext::create()->setGroups(['read']));
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

        return $this->serializer->deserialize((string) $response->getBody(), $returnType, 'json', DeserializationContext::create()->setGroups(['read']));
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
        $this->client->getConfig();

        $logContext = [
            'body' => $options['body'] ?? null,
            'method' => $method,
            'url' => $url,
        ];

        if (isset($options['body'])) {
            $logContext['body'] = $options['body'];
        }
        $logMessage = sprintf('Freshdesk.REQUEST: %s %s', $method, $url);

        $this->logger->debug($logMessage, $logContext);

        try {
            $response = $this->client->request($method, $url, array_merge($options, $this->getRequestOptions()));
        } catch (ConnectException $exception) {
            $logMessage = sprintf('Freshdesk.ERROR: %s', $exception->getMessage());
            $this->logger->notice($logMessage);

            throw $this->convertException($exception);
        } catch (RequestException $exception) {
            $response = $exception->getResponse();
            if ($response instanceof ResponseInterface) {
                $this->logResponse($response);
            }
            throw $this->convertException($exception);
        }

        $this->logResponse($response);

        return $response;
    }

    private function logResponse(ResponseInterface $response): void
    {
        $logMessage = sprintf(
            'Freshdesk.RESPONSE: %s - BODY: %s',
            $response->getStatusCode(),
            $response->getBody()
        );

        $logContext = [
            'response_code' => $response->getStatusCode(),
            'response_body' => (string) $response->getBody(),
            'response_headers' => $response->getHeaders(),
        ];

        $this->logger->debug($logMessage, $logContext);
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
