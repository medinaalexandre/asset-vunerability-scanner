<?php

use App\Dto\AssetDto;
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

    expect(
        $useCase->execute(new AssetDto('foo', 'bar', 'device', 'brazil'), $loggedUser)
    )->toBe($createdAsset);
});
