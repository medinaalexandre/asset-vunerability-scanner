<?php

use App\Models\Asset;
use App\Repositories\EloquentAssetRepository;
use App\UseCases\Asset\DeleteAssetUseCase;
use Illuminate\Database\Eloquent\ModelNotFoundException;

beforeEach(function () {
    $this->useCase = new DeleteAssetUseCase(new EloquentAssetRepository(new Asset()));
});

it('can delete an own asset', function () {
    $asset = Asset::factory()->create();
    $this->useCase->execute($asset->id, $asset->user_id);

    $asset->refresh();
    expect($asset->deleted_at)->not->toBeNull();
});

it('cannot delete an asset from another user', function () {
    $asset = Asset::factory()->create();
    $this->useCase->execute($asset->id, $asset->user_id + 1);
})->throws(ModelNotFoundException::class);
