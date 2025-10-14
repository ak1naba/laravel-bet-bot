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
});
