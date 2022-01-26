<?php

Route::get('/harvest', [App\Http\Controllers\Leadman\HarvestController::class, 'index'])->name('harvest.index');

Route::group(['prefix' => 'harvest', 'middlename' => 'auth'], function () {
    Route::get('/get-harvest', [App\Http\Controllers\Leadman\HarvestController::class, 'getHarvest']);
    Route::post('/store-harvest', [App\Http\Controllers\Leadman\HarvestController::class, 'storeHarvest']);
    Route::post('/update-harvest/{id}', [App\Http\Controllers\Leadman\HarvestController::class, 'updateHarvest']);
    Route::post('/delete-harvest/{id}', [App\Http\Controllers\Leadman\HarvestController::class, 'deleteHarvest']);
});
