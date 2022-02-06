<?php

use Illuminate\Support\Facades\Route;

Route::get('/logistic-regression', [App\Http\Controllers\Leadman\LogisticRegresionController::class, 'index'])->name('logistic.index');

Route::prefix('logistic')
    ->middleware('auth')
    ->group(function() {
        Route::get('/get-logistics', [App\Http\Controllers\Leadman\LogisticRegresionController::class, 'getLogistics']);
        Route::post('/store-logistic', [App\Http\Controllers\Leadman\LogisticRegresionController::class, 'storeLogistic']);
        Route::post('/update-logistic/{id}', [App\Http\Controllers\Leadman\LogisticRegresionController::class, 'updateLogistic']);
        Route::post('/delete-logistic/{id}', [App\Http\Controllers\Leadman\LogisticRegresionController::class, 'deleteLogistic']);
    });
