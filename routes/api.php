<?php

use App\Http\Controllers\Bot\BotRequestController;
use App\Http\Controllers\Game\GameController;
use App\Http\Controllers\Game\TableOccupationController;
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

Route::group(['prefix' => '/socket'], function () {
    Route::post('/game', [GameController::class, 'store']);
});
