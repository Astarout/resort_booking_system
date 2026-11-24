<?php

use App\Http\Controllers\Auth\UserRegisterController;
use App\Http\Controllers\Auth\AdminRegisterController;

# User and Admin Registration Routes

Route::controller(UserRegisterController::class)->middleware('NoCache')->prefix('user')->group(function() {

    Route::get('/register', 'index')
        ->name('user.register')
        ->middleware('guest');

    Route::post('/register', 'register')
        ->name('user.register')
        ->middleware('guest');
});

Route::controller(AdminRegisterController::class)->middleware(['NoCache', '2fa'])->prefix('admin')->group(function() {

    Route::get('/register', 'index')
        ->name('admin.register')
        ->middleware('auth', 'role:admin');

    Route::post('/register', 'register')
        ->name('admin.register')
        ->middleware('auth', 'role:admin');

});
