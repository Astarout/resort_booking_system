<?php

use App\Http\Controllers\User\ProfileController;

Route::controller(ProfileController::class)->middleware(['NoCache', '2fa', 'update_last_time_active'])->prefix('user')->group(function() {

    Route::get('/profile', 'index')
        ->name('user.profile')
        ->middleware('auth', 'role:user');

    Route::put('/profile/update', 'updateProfile')
        ->name('user.profile.update')
        ->middleware('auth', 'role:user');

    Route::put('/profile/changePassword/update', 'changePassword')
        ->name('user.profile.password.update')
        ->middleware('auth', 'role:user');

    Route::post('/profile/review', 'submitReview')
        ->name('user.profile.rate')
        ->middleware('auth', 'role:user');

    Route::delete('/profile/delete', 'markAccountToBeDeleted')
        ->name('user.profile.delete')
        ->middleware('auth', 'role:user');

});
