<?php

namespace App\Repositories\Contracts;

use App\Dto\AssetDto;
use App\Models\Asset;

interface AssetRepositoryInterface
{
    public function create(AssetDto $assetDto, int $userId): Asset;
}
