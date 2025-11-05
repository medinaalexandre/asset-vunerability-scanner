<?php

namespace App\UseCases\Asset;

use App\Dto\AssetDto;
use App\Models\Asset;
use App\Repositories\Contracts\AssetRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UpdateAssetUseCase
{
    public function __construct(
        private readonly AssetRepositoryInterface $assetRepository,
    ) {
    }

    public function execute(int $assetId, int $userId, AssetDto $dto): Asset
    {
        $asset = $this->assetRepository->findByUser($assetId, $userId);

        if (is_null($asset)) {
            throw new ModelNotFoundException("Asset with ID $assetId not found");
        }


        $this->assetRepository->update($assetId, $dto);
        return $this->assetRepository->find($assetId);
    }
}
