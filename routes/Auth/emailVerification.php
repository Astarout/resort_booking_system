<?php

use App\Http\Controllers\Auth\EmailVerificationController;

Route::controller(EmailVerificationController::class)->middleware(['NoCache', '2fa'])->group(function() {

    Route::post('/email/verification-notification', 'sendEmailVerification')
        ->name('verification.send')
        ->middleware('auth', 'throttle:1,1');

    Route::get('/email/verify/{id}/{hash}', 'verify')
        ->name('verification.verify')
        ->middleware('auth', 'signed');

});
