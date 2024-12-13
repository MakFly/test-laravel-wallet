<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\AccountController;
use App\Http\Controllers\Api\V1\LoginController;
use App\Http\Controllers\Api\V1\SendMoneyController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\RecurringTransfersController;
use Illuminate\Support\Facades\Route;

Route::post('/v1/login', LoginController::class)->middleware(['guest:sanctum', 'throttle:api.login']);

Route::middleware(['auth:sanctum', 'throttle:api'])->prefix('v1')->group(function () {
    Route::get('/account', AccountController::class);
    Route::post('/wallet/send-money', SendMoneyController::class);

    Route::get('/account/balance', [UserController::class, 'getBalanceUser']);

    // add two uri api for : getBalance - UpdateBalanceUser
    Route::get('/recurring-transfers', [RecurringTransfersController::class, 'list'])->name('api.recurring-transferts.list');
    Route::post('/recurring-transfers/create', [RecurringTransfersController::class, 'create'])->name('api.recurring-transferts.create');
    Route::delete('/recurring-transfers/{id}', [RecurringTransfersController::class, 'delete'])->name('api.recurring-transferts.delete');
});
