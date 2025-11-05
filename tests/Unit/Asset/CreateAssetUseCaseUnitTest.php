<?php

use App\Dto\AssetDto;
use App\Enums\AssetCriticalityLevelEnum;
use App\Models\Asset;
use App\Models\User;
use App\Repositories\EloquentAssetRepository;
use App\UseCases\Asset\CreateAssetUseCase;

it('can create an asset', function () {
    $createdAsset = new Asset();
    $loggedUser = new User();
    $loggedUser->id = 1;
    $repositoryMock = Mockery::mock(EloquentAssetRepository::class);
    $repositoryMock->shouldReceive('create')
        ->andReturn($createdAsset);

    $useCase = new CreateAssetUseCase($repositoryMock);

    $assetDto = new AssetDto(
        'foo',
        'bar',
        'device',
        'brazil',
        AssetCriticalityLevelEnum::NONE
    );
    expect(
        $useCase->execute($assetDto, $loggedUser)
    )->toBe($createdAsset);
});
