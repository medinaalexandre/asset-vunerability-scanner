<?php

use App\Dto\AssetDto;
use App\Enums\AssetCriticalityLevelEnum;
use App\Models\Asset;
use App\Models\User;
use App\Repositories\EloquentAssetRepository;
use App\UseCases\Asset\CreateAssetUseCase;

it('can create an asset', function () {
    $user = User::factory()->create();
    $assetDto = new AssetDto(
        name: 'foo',
        description: null,
        deviceType: 'bar',
        location: 'Brazil',
        criticalityLevel: AssetCriticalityLevelEnum::CRITICAL
    );

    $useCase = new CreateAssetUseCase(new EloquentAssetRepository(new Asset()));
    $asset = $useCase->execute($assetDto, $user);
    expect($asset)->toBeInstanceOf(Asset::class)
        ->id->toBeInt()
        ->name->toBe('foo')
        ->device_type->toBe('bar')
        ->location->toBe('Brazil')
        ->status->toBe('NEED VERIFICATION')
        ->criticality_level->toBe(AssetCriticalityLevelEnum::CRITICAL);
});
