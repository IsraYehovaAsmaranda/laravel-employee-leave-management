<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix("/auth")->controller(AuthController::class)->group(function () {
    Route::post("/login", "login");
    Route::middleware("auth:sanctum")->group(function () {
        Route::post("/logout", "logout");
        Route::post("/refresh-token", "refreshToken");
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
