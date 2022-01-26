<?php

Route::get('/half-month', [App\Http\Controllers\Leadman\HalfMonthController::class, 'index'])->name('month.index');


Route::group(['prefix' => 'month', 'middleware' => 'auth'], function () {
    Route::get('/get-reports', [App\Http\Controllers\Leadman\HalfMonthController::class, 'getReports']);
    Route::get('/generate-report', [App\Http\Controllers\Leadman\HalfMonthController::class, 'generateReport']);
    Route::post('/store-report', [App\Http\Controllers\Leadman\HalfMonthController::class, 'storeReport']);
    Route::get('/get-report/{id}', [App\Http\Controllers\Leadman\HalfMonthController::class, 'getReport']);
});
