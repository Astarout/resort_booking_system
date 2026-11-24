<?php

use App\Http\Controllers\User\RescheduleRequestController;

Route::controller(RescheduleRequestController::class)->middleware(['NoCache', '2fa', 'update_last_time_active'])->prefix('user')->group(function() {

    Route::get('/reschedule_request', 'index')
        ->name('user.reschedule_request')
        ->middleware('auth', 'role:user');

    Route::post('/reschedule-request/store', 'store')
        ->name('user.reschedule_request.store')
        ->middleware('auth', 'role:user');

    Route::delete('/reschedule-request/delete/{id}', 'destroy')
        ->name('user.reschedule_request.destroy')
        ->middleware('auth', 'role:user');

});
