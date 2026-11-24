<?php

use App\Http\Controllers\Admin\AdminDashboard;

Route::controller(AdminDashboard::class)->middleware(['NoCache', '2fa', 'update_last_time_active'])->prefix('admin')->group(function() {

    Route::get('/dashboard', 'index')
        ->name('admin.dashboard')
        ->middleware('auth', 'role:admin');

});
