<?php

namespace App\Dto;

readonly class CveDetailsMetricDto
{
    public function __construct(
        public string $cvssVersionSource,
        public string $metricTypeQualifier,
        public ?string $source,
        public string $version,
        public float $baseScore,
        public string $baseSeverity,
        public string $vector,
    ) {
    }
}
