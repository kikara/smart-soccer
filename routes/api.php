<?php

use App\Http\Controllers\Game\GameController;
use App\Http\Controllers\Game\TableOccupationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserSingleAudioController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\UserEventAudioController;
use App\Http\Controllers\Statistics\StatisticController;
use App\Http\Controllers\GameSettingTemplateController;

Route::get('/games', [GameController::class, 'games']);
Route::post('/games', [GameController::class, 'store']);

Route::group(['prefix' => 'users'], function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/{user}', [UserController::class, 'show'])->whereNumber(['user']);
    Route::get('/{user}/games', [UserController::class, 'games'])->whereNumber(['user']);
    Route::get('/{user}/audios', [UserSingleAudioController::class, 'index'])->whereNumber(['user']);
    Route::get('/{user}/events/audios', [UserEventAudioController::class, 'index'])->whereNumber(['user']);
    Route::patch('/{user}/telegrams', [UserController::class, 'telegram']);
    Route::get('/stats', [StatisticController::class, 'index']);
    Route::get('/find', [UserController::class, 'find']);
    Route::get('/{user}/settings', [UserController::class, 'settings'])->whereNumber(['user']);
});

Route::get('/events', [EventController::class, 'index']);
Route::get('/events/params', [EventController::class, 'params']);
Route::get('/occupations/states', [TableOccupationController::class, 'state']);
Route::post('/occupations', [TableOccupationController::class, 'store']);
Route::get('/settings/{setting}', [GameSettingTemplateController::class, 'show'])->whereNumber(['setting']);
