<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ListController;
use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\MatchController;

Route::controller(UserController::class)->prefix('user')->group(function () {
    Route::post('register/send-code', 'registerSendCode');
    Route::post('register/check', 'registerCheck');
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('late/auth', 'lateAuth');
    Route::post('password/reset/send-code','passwordResetSendCode');
    Route::post('password/reset/check-code', 'passwordResetCheckCode');
    Route::post('password/reset', 'passwordReset');
    Route::post('edit', 'edit')->middleware('auth:sanctum');
    Route::post('delete', 'delete')->middleware('auth:sanctum');
});

Route::controller(ListController::class)->prefix('list')->group(function (){
   Route::get('cats', 'catsList');
   Route::get('cities', 'citiesList');
});

Route::controller(GameController::class)->prefix('game')->group(function () {
    Route::post('store', 'store')->middleware('auth:sanctum');
    Route::get('show/round', 'showRound');
    Route::get('round/solo', 'roundSoloShow');
    Route::post('store/round', 'storeRound');
    Route::post('end/{gameId}', 'endGame');
});




