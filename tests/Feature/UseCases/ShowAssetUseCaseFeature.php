<?php

use App\Models\Asset;
use App\Repositories\EloquentAssetRepository;
use App\UseCases\Asset\ShowAssetUseCase;
use Illuminate\Database\Eloquent\ModelNotFoundException;

beforeEach(function () {
    $this->useCase = new ShowAssetUseCase(new EloquentAssetRepository(new Asset()));

});

it('can see an asset', function () {
   $asset = Asset::factory()->create();
   $receivedAsset = $this->useCase->execute($asset->id, $asset->user_id);
   expect($receivedAsset->id)->toBe($asset->id);
});

it('cannot see an asset from another user', function () {
    $asset = Asset::factory()->create();
    $this->useCase->execute($asset->id, $asset->user_id + 1);
})->throws(ModelNotFoundException::class);
