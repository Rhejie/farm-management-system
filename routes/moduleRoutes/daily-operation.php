<?php

Route::get('/daily-operation', [App\Http\Controllers\Leadman\DailyOperationController::class, 'index'])->name('daily-operation.index');

Route::group(['prefix' => 'operation', 'middleware' => 'auth'], function () {
    Route::get('get-operations', [App\Http\Controllers\Leadman\DailyOperationController::class, 'getOperations']);
    Route::post('store-operation', [App\Http\Controllers\Leadman\DailyOperationController::class, 'storeOperation']);
    Route::post('update-operation/{id}', [App\Http\Controllers\Leadman\DailyOperationController::class, 'updateOperation']);
    Route::post('delete-operation/{id}', [App\Http\Controllers\Leadman\DailyOperationController::class, 'deleteOperation']);
    Route::get('/get-undeployed-operations', [App\Http\Controllers\Leadman\DailyOperationController::class, 'getUndeployedOperations']);
});
