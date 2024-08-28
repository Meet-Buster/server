<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::prefix('users')
    ->group(function () {
        Route::controller(UserController::class)
            ->group(function () {
                Route::patch('/{user:id}', 'update');
                Route::delete('/{user:id}', 'destroy');
            })->middleware('auth:sanctum');
    });
