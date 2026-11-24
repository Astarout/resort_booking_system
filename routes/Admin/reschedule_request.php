<?php

use App\Http\Controllers\Admin\RescheduleRequestController;

Route::controller(RescheduleRequestController::class)->middleware(['NoCache', '2fa', 'update_last_time_active'])->prefix('admin')->group(function() {

    Route::get('/reschedule-request', 'index')
        ->name('admin.reschedule_request')
        ->middleware('auth', 'role:admin');

    Route::get('/reschedule-request/approve/{id}', 'approve')
        ->name('admin.reschedule_request.approve')
        ->middleware('auth', 'role:admin');

    Route::get('/reschedule-request/deny/{id}', 'deny')
        ->name('admin.reschedule_request.deny')
        ->middleware('auth', 'role:admin');

});
