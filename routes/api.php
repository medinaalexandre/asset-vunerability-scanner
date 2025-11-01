<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::prefix('v1')->middleware('auth')->group(function () {
    Route::prefix('assets')->name('assets.')->group(function () {
        Route::post('/', [AssetController::class, 'create'])->name('create');
        Route::post('/{assetId}/vulnerabilities', [AssetController::class, 'attachVulnerability']);
    });
});
