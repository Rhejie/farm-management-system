<?php
Route::group(['middleware' => 'auth'], function () {
    Route::get('/stocks', [App\Http\Controllers\Warehouse\StockController::class, 'index'])->name('warehouse.stock');
    Route::get('/items', [App\Http\Controllers\Warehouse\ItemController::class, 'index'])->name('warehouse.item');
    Route::get('/categories', [App\Http\Controllers\Warehouse\CategoryController::class, 'index'])->name('warehouse.category');
});

Route::group(['prefix' => 'warehouse', 'middleware' => 'auth'], function () {

    Route::get('get-categories', [App\Http\Controllers\Warehouse\CategoryController::class, 'getCategories']);
    Route::post('/store-category', [App\Http\Controllers\Warehouse\CategoryController::class, 'storeCategory']);
    Route::post('/update-category/{id}', [App\Http\Controllers\Warehouse\CategoryController::class, 'updateCategory']);
    Route::post('/delete-category/{id}', [App\Http\Controllers\Warehouse\CategoryController::class, 'deleteCategory']);
    Route::get('/search-categories', [App\Http\Controllers\Warehouse\CategoryController::class, 'searchCategory']);

    Route::get('get-items', [App\Http\Controllers\Warehouse\ItemController::class, 'getItems']);
    Route::post('/store-item', [App\Http\Controllers\Warehouse\ItemController::class, 'storeItem']);
    Route::post('/update-item/{id}', [App\Http\Controllers\Warehouse\ItemController::class, 'updateItem']);
    Route::post('/delete-item/{id}', [App\Http\Controllers\Warehouse\ItemController::class, 'deleteItem']);
    Route::get('/search-items', [App\Http\Controllers\Warehouse\ItemController::class, 'searchItem']);

    Route::get('get-stocks', [App\Http\Controllers\Warehouse\StockController::class, 'getStocks']);
    Route::post('/store-stock', [App\Http\Controllers\Warehouse\StockController::class, 'storeStock']);
    Route::post('/update-stock/{id}', [App\Http\Controllers\Warehouse\StockController::class, 'updateStock']);
    Route::post('/delete-stock/{id}', [App\Http\Controllers\Warehouse\StockController::class, 'deleteStock']);
    Route::post('/re-stock/{id}', [App\Http\Controllers\Warehouse\StockController::class, 'restock']);

});
