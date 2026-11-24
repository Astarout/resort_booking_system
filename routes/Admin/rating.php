<?php

use App\Http\Controllers\Admin\RatingController;

Route::controller(RatingController::class)->middleware(['NoCache', '2fa', 'update_last_time_active'])->prefix('admin')->group(function() {

    Route::get('/ratings', 'index')
        ->name('admin.ratings')
        ->middleware('auth', 'role:admin');

    Route::patch('/ratings/set-visible/{id}', 'visible')
        ->name('admin.ratings.visible')
        ->middleware('auth', 'role:admin');

    Route::patch('/ratings/set-hidden/{id}', 'hidden')
        ->name('admin.ratings.hidden')
        ->middleware('auth', 'role:admin');

});
