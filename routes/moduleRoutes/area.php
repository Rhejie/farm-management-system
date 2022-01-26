<?php

Route::get('/areas', [App\Http\Controllers\Hr\AreaController::class, 'index'])->name('area.index');

Route::group(['prefix' => 'area', 'middleware' => 'auth'], function() {
    Route::get('/get-areas', [App\Http\Controllers\Hr\AreaController::class, 'getAreas']);
    Route::post('/store-area', [App\Http\Controllers\Hr\AreaController::class, 'storeArea']);
    Route::post('/update-area/{id}', [App\Http\Controllers\Hr\AreaController::class, 'updateArea']);
    Route::post('/delete-area/{id}', [App\Http\Controllers\Hr\AreaController::class, 'deleteArea']);
    Route::get('/search-areas', [App\Http\Controllers\Hr\AreaController::class, 'searchArea']);
    Route::get('/get-all-areas', [App\Http\Controllers\Hr\AreaController::class, 'getAreasList']);
    Route::get('/get-area/{id}', [App\Http\Controllers\Hr\AreaController::class, 'getArea']);
});
