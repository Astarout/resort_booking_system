<?php

use App\Http\Controllers\Admin\GalleryController;

Route::controller(GalleryController::class)->middleware(['NoCache', '2fa', 'update_last_time_active'])->prefix('admin')->group(function() {

    Route::get('/gallery', 'index')
        ->name('admin.gallery')
        ->middleware('auth', 'role:admin');

    Route::post('/gallery/store', 'store')
        ->name('admin.gallery.upload')
        ->middleware('auth', 'role:admin');

    Route::delete('/gallery/destroy/{gallery}', 'destroy')
        ->name('admin.gallery.delete')
        ->middleware('auth', 'role:admin');

    Route::put('/gallery/update/{gallery}', 'updateImage')
        ->name('admin.gallery.update')
        ->middleware('auth', 'role:admin');
});
