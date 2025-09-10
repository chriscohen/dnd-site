<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Items\ItemController;
use App\Http\Controllers\Magic\MagicDomainController;
use App\Http\Controllers\Magic\MagicSchoolController;
use App\Http\Controllers\ReferenceController;

Route::prefix('api')->group(function () {
    Route::get('/domains', [MagicDomainController::class, 'index']);

    Route::get('/item/{slug}', [ItemController::class, 'get']);
    Route::get('/items', [ItemController::class, 'index']);

    Route::get('/references', [ReferenceController::class, 'index']);

    Route::get('/schools', [MagicSchoolController::class, 'index']);
});
