<?php

use App\MoonShine\Controllers\Raffle\GetWinnerRaffleController;
use App\MoonShine\Controllers\Raffle\SelectWinnerRaffleController;
use App\MoonShine\Controllers\Transaction\ReplenishedAdminController;
use App\MoonShine\Controllers\Transaction\WriteOffAdminController;
use Illuminate\Support\Facades\Route;

Route::middleware('moonshine')->prefix('admin')->name('admin.')->group(function () {
    Route::prefix('raffles')->name('raffles.')->group(function () {
        Route::post('/{raffle}/select-winner', SelectWinnerRaffleController::class)->name('select-winner');
        Route::get('/{raffle}', GetWinnerRaffleController::class)->name('get');
    });
    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::post('/transactions/{user}/replenished', ReplenishedAdminController::class)->name('replenished');
        Route::post('/transactions/{user}/write-off', WriteOffAdminController::class)->name('write-off');
    });
});
