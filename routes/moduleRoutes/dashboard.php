<?php

Route::group(['prefix' => 'dashboard'], function () {
    Route::get('/total-employees', [App\Http\Controllers\DashboardController::class, 'getTotalEmployees']);
    Route::get('/total-areas', [App\Http\Controllers\DashboardController::class, 'getTotalAreas']);
    Route::get('/total-operations', [App\Http\Controllers\DashboardController::class, 'getTotalOperations']);
    Route::get('/total-present', [App\Http\Controllers\DashboardController::class, 'getPresentEmployee']);
});
