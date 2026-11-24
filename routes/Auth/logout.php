<?php

use App\Http\Controllers\Auth\LogoutController;

Route::controller(LogoutController::class)->middleware('NoCache')->group(function() {

    Route::post('/logout', 'logout')
        ->name('logout')
        ->middleware('auth');

});
