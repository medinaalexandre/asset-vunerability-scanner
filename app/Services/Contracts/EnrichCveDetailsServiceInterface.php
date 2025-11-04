<?php

namespace App\Services\Contracts;

use App\Dto\CveDetailsDto;
use App\Exceptions\CveIdNotFoundException;
use App\Exceptions\InvalidApiResponseException;
use GuzzleHttp\Exception\GuzzleException;

interface EnrichCveDetailsServiceInterface
{
    /**
     * @throws CveIdNotFoundException
     * @throws InvalidApiResponseException
     * @throws GuzzleException
     */
    public function getDetails(string $cveId): CveDetailsDto;
}
