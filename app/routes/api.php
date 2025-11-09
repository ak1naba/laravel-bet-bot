<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventParticipantController;
use App\Http\Controllers\NewItemController;
use App\Http\Controllers\SportController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\OddController;
use App\Http\Controllers\BetController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TelegramController;
use Illuminate\Support\Facades\Route;


// Telegram action
Route::prefix('/telegram')
    ->group(function(){
        Route::post('/webhook', [TelegramController::class, 'webhook']);
});

// Auth
Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/register', [AuthController::class, 'register']);

// Authenticated
Route::group(['middleware'=>'auth:sanctum'], function (){
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('/user')
        ->group(function () {
            Route::get('/me', [UserController::class, 'getAuthenticatedUser']);
    });

    // User-facing bets
    Route::prefix('/bet')
        ->group(function () {
            Route::get('/', [BetController::class, 'index'])->name('user.bet.index');
            Route::get('/{bet}', [BetController::class, 'show'])->name('user.bet.show');
            Route::post('/', [BetController::class, 'store'])->name('user.bet.store');
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

            Route::prefix('/event')
                ->group(function () {
                    Route::get('/', [EventController::class, 'index'])->name('event.index');
                    Route::get('/{event}', [EventController::class, 'show'])->name('event.show');
                    Route::post('/', [EventController::class, 'store'])->name('event.store');
                    Route::put('/{event}', [EventController::class, 'update'])->name('event.update');
                    Route::delete('/{event}', [EventController::class, 'delete'])->name('event.delete');
                    Route::delete('/force/{event}', [EventController::class, 'forceDelete'])->name('event.forceDelete');
                    Route::post('/restore/{event}', [EventController::class, 'restore'])->name('event.restore');

                    Route::prefix('{event}/participant')
                        ->group(function () {
                                Route::get('/', [EventParticipantController::class, 'index'])->name('participant.index');
                                Route::get('/{participant}', [EventParticipantController::class, 'show'])->name('participant.show');
                                Route::post('/', [EventParticipantController::class, 'store'])->name('participant.store');
                                Route::put('/{participant}', [EventParticipantController::class, 'update'])->name('participant.update');
                                Route::delete('/{participant}', [EventParticipantController::class, 'delete'])->name('participant.delete');
                                Route::delete('/force/{participant}', [EventParticipantController::class, 'forceDelete'])->name('participant.forceDelete');
                                Route::post('/restore/{participant}', [EventParticipantController::class, 'restore'])->name('participant.restore');
                        });

                    Route::prefix('{event}/market')
                        ->group(function () {
                            Route::get('/', [MarketController::class, 'index'])->name('market.index');
                            Route::get('/{market}', [MarketController::class, 'show'])->name('market.show');
                            Route::post('/', [MarketController::class, 'store'])->name('market.store');
                            Route::put('/{market}', [MarketController::class, 'update'])->name('market.update');
                            Route::delete('/{market}', [MarketController::class, 'delete'])->name('market.delete');
                            Route::delete('/force/{market}', [MarketController::class, 'forceDelete'])->name('market.forceDelete');
                            Route::post('/restore/{market}', [MarketController::class, 'restore'])->name('market.restore');
                        });

                    Route::prefix('/market')
                        ->group(function () {
                            Route::prefix('{market}/odd')
                                ->group(function () {
                                    Route::get('/', [OddController::class, 'index'])->name('odd.index');
                                    Route::get('/{odd}', [OddController::class, 'show'])->name('odd.show');
                                    Route::post('/', [OddController::class, 'store'])->name('odd.store');
                                    Route::put('/{odd}', [OddController::class, 'update'])->name('odd.update');
                                    Route::delete('/{odd}', [OddController::class, 'delete'])->name('odd.delete');
                                    Route::delete('/force/{odd}', [OddController::class, 'forceDelete'])->name('odd.forceDelete');
                                    Route::post('/restore/{odd}', [OddController::class, 'restore'])->name('odd.restore');
                                });
                        });

                    Route::prefix('/bet')
                        ->group(function () {
                            Route::get('/', [BetController::class, 'index'])->name('bet.index');
                            Route::get('/{bet}', [BetController::class, 'show'])->name('bet.show');
                            Route::post('/', [BetController::class, 'store'])->name('bet.store');
                            Route::put('/{bet}', [BetController::class, 'update'])->name('bet.update');
                            Route::delete('/{bet}', [BetController::class, 'delete'])->name('bet.delete');
                            Route::delete('/force/{bet}', [BetController::class, 'forceDelete'])->name('bet.forceDelete');
                            Route::post('/restore/{bet}', [BetController::class, 'restore'])->name('bet.restore');
                        });
                });

    });

});

