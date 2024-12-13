<?php

declare(strict_types=1);

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RecurringTransfersController;
use App\Http\Controllers\SendMoneyController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::post('/send-money', [SendMoneyController::class, '__invoke'])->name('send-money');

    Route::get('/recurring-transfers', [RecurringTransfersController::class, 'list'])->name('recurring-transfers.list');
    Route::post('/recurring-transfers/create', [RecurringTransfersController::class, 'create'])->name('recurring-transfers.create');
    // Route::delete('/recurring-transfers/delete', [RecurringTransfersController::class, 'delete'])->name('recurring-transfers.delete');
});

require __DIR__.'/auth.php';
