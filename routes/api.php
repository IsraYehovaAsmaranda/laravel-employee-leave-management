<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
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
    Route::prefix("/users")->controller(UserController::class)->group(function () {
        Route::get("", "index");
        Route::get("/{id}", "show");
        Route::post("", "store");
        Route::put("/{id}", "update");
        Route::delete("/{id}", "destroy");
        Route::get("/all", "indexNoPagination");
    });

    Route::prefix("/roles")->controller(RoleController::class)->group(function () {
        Route::get("", "index");
        Route::get("/{role}", "show");
        Route::post("", "store");
        Route::put("/{role}", "update");
        Route::delete("/{role}", "destroy");
        Route::get("/all", "indexNoPagination");
    });

    Route::prefix("/permissions")->controller(PermissionController::class)->group(function () {
        Route::get("/permissions", "index");
    });
});
