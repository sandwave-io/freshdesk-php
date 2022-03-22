<?php

declare(strict_types = 1);

namespace SandwaveIo\Freshdesk;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

final class RestClientFactory implements RestClientFactoryInterface
{
    private string $url;

    private string $apiKey;

    public function __construct(string $url, string $apiKey)
    {
        $this->url = $url;
        $this->apiKey = $apiKey;
    }

    public function create(): ClientInterface
    {
        return new Client(
            [
                'base_uri' => $this->url,
                'auth' => [
                    $this->apiKey,
                    'X',
                ],
            ]
        );
    }
}
