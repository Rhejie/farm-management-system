<?php

Route::group(['prefix' => 'company'], function () {
    Route::get('/', [App\Http\Controllers\Company\CompanyController::class, 'index'])->name('company.index');
    Route::get('/job/create', [App\Http\Controllers\Company\CompanyController::class, 'createJob'])->name('company.create.job');
    Route::get('/jobs', [App\Http\Controllers\Company\CompanyController::class, 'jobs'])->name('company.jobs');
    Route::get('/applicants', [App\Http\Controllers\Company\CompanyController::class, 'applicants'])->name('company.applicants');
    Route::get('/profile', [App\Http\Controllers\Company\CompanyController::class, 'profile'])->name('company.profile');
});

