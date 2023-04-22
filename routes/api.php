<?php

use App\Http\Controllers\Bot\BotRequestController;
use App\Http\Controllers\Game\GameController;
use App\Http\Controllers\Game\TableOccupationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserSingleAudioController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\UserEventAudioController;


Route::group(['namespace' => 'Popup'], function () {
    Route::prefix('/popup')->group(function () {
        Route::post('/getStartForm', 'PopupController@getStartGameForm');
        Route::post('/addEventPopup', 'PopupController@addEventPopup');
    });
});

Route::group(['namespace' => 'Game'], function () {
    Route::post('/getMainLayout', 'GameController@getMainLayout');
    Route::post('/getMainInfo', 'GameController@getMainInfo');
    Route::post('/getNewTableLayout', 'GameController@getNewTableLayout');
    Route::post('/getGamersAudio', 'GameController@getGamersAudio');
});

Route::group(['prefix' => '/bot'], function () {
    Route::get('/settings/{settingId}', [BotRequestController::class, 'getGameSettingsById']);

    Route::post('/occupations', [TableOccupationController::class, 'store']);

    Route::get('/telegrams/{chatId}/user', [BotRequestController::class, 'getUserIdByTelegramChatId']);

//    Route::post('/getUserIdByTelegramChatId', 'BotRequestController@getUserIdByTelegramChatId');
//    Route::post('/setTableBusy', 'BotRequestController@setTableBusy');
//    Route::post('/getGameSettingsById', 'BotRequestController@getGameSettingsById');

    Route::post('/getGamers', 'BotRequestController@getGamers');
    Route::post('/saveTelegramData', 'BotRequestController@saveTelegramData');
    Route::post('/getGameSettings', 'BotRequestController@getGameSettings');
    Route::post('/checkUser', 'BotRequestController@checkTelegramUser');
    Route::post('/saveGameSettings', 'BotRequestController@saveGameSettings');
    Route::post('/isTableOccupied', 'BotRequestController@isTableOccupied');
});


Route::get('/games', [GameController::class, 'games']);
Route::post('/games', [GameController::class, 'store']);

Route::group(['prefix' => 'users'], function () {
    Route::get('/{user}', [UserController::class, 'show'])->whereNumber(['user']);
    Route::get('/{user}/games', [UserController::class, 'games'])->whereNumber(['user']);
    Route::get('/{user}/audios', [UserSingleAudioController::class, 'index'])->whereNumber(['user']);
    Route::get('/{user}/events/audios', [UserEventAudioController::class, 'index'])->whereNumber(['user']);
});

Route::get('/events', [EventController::class, 'index']);
Route::get('/events/params', [EventController::class, 'params']);
