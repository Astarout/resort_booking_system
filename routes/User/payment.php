<?php

use App\Http\Controllers\User\PaymentController;

Route::controller(PaymentController::class)->middleware(['NoCache', '2fa', 'update_last_time_active'])->prefix('user')->group(function() {

    Route::get('/payments', 'index')
        ->name('user.payments')
        ->middleware('auth', 'role:user');

    Route::get('/payments/transactions', 'viewTransactions')
        ->name('user.payments.transactions')
        ->middleware('auth', 'role:user');

    Route::post('/payments/{reservation}/pay', 'pay')
        ->name('user.payments.pay')
        ->middleware('auth', 'role:user');

    Route::get('/payments/{reservation}/payment-success', 'success')
        ->name('user.payments.success')
        ->middleware('auth', 'role:user');

    Route::get('/payments/{reservation}/payment-failed', 'failed')
        ->name('user.payments.failed')
        ->middleware('auth', 'role:user');

});
