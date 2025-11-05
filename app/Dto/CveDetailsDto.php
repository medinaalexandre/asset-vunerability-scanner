<?php

namespace App\Dto;

readonly class CveDetailsDto
{
    /**
     * @param array<CveDetailsMetricDto> $metrics
     * @param array<string> $references
     */
    public function __construct(
        public ?string $id,
        public ?string $sourceIdentifier,
        public ?string $publishedAt,
        public ?string $lastModifiedAt,
        public ?string $status,
        public ?string $description,
        public ?string $cweId,
        public array $metrics,
        public array $references,
    ) {
    }
}
