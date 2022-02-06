<?php

use Illuminate\Support\Facades\Route;

Route::get('/payroll', [App\Http\Controllers\Finance\FinanceController::class, 'index'])->name('payroll.index');
Route::get('/finance/payslip/{id}', [App\Http\Controllers\Finance\FinanceController::class, 'generatePayslip']);
Route::get('/finance-setting', [App\Http\Controllers\Finance\FinanceController::class, 'financeSetting'])->name('finance.setting');

Route::group(['prefix' => 'finance', 'middleware' => 'auth'], function () {
    Route::get('/get-payrolls', [App\Http\Controllers\Finance\FinanceController::class, 'getPayrolls']);
    Route::post('/generate-payroll', [App\Http\Controllers\Finance\FinanceController::class, 'generatePayroll']);
    Route::post('/store-payroll', [App\Http\Controllers\Finance\FinanceController::class, 'storePayroll']);
    Route::get('/overtime-rate', [App\Http\Controllers\Finance\FinanceController::class, 'getOvertimeRate']);
    Route::post('/update-finance-setting/{id}', [App\Http\Controllers\Finance\FinanceController::class, 'updateFinanceSetting']);
});
