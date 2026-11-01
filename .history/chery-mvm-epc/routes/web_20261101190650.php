
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\Seller\SellerAuthController;
use App\Http\Controllers\Admin\SellerManagementController;

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



Route::middleware(['auth'])
    ->prefix('seller')
    ->name('seller.')
    ->group(function () {

        Route::get('/register', [SellerAuthController::class, 'create'])
            ->name('register');

        Route::post('/register', [SellerAuthController::class, 'store'])
            ->name('register.store');

        Route::get('/status', [SellerAuthController::class, 'status'])
            ->name('status');
    });

// مسیرهای مدیریت؛ فقط مدیران
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/sellers', [SellerManagementController::class, 'index'])
            ->name('sellers.index');

        Route::get('/sellers/{id}', [SellerManagementController::class, 'show'])
            ->name('sellers.show');

        Route::post('/sellers/{id}/approve', [SellerManagementController::class, 'approve'])
            ->name('sellers.approve');

        Route::post('/sellers/{id}/reject', [SellerManagementController::class, 'reject'])
            ->name('sellers.reject');
    });
