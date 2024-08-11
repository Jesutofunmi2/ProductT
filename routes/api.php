<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use Illuminate\Support\Facades\Route;


Route::prefix('/v1')
    ->group(function () {
        Route::prefix('auth')->name('auth.')
            ->group(function () {
                Route::post('login', LoginController::class)->name('login');
                Route::post('register', RegisterController::class)->name('register');
            });

            Route::middleware('auth:sanctum')
            ->group(function () {

            });
    });

// Route::name('auth.')->prefix('auth')->group(function () {
//     Route::post('login', LoginController::class)->name('login');
//     Route::post('logout', LogoutController::class)->name('logout');
//     Route::post('register', RegisterController::class)->name('register');
//     Route::post('reset-password', ResetPasswordController::class)->name('reset-password');
//     Route::post('forgot-password', ForgotPasswordController::class);
// });