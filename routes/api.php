<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VulnerabilityController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::prefix('assets')->name('assets.')->group(function () {
        Route::post('/', [AssetController::class, 'create'])->name('create');
        Route::prefix('/{assetId}')->group(function () {
            Route::get('/', [AssetController::class, 'show'])->name('show');
            Route::patch('/', [AssetController::class, 'update'])->name('update');
            Route::delete('/', [AssetController::class, 'delete'])->name('delete');
            Route::get('/risk', [AssetController::class, 'calculateRisk'])->name('calculate-risk');
            Route::post('/vulnerabilities', [AssetController::class, 'attachVulnerability'])
                ->name('vulnerabilities.store');
            Route::delete('/vulnerabilities/{cveId}', [AssetController::class, 'detachVulnerability'])
                ->name('vulnerabilities.store');
        });
    });

    Route::prefix('vulnerabilities')->name('vulnerabilities.')->group(function () {
        Route::post('/', [VulnerabilityController::class, 'create'])->name('create');
    });
});
