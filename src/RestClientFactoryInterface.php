<?php

declare(strict_types = 1);

namespace SandwaveIo\Freshdesk;

use GuzzleHttp\ClientInterface;

interface RestClientFactoryInterface
{
    public function create(): ClientInterface;
}
