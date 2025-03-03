<?php

use App\Http\Controllers\ReviewController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckStoreMiddleware;
use Illuminate\Support\Facades\Route;

Route::post("/login", [UserController::class, "login"]);
Route::post("/register", [UserController::class, "register"]);
Route::get("/logout", [UserController::class, "logout"]);


Route::prefix("/stores")->group(function() {
    Route::get("/", [StoreController::class,"index"]);
    Route::get("/{storeId}", [StoreController::class, "show"]);

    Route::prefix("/{storeId}/reviews")->middleware(["auth:api", CheckStoreMiddleware::class])->group(function() {
        Route::post("/", [ReviewController::class, "create"]);
        Route::put("/", [ReviewController::class, "update"]);
        Route::delete("/", [ReviewController::class, "destroy"]);
    });
});



