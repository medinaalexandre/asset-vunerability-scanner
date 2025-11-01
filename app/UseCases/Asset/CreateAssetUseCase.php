<?php

namespace App\UseCases\Asset;

use App\Dto\AssetDto;
use App\Models\Asset;
use App\Models\User;
use App\Repositories\EloquentAssetRepository;

final readonly class CreateAssetUseCase
{
    public function __construct(
        protected EloquentAssetRepository $repository
    ) {

    }
    public function execute(AssetDto $assetDto, User $user): Asset
    {
        $assetDto->status = 'NEED VERIFICATION';
        return $this->repository->create($assetDto, $user->id);
    }
}
