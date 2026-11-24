<?php

use App\Http\Controllers\Home\LandingPageController;

Route::controller(LandingPageController::class)->group(function() {

    Route::get('/', 'index')
        ->name('home.landing');

    Route::get('/terms-and-conditions', 'TermsAndCondition')
        ->name('home.terms-and-conditions');

    Route::get('/guidelines', 'Guidelines')
        ->name('home.guidelines');

    Route::get('/villa-booking-reminders', 'VillaBookingReminders')
        ->name('home.villa-booking-reminders');

    Route::get('/refund-and-rebooking-policy', 'RefundAndRebookingPolicy')
        ->name('home.refund-and-rebooking-policy');

    Route::get('/accommodation/{encrypted_id}', 'viewAccommodationDetails')
        ->name('home.accommodation-details');

});
