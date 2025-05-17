<?php

use App\Http\Controllers\AuditController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IntervieweeController;
use App\Http\Controllers\IntervieweeTaskController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
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

    Route::prefix("/tasks")->controller(TaskController::class)->group(function () {
        Route::get("", "index");
        Route::get("/{task}", "show");
        Route::post("", "store");
        Route::put("/{task}", "update");
        Route::delete("/{task}", "destroy");
        Route::get("/all", "indexNoPagination");
        Route::get("/{task}/attachment", "downloadAttachment");
    });

    Route::prefix("/interviewees")->controller(IntervieweeController::class)->group(function () {
        Route::get("", "index");
        Route::get("/{interviewee}", "show");
        Route::post("", "store");
        Route::put("/{interviewee}", "update");
        Route::delete("/{interviewee}", "destroy");
        Route::get("/all", "indexNoPagination");
    });

    Route::prefix("/interviewee-tasks")->controller(IntervieweeTaskController::class)->group(function () {
        Route::get("", "index");
        Route::get("/{intervieweeTask}", "show");
        Route::post("", "store");
        Route::put("/{intervieweeTask}", "update");
        Route::delete("/{intervieweeTask}", "destroy");
    });

    Route::prefix("audits")->controller(AuditController::class)->group(function () {
        Route::get("", "index");
        Route::get("/users", "users");
        Route::get("/users/{user}", "userById");
        Route::get("/roles", "roles");
        Route::get("/roles/{role}", "roleById");
    });
});
