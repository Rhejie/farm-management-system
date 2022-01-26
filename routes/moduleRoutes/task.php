<?php

Route::get('/task', [App\Http\Controllers\Leadman\TaskController::class, 'index'])->name('task.index');

Route::group(['prefix' => 'task', 'middleware' => 'auth'], function () {
    Route::get('get-tasks', [App\Http\Controllers\Leadman\TaskController::class, 'getTasks']);
    Route::get('search-task', [App\Http\Controllers\Leadman\TaskController::class, 'searchTask']);
    Route::post('store-task', [App\Http\Controllers\Leadman\TaskController::class, 'storeTask']);
    Route::post('update-task/{id}', [App\Http\Controllers\Leadman\TaskController::class, 'updateTask']);
    Route::post('delete-task/{id}', [App\Http\Controllers\Leadman\TaskController::class, 'deleteTask']);
});
