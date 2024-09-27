<?php

use App\Http\Controllers\Admin\ProductController;
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
Route::middleware(['CheckAbilities:'.UserStatus::ADMIN])->prefix('Admin')->group(function () {

    Route::apiResource('product', ProductController::class)->except('show', 'index');

});
Route::get('product', [ProductController::class, 'index'])->middleware(['CheckAbilities:'.UserStatus::USER.','.UserStatus::ADMIN]);
