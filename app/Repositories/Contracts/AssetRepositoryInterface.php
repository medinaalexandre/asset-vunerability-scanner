<?php

namespace App\Repositories\Contracts;

use App\Dto\AssetDto;
use App\Models\Asset;

interface AssetRepositoryInterface
{
    public function create(AssetDto $assetDto, int $userId): Asset;

    public function find(int $id): ?Asset;

    public function attachVulnerability(int $assetId, int $vulnerabilityId): void;
}
