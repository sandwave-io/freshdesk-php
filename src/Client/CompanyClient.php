<?php

declare(strict_types = 1);

namespace SandwaveIo\Freshdesk\Client;

use SandwaveIo\Freshdesk\Entity\Company;
use SandwaveIo\Freshdesk\Exception\FreshdeskException;

final class CompanyClient
{
    private const URL_COMPANY = 'companies/';
    private const URL_COMPANY_ID = 'companies/%s';

    private RestClientInterface $client;

    public function __construct(RestClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @throws FreshdeskException
     */
    public function get(int $companyId): Company
    {
        return $this->client->get(sprintf(self::URL_COMPANY_ID, $companyId), Company::class);
    }

    /**
     * @throws FreshdeskException
     */
    public function update(int $companyId, Company $company): Company
    {
        return $this->client->put(sprintf(self::URL_COMPANY_ID, $companyId), $company, Company::class);
    }

    /**
     * @throws FreshdeskException
     */
    public function create(Company $company): Company
    {
        return $this->client->post(self::URL_COMPANY, $company, Company::class);
    }

    /**
     * @throws FreshdeskException
     */
    public function delete(int $companyId): void
    {
        $this->client->delete(sprintf(self::URL_COMPANY_ID, $companyId));
    }
}
