<?php

use App\Http\Controllers\User\UserDashboard;

Route::controller(UserDashboard::class)->middleware(['NoCache', '2fa', 'update_last_time_active'])->prefix('user')->group(function() {

    Route::get('/dashboard', 'index')
        ->name('user.dashboard')
        ->middleware('auth', 'role:user');

});
