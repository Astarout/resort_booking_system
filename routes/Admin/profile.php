<?php

use App\Http\Controllers\Admin\ProfileController;

Route::controller(ProfileController::class)->middleware(['NoCache', '2fa', 'update_last_time_active'])->prefix('admin')->group(function() {

    Route::get('/profile', 'index')
        ->name('admin.profile')
        ->middleware('auth', 'role:admin');

    Route::put('/profile/update', 'updateProfile')
        ->name('admin.profile.update')
        ->middleware('auth', 'role:admin');

    Route::put('/profile/changePassword/update', 'changePassword')
        ->name('admin.profile.password.update')
        ->middleware('auth', 'role:admin');

    Route::delete('/profile/delete', 'markAccountToBeDeleted')
        ->name('admin.profile.delete')
        ->middleware('auth', 'role:admin');
});
