<?php

Route::get('/attendance', [App\Http\Controllers\Leadman\AttendanceController::class, 'index'])->name('attendance.index');
Route::get('/overtime', [App\Http\Controllers\Leadman\AttendanceController::class, 'overtime'])->name('overtime.index');

Route::group(['prefix' => 'attendance', 'middleware' => 'auth'], function () {
    Route::get('/get-attendance', [App\Http\Controllers\Leadman\AttendanceController::class, 'getAttendance']);
    Route::post('/store-attendance', [App\Http\Controllers\Leadman\AttendanceController::class, 'storeAttendance']);
    Route::post('/update-attendance/{id}', [App\Http\Controllers\Leadman\AttendanceController::class, 'updateAttendance']);
    Route::post('/time-in', [App\Http\Controllers\Leadman\AttendanceController::class, 'timeIn']);
    Route::post('/ot-in/{id}', [App\Http\Controllers\Leadman\AttendanceController::class, 'otIn']);
    Route::post('/ot-out/{id}', [App\Http\Controllers\Leadman\AttendanceController::class, 'otOut']);
    Route::get('/get-overtime', [App\Http\Controllers\Leadman\AttendanceController::class, 'getOvertime']);
    Route::post('/approved-ot/{id}', [App\Http\Controllers\Leadman\AttendanceController::class, 'approvedOT']);
    Route::post('/decline-ot/{id}', [App\Http\Controllers\Leadman\AttendanceController::class, 'declineOT']);
});
