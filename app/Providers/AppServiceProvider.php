<?php

namespace App\Providers;

use App\Repositories\Contracts\AssetRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\VulnerabilityRepositoryInterface;
use App\Repositories\EloquentAssetRepository;
use App\Repositories\EloquentUserRepository;
use App\Repositories\EloquentVulnerabilityRepository;
use App\Services\Clients\NvdGuzzleClient;
use App\Services\Contracts\EnrichCveDetailsServiceInterface;
use App\Services\NvdApiService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            EloquentUserRepository::class
        );

        $this->app->bind(
            VulnerabilityRepositoryInterface::class,
            EloquentVulnerabilityRepository::class
        );

        $this->app->bind(
            AssetRepositoryInterface::class,
            EloquentAssetRepository::class
        );

        $this->app->bind(
            EnrichCveDetailsServiceInterface::class,
            NvdApiService::class
        );

        $this->app->singleton(NvdGuzzleClient::class, fn() => new NvdGuzzleClient([
                'base_uri' => config('nvd.base_url')
            ]
        ));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
