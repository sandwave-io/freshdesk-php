[![](https://user-images.githubusercontent.com/60096509/91668964-54ecd500-eb11-11ea-9c35-e8f0b20b277a.png)](https://sandwave.io)


# Freshdesk API - PHP SDK

[![GitHub Workflow Status](https://img.shields.io/github/workflow/status/sandwave-io/freshdesk-php/CI)](https://packagist.org/packages/sandwave-io/freshdesk-php)
[![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/sandwave-io/freshdesk-php)](https://packagist.org/packages/sandwave-io/freshdesk-php)
[![Packagist PHP Version Support](https://img.shields.io/packagist/v/sandwave-io/freshdesk-php)](https://packagist.org/packages/sandwave-io/freshdesk-php)
[![Packagist Downloads](https://img.shields.io/packagist/dt/sandwave-io/freshdesk-php)](https://packagist.org/packages/sandwave-io/freshdesk-php)

## Supported APIs

This SDK currently supports these APIs:

* [Ticket API](https://developers.freshdesk.com/api/#tickets)
* [Contacts API](https://developers.freshdesk.com/api/#contacts)

Are you missing functionality? Feel free to create an issue, or hit us up with a pull request.

## How to use (REST API)

```bash
composer require sandwave-io/freshdesk-php
```

```php
<?php

use SandwaveIo\Freshdesk\SerializerFactory;
use SandwaveIo\Freshdesk\FreshdeskClient;
use SandwaveIo\Freshdesk\Client\RestClient;
use SandwaveIo\Freshdesk\RestClientFactory;

$factory = new RestClientFactory(
    'api-endpoint',
    'API key',
);

$serializer = SerializerFactory::create();
$restClient = new RestClient(
    $factory->create(),
    $serializer
);

$freshdeskClient = new FreshdeskClient($restClient);
$freshdeskClient->getTicketClient()->get(123);
```

## How to contribute

Feel free to create a PR if you have any ideas for improvements. Or create an issue.

* When adding code, make sure to add tests for it (phpunit).
* Make sure the code adheres to our coding standards (use php-cs-fixer to check/fix).
* Also make sure PHPStan does not find any bugs.

```bash
composer analyze # this will (dry)run php-cs-fixer, phpstan and phpunit

composer phpcs-fix # this will actually let php-cs-fixer run to fix
```

These tools will also run in GitHub actions on PR's and pushes on main.
