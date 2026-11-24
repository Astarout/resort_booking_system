<?php

use App\Http\Controllers\User\ReservationController;

Route::controller(ReservationController::class)->middleware(['NoCache', '2fa', 'update_last_time_active'])->prefix('user')->group(function() {

    Route::get('/reservation', 'index')
        ->name('user.reservation')
        ->middleware('auth', 'role:user');

    Route::get('/reservation/view/{id}', 'viewDetails')
        ->name('user.reservation.view')
        ->middleware('auth', 'role:user');

    Route::get('/reservation/form', 'reservationForm')
        ->name('user.reservation.form')
        ->middleware('auth', 'role:user');

    Route::post('/reservation/form/store', 'store')
        ->name('user.reservation.store')
        ->middleware('auth', 'role:user');

});
