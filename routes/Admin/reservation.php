<?php

use App\Http\Controllers\Admin\ReservationController;

Route::controller(ReservationController::class)->middleware(['NoCache', '2fa', 'update_last_time_active'])->prefix('admin')->group(function() {

    Route::get('/reservations', 'index')
        ->name('admin.reservations')
        ->middleware('auth', 'role:admin');

    Route::delete('/reservations/delete/{id}', 'destroy')
        ->name('admin.reservations.delete')
        ->middleware('auth', 'role:admin');

    Route::put('/reservations/approve/{id}', 'approve')
        ->name('admin.reservations.approve')
        ->middleware('auth', 'role:admin');

    Route::get('/reservation/showValidID/{id}', 'showValidID')
        ->name('admin.reservations.showValidID')
        ->middleware('auth', 'role:admin');

});
