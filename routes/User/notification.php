<?php

use App\Http\Controllers\User\NotificationController;

Route::controller(NotificationController::class)->middleware(['NoCache', '2fa', 'update_last_time_active'])->prefix('user')->group(function() {

    Route::get('/notification', 'index')
        ->name('user.notifications')
        ->middleware('auth', 'role:user');

    Route::patch('/notification/read/{id}', 'read')
        ->name('user.notifications.read')
        ->middleware('auth', 'role:user');

    Route::delete('/notification/delete/{id}', 'destroy')
        ->name('user.notifications.delete')
        ->middleware('auth', 'role:user');

    Route::put('/notification/readAll', 'markAllAsRead')
        ->name('user.notifications.readAll')
        ->middleware('auth', 'role:user');

    Route::delete('/notification/deleteAll', 'clearAll')
        ->name('user.notifications.deleteAll')
        ->middleware('auth', 'role:user');
});
