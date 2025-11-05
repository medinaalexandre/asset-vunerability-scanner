<?php

namespace App\UseCases\Asset;

use App\Models\Asset;
use App\Repositories\Contracts\AssetRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ShowAssetUseCase
{
    public function __construct(
        private readonly AssetRepositoryInterface $assetRepository,
    ) {
    }

    public function execute(int $assetId, int $userId): Asset
    {
        $asset = $this->assetRepository->findByUser($assetId, $userId);

        if (is_null($asset)) {
            throw new ModelNotFoundException("Asset with ID $assetId not found");
        }

        return $asset;
    }
}
