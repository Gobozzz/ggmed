<?php

use App\MoonShine\Controllers\EditorJs\UploadImageController;
use App\MoonShine\Controllers\Post\PostGenerateWithAIController;
use App\MoonShine\Controllers\Raffle\GetWinnerRaffleController;
use App\MoonShine\Controllers\Raffle\SelectWinnerRaffleController;
use App\MoonShine\Controllers\Transaction\ReplenishedAdminController;
use App\MoonShine\Controllers\Transaction\WriteOffAdminController;
use Illuminate\Support\Facades\Route;
use MoonShine\Laravel\Http\Middleware\Authenticate;

Route::middleware(['moonshine', Authenticate::class])->prefix('admin')->name('admin.')->group(function () {
    Route::prefix('raffles')->name('raffles.')->group(function () {
        Route::post('/{raffle}/select-winner', SelectWinnerRaffleController::class)->name('select-winner');
        Route::get('/{raffle}', GetWinnerRaffleController::class)->name('get');
    });
    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::post('/{user}/replenished', ReplenishedAdminController::class)->name('replenished');
        Route::post('/{user}/write-off', WriteOffAdminController::class)->name('write-off');
    });
    Route::prefix('posts')->name('posts.')->group(function () {
        Route::post('/generate-ai', PostGenerateWithAIController::class)->name('generate-ai');
    });
    Route::prefix('editor-js')->name('editorJs.')->group(function () {
        Route::post('/upload/image/file', [UploadImageController::class, 'byFile'])->name('upload.image.file');
        Route::post('/upload/image/url', [UploadImageController::class, 'byUrl'])->name('upload.image.url');
        Route::post('/delete/image', [UploadImageController::class, 'remove'])->name('remove.image');
    });
});
