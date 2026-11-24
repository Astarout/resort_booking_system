<?php

use App\Http\Controllers\Admin\NotificationController;

Route::controller(NotificationController::class)->middleware(['NoCache', '2fa', 'update_last_time_active'])->prefix('admin')->group(function() {

    Route::get('/notification', 'index')
        ->name('admin.notifications')
        ->middleware('auth', 'role:admin');

    Route::patch('/notification/read/{id}', 'read')
        ->name('admin.notifications.read')
        ->middleware('auth', 'role:admin');

    Route::delete('/notification/delete/{id}', 'destroy')
        ->name('admin.notifications.delete')
        ->middleware('auth', 'role:admin');

    Route::put('/notification/readAll', 'markAllAsRead')
        ->name('admin.notifications.readAll')
        ->middleware('auth', 'role:admin');

    Route::delete('/notification/deleteAll', 'clearAll')
        ->name('admin.notifications.deleteAll')
        ->middleware('auth', 'role:admin');

});
