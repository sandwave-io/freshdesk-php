<?php

declare(strict_types = 1);

namespace SandwaveIo\Freshdesk\Client;

use SandwaveIo\Freshdesk\Entity\Product;
use SandwaveIo\Freshdesk\Exception\FreshdeskException;

final class ProductClient
{
    private const URL_PRODUCT = 'products/';
    private const URL_PRODUCT_ID = 'products/%s';

    private RestClientInterface $client;

    public function __construct(RestClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @throws FreshdeskException
     */
    public function get(int $productId): Product
    {
        return $this->client->get(sprintf(self::URL_PRODUCT_ID, $productId), Product::class);
    }

    /**
     * @throws FreshdeskException
     */
    public function update(int $productId, Product $ticket): Product
    {
        return $this->client->put(sprintf(self::URL_PRODUCT_ID, $productId), $ticket, Product::class);
    }

    /**
     * @throws FreshdeskException
     */
    public function create(Product $ticket): Product
    {
        return $this->client->post(self::URL_PRODUCT, $ticket, Product::class);
    }

    /**
     * @throws FreshdeskException
     */
    public function delete(int $productId): void
    {
        $this->client->delete(sprintf(self::URL_PRODUCT_ID, $productId));
    }
}
