<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
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
    Route::controller(UserController::class)->group(function () {
        Route::get("/users", "index");
        Route::get("/users/{id}", "show");
        Route::post("/users", "store");
        Route::put("/users/{id}", "update");
        Route::delete("/users/{id}", "destroy");
    });
});
