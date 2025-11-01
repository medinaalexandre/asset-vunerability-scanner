<?php

namespace App\Repositories;

use App\Dto\AssetDto;
use App\Models\Asset;
use App\Repositories\Contracts\AssetRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class EloquentAssetRepository implements AssetRepositoryInterface
{
    public function __construct(protected Asset $model)
    {
    }

    protected function newQuery(): Builder
    {
        return $this->model->newQuery();
    }

    public function create(AssetDto $assetDto, int $userId): Asset
    {
        /** @var Asset $newAsset */
        $newAsset = $this->newQuery()->create([
            'name' => $assetDto->name,
            'description' => $assetDto->description,
            'device_type' => $assetDto->deviceType,
            'location' => $assetDto->location,
            'status' => $assetDto->status,
            'user_id' => $userId,
        ]);

        return $newAsset;
    }

    public function find(int $id): ?Asset
    {
        return $this->newQuery()->where('id', $id)->first();
    }

    public function attachVulnerability(int $assetId, int $vulnerabilityId): void
    {
        $this->find($assetId)?->vulnerabilities()->attach($vulnerabilityId);
    }
}
