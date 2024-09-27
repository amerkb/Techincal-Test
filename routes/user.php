<?php

use App\Http\Controllers\User\OrderController;
use App\Statuses\UserStatus;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::middleware(['CheckAbilities:'.UserStatus::USER])->prefix('User')->group(function () {
    Route::post('makeOrder', [OrderController::class, 'makeOrder']);
});
