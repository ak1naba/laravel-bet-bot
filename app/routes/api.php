<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewItemController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\WriterAccess;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminAccess;
use App\Http\Middleware\NewItemOwning;

Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/register', [AuthController::class, 'register']);


Route::group(['middleware'=>'auth:sanctum'], function (){
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::middleware([AdminAccess::class])->prefix('/admin')->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'create']);
        Route::put('/users/{user}', [UserController::class, 'update']);
        Route::delete('/users/{user}', [UserController::class, 'destroy']);
    });

    Route::middleware([WriterAccess::class])->prefix('/posts')->group(function () {
        Route::get('/', [NewItemController::class, 'index']);
        Route::post('/', [NewItemController::class, 'store']);
        Route::middleware([NewItemOwning::class])->group(function () {
            Route::get('/{new_item}', [NewItemController::class, 'show']);
            Route::put('/{new_item}', [NewItemController::class, 'update']);
            Route::delete('/{new_item}', [NewItemController::class, 'update']);
        });
    });

    Route::get('/news', [NewItemController::class, 'getNewItems']);
    Route::get('/news/{new_item}', [NewItemController::class, 'getNewItem']);
});
