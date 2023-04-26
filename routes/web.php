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
    Route::get('/{any}', fn () => view('index'))->where('any', '.*')->middleware('auth');
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
