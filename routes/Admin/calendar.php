<?php

use App\Http\Controllers\Admin\CalendarController;

Route::controller(CalendarController::class)->middleware(['NoCache', '2fa', 'update_last_time_active'])->prefix('admin')->group(function() {

    Route::get('/calendar', 'index')
        ->name('admin.calendar')
        ->middleware('auth', 'role:admin');

});
