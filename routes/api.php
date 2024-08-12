<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;


Route::prefix('/v1')
    ->group(function () {
        Route::prefix('auth')->name('auth.')
            ->group(function () {
                Route::post('login', LoginController::class)->name('login')->middleware('guest:sanctum');
                Route::post('register', RegisterController::class)->name('register')->middleware('guest:sanctum');
                Route::post('/logout', LogoutController::class)->middleware('auth:sanctum')->name('logout');
            });

        Route::middleware('auth:sanctum')
            ->group(function () {
                Route::apiResource('products', ProductController::class);
            });
    });
