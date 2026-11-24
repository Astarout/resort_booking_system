<?php

use App\Http\Controllers\Admin\AccommodationController;
use App\Http\Controllers\Admin\SheetController;
use App\Http\Controllers\Admin\PetController;
use App\Http\Controllers\Admin\EntranceFeeController;

Route::controller(AccommodationController::class)->middleware(['NoCache', '2fa', 'update_last_time_active'])->prefix('admin')->group(function() {

    Route::get('/accommodations', 'index')
        ->name('admin.accommodation')
        ->middleware('auth', 'role:admin');

    Route::get('/accommodations/add', 'addForm')
        ->name('admin.accommodation.add')
        ->middleware('auth', 'role:admin');

    Route::post('/accommodations/store', 'store')
        ->name('admin.accommodation.store')
        ->middleware('auth', 'role:admin');

    Route::delete('/accommodations/delete/{id}', 'destroy')
        ->name('admin.accommodation.delete')
        ->middleware('auth', 'role:admin');

    Route::get('/accommodations/edit/{id}', 'editForm')
        ->name('admin.accommodation.edit')
        ->middleware('auth', 'role:admin');

    Route::put('/accommodations/update/{id}', 'update')
        ->name('admin.accommodation.update')
        ->middleware('auth', 'role:admin');

});

Route::controller(SheetController::class)->middleware(['NoCache', '2fa'])->prefix('admin')->group(function() {

    Route::post('/sheets/store', 'store')
        ->name('admin.sheets.store')
        ->middleware('auth', 'role:admin');

    Route::delete('/sheets/destroy/{id}', 'destroy')
        ->name('admin.sheets.destroy')
        ->middleware('auth', 'role:admin');

});

Route::controller(PetController::class)->middleware(['NoCache', '2fa'])->prefix('admin')->group(function() {

    Route::post('/pets/store', 'store')
        ->name('admin.pets.store')
        ->middleware('auth', 'role:admin');

    Route::put('/pets/update/{id}', 'update')
        ->name('admin.pets.update')
        ->middleware('auth', 'role:admin');

    Route::delete('/pets/destroy/{id}', 'destroy')
        ->name('admin.pets.destroy')
        ->middleware('auth', 'role:admin');

});

Route::controller(EntranceFeeController::class)->middleware(['NoCache', '2fa'])->prefix('admin')->group(function() {

    Route::post('/entrance_fees/store', 'store')
        ->name('admin.entrance_fees.store')
        ->middleware('auth', 'role:admin');

    Route::put('/entrance_fees/update/{id}', 'update')
        ->name('admin.entrance_fees.update')
        ->middleware('auth', 'role:admin');

    Route::delete('/entrance_fees/destroy/{id}', 'destroy')
        ->name('admin.entrance_fees.destroy')
        ->middleware('auth', 'role:admin');

});
