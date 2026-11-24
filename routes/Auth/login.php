<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\Auth\ReactivationController;

Route::controller(LoginController::class)->middleware('NoCache')->group(function() {

    Route::get('/login', 'index')
        ->name('login')
        ->middleware('guest');

    Route::post('/login', 'login')
        ->name('login')
        ->middleware('guest', 'throttle:100,1');

});

Route::controller(TwoFactorController::class)->middleware('NoCache')->group(function() {

    Route::get('/2fa/send', 'showSendPage')
        ->name('2fa.send')
        ->middleware('auth');

    Route::post('/2fa/send', 'sendCode')
        ->name('2fa.send')
        ->middleware('auth');

    Route::get('/2fa/verify', 'showVerifyPage')
        ->name('2fa.verify')
        ->middleware('auth');

    Route::post('/2fa/verify', 'verifyCode')
        ->name('2fa.verify')
        ->middleware('auth');

    Route::post('/2fa/resend', 'resendCode')
        ->name('2fa.resend')
        ->middleware('auth');

});

Route::controller(ReactivationController::class)->middleware('NoCache')->group(function() {

    Route::get('/reactivate', 'show')
        ->name('account.reactivate');

    Route::post('/reactivate', 'reactivate')
        ->name('account.reactivate.submit');

});

