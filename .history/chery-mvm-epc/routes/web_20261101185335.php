
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



// درخواست فروشندگی
Route::middleware(['auth'])->prefix('seller')->name('seller.')->group(function () {

    Route::get('/register', [SellerAuthController::class, 'create'])
        ->name('register');

    Route::post('/register', [SellerAuthController::class, 'store'])
        ->name('register.store');

    Route::get('/status', [SellerAuthController::class, 'status'])
        ->name('status');

});

// مدیریت فروشندگان توسط مدیر
Route::middleware(['auth'])->prefix('admin/sellers')->name('admin.sellers.')->group(function () {

    Route::get('/', [SellerManagementController::class, 'index'])
        ->name('index');

    Route::get('/{seller}', [SellerManagementController::class, 'show'])
        ->name('show');

    Route::post('/{seller}/approve', [SellerManagementController::class, 'approve'])
        ->name('approve');

    Route::post('/{seller}/reject', [SellerManagementController::class, 'reject'])
        ->name('reject');

});
