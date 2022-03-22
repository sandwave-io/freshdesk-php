<?php

declare(strict_types = 1);

namespace SandwaveIo\Freshdesk\Client;

use SandwaveIo\Freshdesk\Exception\FreshdeskException;

interface RestClientInterface
{
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
    public function get(string $url, string $returnType): object;

    /**
     * @template T
     *
     * @param string          $url
     * @param object          $data
     * @param class-string<T> $returnType
     *
     * @return T
     */
    public function post(string $url, object $data, string $returnType);

    /**
     * @template T
     *
     * @param string          $url
     * @param object          $data
     * @param class-string<T> $returnType
     *
     * @return T
     */
    public function put(string $url, object $data, string $returnType);

    /**
     * @throws FreshdeskException
     */
    public function delete(string $url): void;
}
