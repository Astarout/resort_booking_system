<?php

use App\Http\Controllers\Admin\ExpectedGuestsController;

Route::controller(ExpectedGuestsController::class)->middleware(['NoCache', '2fa', 'update_last_time_active'])->prefix('admin')->group(function() {

    Route::get('/expectedGuests', 'index')
        ->name('admin.expectedGuests')
        ->middleware('auth', 'role:admin');

    Route::post('/expected-guests/time-in', 'time_in')
        ->name('admin.expectedGuests.timeIn')
        ->middleware('auth', 'role:admin');

    Route::post('/expected-guests/time-out', 'time_out')
        ->name('admin.expectedGuests.timeOut')
        ->middleware('auth', 'role:admin');

    Route::post('/expected-guests/mark-fee-paid', 'markFeePaid')
        ->name('admin.expectedGuests.markFeePaid')
        ->middleware('auth', 'role:admin');
    
});
