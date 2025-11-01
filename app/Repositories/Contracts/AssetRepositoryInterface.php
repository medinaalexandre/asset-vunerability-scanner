<?php

namespace App\Repositories\Contracts;

use App\Dto\AssetDto;
use App\Exceptions\VulnerabilityAlreadyAttachedException;
use App\Models\Asset;

interface AssetRepositoryInterface
{
    public function create(AssetDto $assetDto, int $userId): Asset;

    public function find(int $id): ?Asset;

    /** @throws VulnerabilityAlreadyAttachedException */
    public function attachVulnerability(int $assetId, int $vulnerabilityId): void;
}
