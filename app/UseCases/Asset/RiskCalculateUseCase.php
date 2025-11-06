<?php

namespace App\UseCases\Asset;

use App\Dto\CalculatedAssetRisk;
use App\Repositories\Contracts\AssetRepositoryInterface;
use App\Repositories\VulnerabilityFactsRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RiskCalculateUseCase
{
    public const CRITICAL_THRESHOLD = 30;
    public const HIGH_THRESHOLD = 10;
    public const MEDIUM_THRESHOLD = 5;

    public function __construct(
        protected readonly AssetRepositoryInterface $assetRepository,
        protected readonly VulnerabilityFactsRepository $vulnerabilityFactsRepository,
    ) {
    }

    public function execute(int $assetId, int $userId): CalculatedAssetRisk
    {
        $asset = $this->assetRepository->findByUser($assetId, $userId);

        if (is_null($asset)) {
            throw new ModelNotFoundException("Asset not found");
        }

        $result = $this->vulnerabilityFactsRepository->getCalculatedRiskForAsset($assetId);

        return new CalculatedAssetRisk(
            assetId: $result->assetId,
            calculatedRisk: $result->calculatedRisk,
            maxCveScore: $result->maxCveScore,
            assetWeightFactor: $result->assetWeightFactor,
            riskLevel: $this->determineRiskLevel($result->calculatedRisk),
            calculationTimestamp: Carbon::now()->timestamp,
        );
    }

    protected function determineRiskLevel(float $score): string
    {
        return match (true) {
            $score >= self::CRITICAL_THRESHOLD => 'Critical',
            $score >= self::HIGH_THRESHOLD     => 'High',
            $score >= self::MEDIUM_THRESHOLD   => 'Medium',
            default => 'Low',
        };
    }
}
