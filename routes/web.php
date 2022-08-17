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
    Route::get('/profile', 'UserProfileController@index')->name('profile');
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
