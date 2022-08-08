<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'Popup'], function () {
    Route::prefix('/popup')->group(function () {
        Route::post('/getStartForm', 'PopupController@getStartGameForm');
    });
});

Route::group(['namespace' => 'Game'], function () {
    Route::post('/getMainLayout', 'GameController@getMainLayout');
    Route::post('/getMainInfo', 'GameController@getMainInfo');
    Route::post('/saveGame', 'GameController@saveGame');
});

Route::group(['namespace' => 'Bot'], function () {
    Route::prefix('/bot')->group(function () {
        Route::post('/getGamers', 'BotRequestController@getGamers');
        Route::post('/saveTelegramData', 'BotRequestController@saveTelegramData');
        Route::post('/getGameSettings', 'BotRequestController@getGameSettings');
        Route::post('/checkUser', 'BotRequestController@checkTelegramUser');
        Route::post('/saveGameSettings', 'BotRequestController@saveGameSettings');
        Route::post('/getGameSettingsById', 'BotRequestController@getGameSettingsById');
        Route::post('/getUserIdByTelegramChatId', 'BotRequestController@getUserIdByTelegramChatId');
        Route::post('/setTableBusy', 'BotRequestController@setTableBusy');
        Route::post('/isTableOccupied', 'BotRequestController@isTableOccupied');
    });
});
