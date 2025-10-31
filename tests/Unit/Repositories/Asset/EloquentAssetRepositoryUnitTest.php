<?php

use App\Dto\AssetDto;
use App\Models\Asset;
use App\Repositories\EloquentAssetRepository;
use Illuminate\Database\Eloquent\Builder;
use Mockery\MockInterface;

afterEach(function () {
    Mockery::close();
});

it('can persist new asset', function () {
    $dto = new AssetDto(
        name: 'foo',
        description: 'foo bar',
        deviceType: 'desktop',
        location: 'Brazil',
        status: 'active',
    );

    $expectedData = [
        'name' => $dto->name,
        'description' => $dto->description,
        'device_type' => $dto->deviceType,
        'location' => $dto->location,
        'status' => $dto->status,
        'user_id' => 1
    ];

    $builderMock = Mockery::mock(Builder::class);
    $builderMock->shouldReceive('create')
        ->with($expectedData)
        ->once()
        ->andReturn(new Asset());

    $eloquentMock = Mockery::mock(Asset::class, static fn (MockInterface $mock) =>
        $mock->shouldReceive('newQuery')->andReturn($builderMock)
    );

    (new EloquentAssetRepository($eloquentMock))->create($dto, 1);
});
