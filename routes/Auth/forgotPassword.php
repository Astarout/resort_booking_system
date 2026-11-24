<?php

use App\Http\Controllers\Auth\ForgotPasswordController;

Route::controller(ForgotPasswordController::class)->middleware('NoCache')->prefix('password')->group(function() {

    Route::get('/reset/form', 'showForgotPasswordRequestForm')
        ->name('password.request')
        ->middleware('guest');

    Route::post('/reset/send', 'sendPasswordResetLink')
        ->name('password.email')
        //->middleware('guest', 'throttle:5,1');
        ->middleware('guest');

    Route::get('/reset/{token}', 'showResetForm')
        ->name('password.reset')
        ->middleware('guest');

    Route::post('/reset', 'reset')
        ->name('password.update')
        ->middleware('guest');
});
