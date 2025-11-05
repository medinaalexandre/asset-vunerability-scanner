<?php

namespace App\UseCases\Asset;

use App\Repositories\Contracts\AssetRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DeleteAssetUseCase
{
    public function __construct(
        protected readonly AssetRepositoryInterface $assetRepository,
    ) {

    }

    public function execute(int $assetId, int $userId): void
    {
        $asset = $this->assetRepository->findByUser($userId, $assetId);

        if (is_null($asset)) {
            throw new ModelNotFoundException("Asset with ID $assetId not found");
        }

        $this->assetRepository->delete($assetId);
    }
}
