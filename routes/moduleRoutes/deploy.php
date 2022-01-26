<?php

Route::get('/deploy', [App\Http\Controllers\Warehouse\DeployController::class, 'index'])->name('deploy.index');

Route::group(['prefix' => 'deploy', 'middleware' => 'auth'], function () {
    Route::get('/get-deploy', [App\Http\Controllers\Warehouse\DeployController::class, 'getDeploy']);
    Route::post('/store-deploy', [App\Http\Controllers\Warehouse\DeployController::class, 'storeDeploy']);
    Route::post('/update-deploy/{id}', [App\Http\Controllers\Warehouse\DeployController::class, 'updateDeploy']);
    Route::post('/delete-deploy/{id}', [App\Http\Controllers\Warehouse\DeployController::class, 'deleteDeploy']);
});
