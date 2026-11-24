<?php

use App\Http\Controllers\Admin\AccountManagementController;

Route::controller(AccountManagementController::class)->middleware(['NoCache', '2fa', 'update_last_time_active'])->prefix('admin')->group(function() {

    Route::get('/accounts', 'index')
        ->name('admin.accounts')
        ->middleware('auth', 'role:admin');

    Route::patch('/accounts/verify/{id}', 'verifyAccount')
        ->name('admin.accounts.verify')
        ->middleware('auth', 'role:admin');

    Route::patch('/accounts/deactivate/{id}', 'deactivate')
        ->name('admin.accounts.deactivate')
        ->middleware('auth', 'role:admin');

    Route::delete('/accounts/delete/{id}', 'markAccountToBeDeleted')
        ->name('admin.accounts.delete')
        ->middleware('auth', 'role:admin');

});
