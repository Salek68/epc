
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogController;

Route::get('/', [CatalogController::class, 'index'])
    ->name('catalog.index');

Route::get('/catalog/category', [CatalogController::class, 'category'])
    ->name('catalog.category');

Route::get('/catalog/part/{id}', [CatalogController::class, 'show'])
    ->whereNumber('id')
    ->name('catalog.show');

    Route::get(
    '/epc/tiggo-7-pro',
    [CatalogController::class, 'explorer']
)->name('catalog.explorer');