<?php

declare(strict_types=1);

use App\Http\Controllers\FeatController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CampaignSettingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CharacterClassController;
use App\Http\Controllers\Items\ItemController;
use App\Http\Controllers\Languages\LanguageController;
use App\Http\Controllers\Magic\MagicDomainController;
use App\Http\Controllers\Magic\MagicSchoolController;
use App\Http\Controllers\ReferenceController;
use App\Http\Controllers\Sources\SourceController;
use App\Http\Controllers\Spells\SpellController;

Route::prefix('api')->group(function () {
    Route::get('/campaign-setting/{slug}', [CampaignSettingController::class, 'get']);
    Route::get('/campaign-settings', [CampaignSettingController::class, 'index']);

    Route::get('/category/{slug}', [CategoryController::class, 'get']);
    Route::get('/categories', [CategoryController::class, 'index']);

    Route::get('/class/{slug}', [CharacterClassController::class, 'get']);
    Route::get('/classes', [CharacterClassController::class, 'index']);

    Route::get('/domains', [MagicDomainController::class, 'index']);

    Route::get('/feat/{slug}', [FeatController::class, 'get']);
    Route::get('/features', [FeatController::class, 'index']);

    Route::get('/item/{slug}', [ItemController::class, 'get']);
    Route::get('/items', [ItemController::class, 'index']);

    Route::get('/language/{slug}', [LanguageController::class, 'get']);
    Route::get('/languages', [LanguageController::class, 'index']);

    Route::get('/references', [ReferenceController::class, 'index']);

    Route::get('/school/{slug}', [MagicSchoolController::class, 'get']);
    Route::get('/schools', [MagicSchoolController::class, 'index']);

    Route::get('/source/{slug}', [SourceController::class, 'get']);
    Route::get('/sources', [SourceController::class, 'index']);

    Route::geT('/spell/{slug}', [SpellController::class, 'get']);
    Route::get('/spells', [SpellController::class, 'index']);
});
