<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewItemController;
use App\Http\Controllers\SportController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\WriterAccess;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminAccess;
use App\Http\Middleware\NewItemOwning;

Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/register', [AuthController::class, 'register']);


Route::group(['middleware'=>'auth:sanctum'], function (){
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('/user')
        ->group(function () {
            Route::get('/me', [UserController::class, 'getAuthenticatedUser']);
    });


    Route::prefix('/admin')
        ->middleware('admin')
        ->group(function () {

        Route::prefix('/sport')
            ->group(function () {
                Route::get('/', [SportController::class, 'index'])->name('sport.index');
                Route::get('/{sport}', [SportController::class, 'show'])->name('sport.show');
                Route::post('/', [SportController::class, 'store'])->name('sport.store');
                Route::put('/{sport}', [SportController::class, 'update'])->name('sport.update');
                Route::delete('/{sport}', [SportController::class, 'delete'])->name('sport.delete');
                Route::delete('/force/{sport}', [SportController::class, 'forceDelete'])->name('sport.forceDelete');
                Route::post('/restore/{sport}', [SportController::class, 'restore'])->name('sport.restore');
            });

        Route::prefix('/team')
            ->group(function () {
                Route::get('/', [TeamController::class, 'index'])->name('team.index');
                Route::get('/{team}', [TeamController::class, 'show'])->name('team.show');
                Route::post('/', [TeamController::class, 'store'])->name('team.store');
                Route::put('/{team}', [TeamController::class, 'update'])->name('team.update');
                Route::delete('/{team}', [TeamController::class, 'delete'])->name('team.delete');
                Route::delete('/force/{team}', [TeamController::class, 'forceDelete'])->name('team.forceDelete');
                Route::post('/restore/{team}', [TeamController::class, 'restore'])->name('team.restore');
            });

    });

});

Route::prefix('/telegram')
    ->group(function(){
        Route::post('/telegram/webhook', [TelegramController::class, 'webhook']);
});