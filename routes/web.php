<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'IndexController@index');

Auth::routes();
//Route::get('/goalTest', function () {
//    return view('goal');
//});

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
Route::group(['namespace' => 'Game'], function() {
    Route::get('/game', 'GameController@index')->name('game');
});

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['namespace' => 'Tournaments'], function() {
    Route::prefix('/tournaments')->group(function() {
        Route::get('/', 'TournamentController@all')->name('tournaments_all');
        Route::get('/add', 'TournamentController@createPage')->name('tournament_add');
        Route::post('/add', 'TournamentController@createPage')->name('tournament_add');
    });
});

Route::group(['namespace' => 'Debug'], function () {
    Route::get('/debug', 'DebugController@index')->name('debug');
});
