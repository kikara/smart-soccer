<?php

use App\Http\Controllers\Debug\DebugController;
use App\Http\Controllers\Game\GameController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserSingleAudioController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserEventAudioController;

Auth::routes();

Route::get('/', [GameController::class, 'index'])->name('index');

Route::group(['prefix' => 'info'], function () {
    Route::get('/{any}', fn () => view('index'))->where('any', '.*');
});

Route::get('/debug', [DebugController::class, 'index'])->name('debug');

Route::group(['prefix' => 'users', 'middleware' => 'auth'], function () {
    Route::get('/', [UserController::class, 'current']);
    Route::post('/avatar', [UserController::class, 'updatePhoto']);
    Route::post('/audios', [UserSingleAudioController::class, 'store']);
    Route::delete('/audios/{audio}', [UserSingleAudioController::class, 'destroy']);
    Route::post('/events/audios', [UserEventAudioController::class, 'store']);
    Route::delete('/events/audios/{audio}', [UserEventAudioController::class, 'destroy'])->whereNumber(['audio']);
});




//Route::get('/games', [GameController::class, 'games']);
//Route::get('/users/{user}', [UserController::class, 'show'])->whereNumber('user');

//Route::group(['namespace' => 'UserProfile'], function () {
//    Route::prefix('/profile')->group(function () {
//        Route::get('/', 'UserProfileController@index')->name("profile");
//        Route::get('/sound_settings', 'UserProfileController@sounds')->name('sound_settings');
//        Route::post('/save_single_sound', 'UserProfileController@saveGoalSound');
//        Route::post('/delete_single_audio', 'UserProfileController@deleteSingleAudio');
//        Route::post('/save_event', 'UserProfileController@saveEvent');
//        Route::post('/event_delete', 'UserProfileController@eventDelete');
//    });
//    Route::post('/saveProfile', 'UserProfileController@saveProfile');
//});

//Route::group(['prefix' => '/game'], function () {
//    Route::get('/layout', [GameController::class, 'layout']);
//});

//Route::group(['namespace' => 'Tournaments'], function() {
//    Route::prefix('/tournaments')->group(function() {
//        Route::get('/', 'TournamentController@all')->name('tournaments_all');
//        Route::get('/add', 'TournamentController@createPage')->name('tournament_add');
//        Route::post('/add', 'TournamentController@createPage')->name('tournament_add');
//    });
//});

//Route::group(['namespace' => 'Statistics'], function () {
//    Route::get('/statistics', 'StatisticController@index')->name('statistics');
//});

//Route::get('/calculateRating', function () {
//    \App\Taskers\UserRatingCalculator::calculate();
//    echo 'done';
//});
