<?php

mutates(NvdApiService::class);

use App\Clients\NvdGuzzleClient;
use App\Dto\CveDetailsDto;
use App\Exceptions\CveIdNotFoundException;
use App\Exceptions\InvalidApiResponseException;
use App\Services\NvdApiService;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\RequestOptions;

beforeEach(function () {
    $this->clientMock = Mockery::mock(NvdGuzzleClient::class);
    $this->apiService = new NvdApiService($this->clientMock);
});

it('can get a response', function () {
    $cveId = 'CVE-2025-0001';
    $jsonResponse = file_get_contents(__DIR__ . '/../Mocks/nvd_api_cve_2025_0001_details_response.json');

    $this->clientMock->shouldReceive('get')
        ->with('cves/2.0', [
            RequestOptions::QUERY => [
                'cveId' => $cveId,
            ]
        ])
        ->once()
        ->andReturn(new Response(body: $jsonResponse));

    $res = $this->apiService->getDetails($cveId);
    $metric = $res->metrics[0];

    expect($res)->toBeInstanceOf(CveDetailsDto::class)
        ->and($res->id)->toBe($cveId)
        ->and($res->sourceIdentifier)->toBe('vulnerability@ncsc.ch')
        ->and($res->publishedAt)->toBe('2025-02-17T10:15:08.550')
        ->and($res->lastModifiedAt)->toBe('2025-02-17T10:15:08.550')
        ->and($res->status)->toBe('Awaiting Analysis')
        ->and($res->cweId)->toBe('CWE-36')
        ->and($res->description)->toStartWith('Abacus ERP is versions older than 2024.210.16036')
        ->and($metric->cvssVersionSource)->toBe('cvssMetricV31')
        ->and($metric->metricTypeQualifier)->toBe('Secondary')
        ->and($metric->source)->toBe('vulnerability@ncsc.ch')
        ->and($metric->version)->toBe('3.1')
        ->and($metric->baseScore)->toBe(6.5)
        ->and($metric->baseSeverity)->toBe('MEDIUM')
        ->and($metric->vector)->toBe('CVSS:3.1/AV:N/AC:L/PR:L/UI:N/S:U/C:H/I:N/A:N')
        ->and($res->references)->toHaveCount(1)
        ->and($res->references[0])->toBe('https://borelenzo.github.io/stuff/2025/02/15/CVE-2025-0001.html');
});

it('throws CveIdNotFoundException if receive 404 as response', function () {
    $this->clientMock->shouldReceive('get')
        ->once()
        ->andThrow(
            new ClientException('client error', request: new Request('GET', 'cve/2.0'), response: new Response(404))
        );

    $this->apiService->getDetails('CVE-2025-0001');
})->throws(CveIdNotFoundException::class, 'CVE-ID CVE-2025-0001 not found', 404);

it('throws RuntimeException if receive ClientException without code 404', function () {
    $this->clientMock->shouldReceive('get')
        ->once()
        ->andThrow(new ClientException('ukn error', request: new Request('GET', 'cve/2.0'), response: new Response(500)));
    $this->apiService->getDetails('CVE-2025-0001');
})->throws(RuntimeException::class, 'Error fetching CVE details: ukn error', 500);

it('throws InvalidApiResponse if response is not a valid json', function () {
    $this->clientMock->shouldReceive('get')
        ->once()
        ->andReturn(new Response(200, [], '<html>ngnix error</html>'));

    $hasThrownException = false;

    try {
        $this->apiService->getDetails('CVE-2025-0001');
    } catch (InvalidApiResponseException $e) {
        $exceptionContent = $e->content;
        $hasThrownException = true;
    }

    expect($hasThrownException)->toBeTrue()
        ->and($exceptionContent)->toBe('<html>ngnix error</html>');
});

it('throws InvalidApiResponse if API contract is broken', function () {
    $jsonResponse = file_get_contents(__DIR__ . '/../Mocks/invalid_nvd_api_cve_details_response.json');
    $this->clientMock->shouldReceive('get')
        ->once()
        ->andReturn(new Response(200, [], $jsonResponse));

    $hasThrownException = false;
    $exceptionContent = null;

    try {
        $this->apiService->getDetails('CVE-2025-0001');
    } catch (InvalidApiResponseException $e) {
        $hasThrownException = true;
        $exceptionContent = $e->content;
    }

    expect($hasThrownException)->toBeTrue()
        ->and($exceptionContent)->toBe(json_decode($jsonResponse, true, flags: JSON_THROW_ON_ERROR));
});
