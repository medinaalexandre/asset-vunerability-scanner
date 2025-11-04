<?php

namespace App\Services;

use App\Dto\CveDetailsDto;
use App\Dto\CveDetailsMetricDto;
use App\Exceptions\CveIdNotFoundException;
use App\Exceptions\InvalidApiResponseException;
use App\Services\Clients\NvdGuzzleClient;
use App\Services\Contracts\EnrichCveDetailsServiceInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use JsonException;
use RuntimeException;

class NvdApiService implements EnrichCveDetailsServiceInterface
{
    public function __construct(
        protected NvdGuzzleClient $client,
    ) {
    }

    /**
     * @throws CveIdNotFoundException
     * @throws InvalidApiResponseException
     * @throws GuzzleException
     */
    public function getDetails(string $cveId): CveDetailsDto
    {
        try {
            $response = $this->client->get('cves/2.0', [
                RequestOptions::QUERY => [
                    'cveId' => $cveId,
                ]
            ]);

            $contents = $response->getBody()->getContents();
            $responseData = json_decode($contents, true, flags: JSON_THROW_ON_ERROR);
        } catch (ClientException $exception) {
            if ($exception->getCode() === 404) {
                throw new CveIdNotFoundException(cveId: $cveId, previous: $exception);
            }
            throw new RuntimeException("Error fetching CVE details: " . $exception->getMessage(), 500, $exception);
        } catch (JsonException) {
            $response->getBody()->rewind();
            throw new InvalidApiResponseException(content: $response->getBody()->getContents());
        }

        if (empty($responseData['format']) || $responseData['format'] !== 'NVD_CVE') {
            throw new InvalidApiResponseException(content: $responseData);
        }

        return $this->mapToCveDetailsDto($responseData);
    }

    protected function mapToCveDetailsDto(array $responseData): CveDetailsDto
    {
        $cveData = $responseData['vulnerabilities'][0]['cve'];
        $description = collect($cveData['descriptions'] ?? [])
            ->sortBy(fn (array $item) => $item['lang'] !== 'en')
            ->first()['value'] ?? null;

        $cweId = collect($cveData['weaknesses'][0]['description'] ?? [])
            ->sortBy(fn (array $item) => $item['lang'] !== 'en')
            ->first()['value'] ?? null;

        $metricDtos = [];
        $allMetrics = $cveData['metrics'] ?? [];

        foreach ($allMetrics as $cvssVersionSource => $versionMetrics) {
            foreach ($versionMetrics as $metric) {
                $cvssData = $metric['cvssData'] ?? null;
                $metricDtos[] = new CveDetailsMetricDto(
                    cvssVersionSource: $cvssVersionSource,
                    metricTypeQualifier: $metric['type'],
                    source: $metric['source'] ?? null,
                    version: $cvssData['version'] ?? 'N/A',
                    baseScore: $cvssData['baseScore'],
                    baseSeverity: $cvssData['baseSeverity'] ?? 'UNAVAILABLE',
                    vector: $cvssData['vectorString'],
                );
            }
        }

        $referenceUrls = collect($cveData['references'] ?? [])
            ->pluck('url')
            ->filter()
            ->toArray();

        return new CveDetailsDto(
            id: $cveData['id'],
            sourceIdentifier: $cveData['sourceIdentifier'] ?? null,
            publishedAt: $cveData['published'] ?? null,
            lastModifiedAt: $cveData['lastModified'] ?? null,
            status: $cveData['vulnStatus'] ?? null,
            description: $description,
            cweId: $cweId,
            metrics: $metricDtos,
            references: $referenceUrls,
        );
    }
}
