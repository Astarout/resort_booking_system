<?php

use App\Http\Controllers\Admin\PaymentController;

Route::controller(PaymentController::class)->middleware(['NoCache', '2fa', 'update_last_time_active'])->prefix('admin')->group(function() {

    Route::get('/payments', 'index')
        ->name('admin.payments')
        ->middleware('auth', 'role:admin');

    Route::post('/payments/store/{reservation_id}', 'storePayment')
        ->name('admin.payments.store')
        ->middleware('auth', 'role:admin');

    Route::get('/payments/transactions', 'viewTranscations')
        ->name('admin.payments.transactions')
        ->middleware('auth', 'role:admin');
});
