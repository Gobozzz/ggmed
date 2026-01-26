<?php

use App\MoonShine\Controllers\Raffle\GetWinnerRaffleController;
use App\MoonShine\Controllers\Raffle\SelectWinnerRaffleController;
use Illuminate\Support\Facades\Route;

Route::middleware('moonshine')->prefix('admin')->name('admin.')->group(function () {
    Route::prefix('raffles')->name('raffles.')->group(function () {
        Route::post('/raffles/{raffle}/select-winner', SelectWinnerRaffleController::class)->name('select-winner');
        Route::get('/raffles/{raffle}', GetWinnerRaffleController::class)->name('get');
    });
});
