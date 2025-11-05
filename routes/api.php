<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VulnerabilityController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::prefix('assets')->name('assets.')->group(function () {
        Route::post('/', [AssetController::class, 'create'])->name('create');
        Route::post('/{assetId}/vulnerabilities', [AssetController::class, 'attachVulnerability'])
            ->name('vulnerabilities.store');
    });

    Route::prefix('vulnerabilities')->name('vulnerabilities.')->group(function () {
        Route::post('/', [VulnerabilityController::class, 'create'])->name('create');
    });
});
