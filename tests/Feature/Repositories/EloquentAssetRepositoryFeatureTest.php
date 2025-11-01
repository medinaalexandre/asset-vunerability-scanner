<?php

use App\Dto\AssetDto;
use App\Models\Asset;
use App\Models\User;
use App\Repositories\EloquentAssetRepository;

it('can create asset', function () {
    $repo = new EloquentAssetRepository(new Asset());
    $dto = new AssetDto(
        name: 'foo',
        description: 'foo bar',
        deviceType: 'desktop',
        location: 'Brazil',
        status: 'active',
    );

    $user = User::factory()->create();

    $createdAsset = $repo->create($dto, $user->getKey());

    expect($createdAsset)
        ->id->toBeInt()
        ->name->toBe($dto->name)
        ->description->toBe($dto->description)
        ->name->toBe($dto->name)
        ->description->toBe($dto->description)
        ->location->toBe($dto->location)
        ->status->toBe($dto->status);

    $this->assertDatabaseHas('assets', ['id' => $createdAsset->id]);
});
