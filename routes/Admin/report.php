<?php

Use App\Http\Controllers\Admin\ReportController;

Route::controller(ReportController::class)->middleware(['NoCache', '2fa'])->prefix('admin')->group(function() {

    Route::get('/report', 'index')
        ->name('admin.report')
        ->middleware('auth', 'role:admin');
    
    Route::get('/report/sales/{filter}/{date_range?}', 'getSalesAndStats')
        ->name('admin.report.sales')
        ->middleware('auth', 'role:admin');
});
