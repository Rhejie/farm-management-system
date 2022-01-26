<?php
Route::get('/employees', [App\Http\Controllers\hr\EmployeeController::class, 'index'])->name('employee.index');

Route::group(['prefix' => 'employee', 'middleware' => 'auth'], function() {
    Route::get('/get-employees', [App\Http\Controllers\HR\EmployeeController::class, 'getEmployees']);
    Route::post('/store-employee', [App\Http\Controllers\HR\EmployeeController::class, 'storeEmployee']);
    Route::post('/update-employee/{id}', [App\Http\Controllers\HR\EmployeeController::class, 'updateEmployee']);
    Route::post('/delete-employee/{id}', [App\Http\Controllers\HR\EmployeeController::class, 'deleteEmployee']);
    Route::get('/search-employee', [App\Http\Controllers\HR\EmployeeController::class, 'searchEmployee']);
    Route::get('/get-profile/{id}', [App\Http\Controllers\HR\EmployeeController::class, 'getProfile']);
});
