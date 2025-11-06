<?php

use App\Dto\CalculatedAssetRisk;
use App\Models\Asset;
use App\Repositories\Contracts\AssetRepositoryInterface;
use App\Repositories\Contracts\VulnerabilityFactsRepositoryInterface;
use App\UseCases\Asset\RiskCalculateUseCase;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

it('can calculate risk from an asset', function () {
    $assetRepositoryMock = Mockery::mock(AssetRepositoryInterface::class);
    $vulnerabilityFactsRepository = Mockery::mock(VulnerabilityFactsRepositoryInterface::class);
    Carbon::setTestNow('2025-01-01 00:00:00');

    $calculatedDto = new CalculatedAssetRisk(
        1,
        8.5,
        9.0,
        4,
    );
    $assetRepositoryMock->shouldReceive('findByUser')->with(1,1)->andReturn(new Asset());
    $vulnerabilityFactsRepository->shouldReceive('getCalculatedRiskForAsset')->with(1)
        ->andReturn($calculatedDto);

    $useCase = new RiskCalculateUseCase($assetRepositoryMock, $vulnerabilityFactsRepository);

    $result = $useCase->execute(1,1);

    expect($result)->toBeInstanceOf(CalculatedAssetRisk::class)
        ->and($result->assetId)->toBe($calculatedDto->assetId)
        ->and($result->calculatedRisk)->toBe($calculatedDto->calculatedRisk)
        ->and($result->maxCveScore)->toBe($calculatedDto->maxCveScore)
        ->and($result->assetWeightFactor)->toBe($calculatedDto->assetWeightFactor)
        ->and($result->riskLevel)->toBe('Medium')
        ->and($result->calculationTimestamp)->toBe((string) Carbon::now()->timestamp);
});

it('should determine risk level correctly', function (float $score, string $expectedRiskLevel) {
    $risckCalculateUseCase = App::make(RiskCalculateUseCase::class);

    $riskLevel = $this->callNonPublicMethod($risckCalculateUseCase, 'determineRiskLevel', [$score]);
    expect($riskLevel)->toBe($expectedRiskLevel);
})->with([
    [0, 'Low'],
    [5, 'Medium'],
    [10, 'High'],
    [20, 'High'],
    [50, 'Critical'],
    [INF, 'Critical'],
]);
