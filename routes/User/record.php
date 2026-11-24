<?php

use App\Http\Controllers\User\RecordController;

Route::controller(RecordController::class)->middleware(['NoCache', '2fa', 'update_last_time_active'])->prefix('user')->group(function() {

    Route::get('/records', 'index')
        ->name('user.records')
        ->middleware('auth', 'role:user');

    Route::delete('/records/delete/{id}', 'destroy')
        ->name('user.records.delete')
        ->middleware('auth', 'role:user');

    Route::get('/records/showValidID/{id}', 'showValidID')
        ->name('user.records.showValidID')
        ->middleware('auth', 'role:user');

    Route::put('/records/cancel/{id}', 'cancel')
        ->name('user.records.cancel')
        ->middleware();

});
