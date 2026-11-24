<?php

use App\Http\Controllers\Admin\RefundRequestController;

Route::controller(RefundRequestController::class)->middleware(['2fa', 'NoCache'])->prefix('admin')->group(function (){

    Route::get('/refund_request', 'index')
        ->name('admin.refund_request')
        ->middleware('auth', 'role:admin');

    Route::put('/refund_request/update/{id}', 'update')
        ->name('admin.refund_request.update')
        ->middleware('auth', 'role:admin');

});
