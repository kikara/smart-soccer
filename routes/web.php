<?php

use App\Http\Controllers\Debug\DebugController;
use App\Http\Controllers\Game\GameController;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', [GameController::class, 'index'])->name('index');
Route::get('/games', [GameController::class, 'games']);


Route::group(['namespace' => 'UserProfile'], function () {
    Route::prefix('/profile')->group(function () {
        Route::get('/', 'UserProfileController@index')->name("profile");
        Route::get('/sound_settings', 'UserProfileController@sounds')->name('sound_settings');
        Route::post('/save_single_sound', 'UserProfileController@saveGoalSound');
        Route::post('/delete_single_audio', 'UserProfileController@deleteSingleAudio');
        Route::post('/save_event', 'UserProfileController@saveEvent');
        Route::post('/event_delete', 'UserProfileController@eventDelete');
    });
    Route::post('/saveProfile', 'UserProfileController@saveProfile');
});

Route::group(['prefix' => '/game'], function () {
    Route::get('/layout', [GameController::class, 'layout']);
});

Route::group(['namespace' => 'Tournaments'], function() {
    Route::prefix('/tournaments')->group(function() {
        Route::get('/', 'TournamentController@all')->name('tournaments_all');
        Route::get('/add', 'TournamentController@createPage')->name('tournament_add');
        Route::post('/add', 'TournamentController@createPage')->name('tournament_add');
    });
});


Route::get('/debug', [DebugController::class, 'index'])->name('debug');


Route::group(['namespace' => 'Statistics'], function () {
    Route::get('/statistics', 'StatisticController@index')->name('statistics');
});

Route::get('/calculateRating', function () {
    \App\Taskers\UserRatingCalculator::calculate();
    echo 'done';
});
