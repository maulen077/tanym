<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ListController;
use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\MatchController;

Route::controller(UserController::class)->prefix('user')->group(function () {
    Route::post('register/send-code', 'registerSendCode')->middleware('auth:sanctum');
    Route::post('register/check', 'registerCheck')->middleware('auth:sanctum');
    Route::post('register', 'register')->middleware('auth:sanctum');
    Route::post('login', 'login')->middleware('auth:sanctum');
    Route::post('late/auth', 'lateAuth')->middleware('auth:sanctum');
    Route::post('password/reset/send-code','passwordResetSendCode')->middleware('auth:sanctum');
    Route::post('password/reset/check-code', 'passwordResetCheckCode')->middleware('auth:sanctum');
    Route::post('password/reset', 'passwordReset')->middleware('auth:sanctum');
    Route::post('edit', 'edit')->middleware('auth:sanctum');
    Route::post('delete', 'delete')->middleware('auth:sanctum');
});

Route::controller(ListController::class)->prefix('list')->group(function (){
   Route::get('cats', 'catsList')->middleware('auth:sanctum');
   Route::get('cities', 'citiesList')->middleware('auth:sanctum');
});

Route::controller(GameController::class)->prefix('game')->group(function () {
    Route::post('store', 'store')->middleware('auth:sanctum');
    Route::get('show/round', 'showRound')->middleware('auth:sanctum');
    Route::get('round/solo', 'roundSoloShow')->middleware('auth:sanctum');
    Route::post('store/round', 'storeRound')->middleware('auth:sanctum');
    Route::get('refresh', 'refreshGame')->middleware('auth:sanctum');
    Route::post('end/{gameId}', 'endGame')->middleware('auth:sanctum');
});





