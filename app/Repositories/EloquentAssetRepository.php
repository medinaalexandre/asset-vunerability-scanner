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
        return $this->newQuery()->create($this->dtoToArrayUpsertData($assetDto, $userId));
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

    public function detachVulnerability(int $assetId, int $vulnerabilityId): void
    {
        $this->find($assetId)?->vulnerabilities()->detach($vulnerabilityId);
    }

    public function findByUser(int $id, int $userId): ?Asset
    {
        return $this->newQuery()->where('user_id', $userId)->where('id', $id)->first();
    }

    public function delete(int $assetId): void
    {
        $this->newQuery()->where('id', $assetId)->delete();
    }

    public function update(int $assetId, AssetDto $assetDto): void
    {
        $this->newQuery()->update($this->dtoToArrayUpsertData($assetDto));
    }

    protected function dtoToArrayUpsertData(AssetDto $assetDto, ?int $userId = null): array
    {
        return array_filter([
            'name' => $assetDto->name,
            'description' => $assetDto->description,
            'device_type' => $assetDto->deviceType,
            'location' => $assetDto->location,
            'status' => $assetDto->status,
            'user_id' => $userId,
            'criticality_level' => $assetDto->criticalityLevel
        ]);
    }
}
