<?php

namespace App\Dto;

readonly class CalculatedAssetRisk
{
    public function __construct(
        public int $assetId,
        public float $calculatedRisk,
        public float $maxCveScore,
        public int $assetWeightFactor,
        public ?string $riskLevel = null,
        public ?string $calculationTimestamp = null,
    ) {
    }
}
