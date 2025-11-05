<?php

namespace App\Repositories\Contracts;

use App\Dto\AssetDto;
use App\Exceptions\VulnerabilityAlreadyAttachedException;
use App\Models\Asset;

interface AssetRepositoryInterface
{
    public function create(AssetDto $assetDto, int $userId): Asset;

    public function find(int $id): ?Asset;

    public function findByUser(int $id, int $userId): ?Asset;

    /** @throws VulnerabilityAlreadyAttachedException */
    public function attachVulnerability(int $assetId, int $vulnerabilityId): void;

    public function detachVulnerability(int $assetId, int $vulnerabilityId): void;

    public function delete(int $assetId): void;

    public function update(int $assetId, AssetDto $dto): void;
}
