<?php

namespace App\Repositories;

use App\Dto\AssetDto;
use App\Exceptions\VulnerabilityAlreadyAttachedException;
use App\Models\Asset;
use App\Repositories\Contracts\AssetRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\UniqueConstraintViolationException;

class EloquentAssetRepository implements AssetRepositoryInterface
{
    public function __construct(protected Asset $model)
    {
    }

    /** @return Builder<Asset> */
    protected function newQuery(): Builder
    {
        return $this->model->newQuery();
    }

    public function create(AssetDto $assetDto, int $userId): Asset
    {
        return $this->newQuery()->create([
            'name' => $assetDto->name,
            'description' => $assetDto->description,
            'device_type' => $assetDto->deviceType,
            'location' => $assetDto->location,
            'status' => $assetDto->status,
            'user_id' => $userId,
            'criticality_level' => $assetDto->criticalityLevel
        ]);
    }

    public function find(int $id): ?Asset
    {
        return $this->newQuery()->where('id', $id)->first();
    }

    public function attachVulnerability(int $assetId, int $vulnerabilityId): void
    {
        try {
            $this->find($assetId)?->vulnerabilities()->attach($vulnerabilityId);
        } catch (UniqueConstraintViolationException) {
            throw new VulnerabilityAlreadyAttachedException;
        }
    }
}
