<?php

namespace App\Dto;

final class AssetDto
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $description,
        public readonly string $deviceType,
        public readonly string $location,
        public string $status = ''
    ) {
    }
}
