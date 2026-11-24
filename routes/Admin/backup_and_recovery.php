<?php

use App\Http\Controllers\Admin\BackupAndRecoveryController;

Route::controller(BackupAndRecoveryController::class)->middleware(['NoCache', '2fa'])->prefix('admin')->group(function() {

    Route::get('/backup-and-recovery', 'index')
        ->name('admin.backup_and_recovery')
        ->middleware('auth', 'role:admin');

    Route::post('/backup-and-recovery/run', 'run')
        ->name('admin.backup_and_recovery.run')
        ->middleware('auth', 'role:admin');

    Route::get('/backup-and-recovery/download/{file_name}', 'download')
        ->name('admin.backup_and_recovery.download')
        ->middleware('auth', 'role:admin');

    Route::get('/backup-and-recovery/restore/{file_name}', 'restore')
        ->name('admin.backup_and_recovery.restore')
        ->middleware('auth', 'role:admin');

    Route::get('/backup-and-recovery/delete/{file_name}', 'delete')
        ->name('admin.backup_and_recovery.delete')
        ->middleware('auth', 'role:admin');

});
