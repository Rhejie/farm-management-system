<?php

Route::get('/team', [App\Http\Controllers\Leadman\TeamController::class, 'index'])->name('team.index');

Route::group(['prefix' => 'team', 'middleware' => 'auth'], function () {
    Route::get('get-teams', [App\Http\Controllers\Leadman\TeamController::class, 'getTeams']);
    Route::get('search-team', [App\Http\Controllers\Leadman\TeamController::class, 'searchTeam']);
    Route::post('store-team', [App\Http\Controllers\Leadman\TeamController::class, 'storeTeam']);
    Route::post('update-team/{id}', [App\Http\Controllers\Leadman\TeamController::class, 'updateTeam']);
    Route::post('delete-team/{id}', [App\Http\Controllers\Leadman\TeamController::class, 'deleteTeam']);
});
