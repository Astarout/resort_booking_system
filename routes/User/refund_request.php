<?php

use App\Http\Controllers\User\RefundRequestController;

Route::controller(RefundRequestController::class)->middleware(['2fa', 'NoCache'])->prefix('user')->group(function (){

    Route::get('/refund_request', 'index')
        ->name('user.refund_request')
        ->middleware('auth', 'role:user');

    Route::post('/refund_request/store', 'store')
        ->name('user.refund_request.store')
        ->middleware('auth', 'role:user');
    
});
