<?php

Route::get('/banana', [App\Http\Controllers\Leadman\BananaYieldReportController::class, 'index'])->name('banana.index');

Route::group(['prefix' => 'report'], function() {

    Route::get('/banana-report/{id}', [App\Http\Controllers\Leadman\BananaYieldReportController::class, 'expectedHarvest']);
    Route::get('/banana-by-year-report/{id}', [App\Http\Controllers\Leadman\BananaYieldReportController::class, 'getData']);
});
