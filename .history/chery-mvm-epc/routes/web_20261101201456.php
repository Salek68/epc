
<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Seller\SellerAuthController;
use App\Http\Controllers\Seller\SellerProductController;
use App\Http\Controllers\Admin\SellerManagementController;

// ==========================================
// EPC - کاتالوگ قطعات
// ==========================================

Route::get('/', [CatalogController::class, 'index'])
    ->name('catalog.index');

Route::get('/catalog/category', [CatalogController::class, 'category'])
    ->name('catalog.category');

Route::get('/catalog/part/{id}', [CatalogController::class, 'show'])
    ->whereNumber('id')
    ->name('catalog.show');

Route::get('/epc/tiggo-7-pro', [CatalogController::class, 'explorer'])
    ->name('catalog.explorer');


// ==========================================
// احراز هویت کاربران
// ==========================================

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'login'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'authenticate'])
        ->name('login.submit');

    Route::get('/register', [AuthController::class, 'register'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'store'])
        ->name('register.submit');

});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


// ==========================================
// پنل فروشنده
// ==========================================

Route::middleware('auth')
    ->prefix('seller')
    ->name('seller.')
    ->group(function () {

        // ثبت‌نام فروشنده
        Route::get('/register', [SellerAuthController::class, 'create'])
            ->name('register');

        Route::post('/register', [SellerAuthController::class, 'store'])
            ->name('register.store');

        // وضعیت تأیید فروشندگی
        Route::get('/status', [SellerAuthController::class, 'status'])
            ->name('status');

        // محصولات فروشنده
        // Route::get('/products', [SellerProductController::class, 'index'])
        //     ->name('products.index');

        // Route::get('/products/create', [SellerProductController::class, 'create'])
        //     ->name('products.create');

        // Route::post('/products', [SellerProductController::class, 'store'])
        //     ->name('products.store');

        // Route::get('/products/{id}/edit', [SellerProductController::class, 'edit'])
        //     ->whereNumber('id')
        //     ->name('products.edit');

        // Route::put('/products/{id}', [SellerProductController::class, 'update'])
        //     ->whereNumber('id')
        //     ->name('products.update');

        // Route::delete('/products/{id}', [SellerProductController::class, 'destroy'])
        //     ->whereNumber('id')
        //     ->name('products.destroy');
    });


// ==========================================
// مدیریت فروشندگان توسط ادمین
// ==========================================

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/sellers', [SellerManagementController::class, 'index'])
            ->name('sellers.index');

        Route::get('/sellers/{id}', [SellerManagementController::class, 'show'])
            ->whereNumber('id')
            ->name('sellers.show');

        Route::post('/sellers/{id}/approve', [SellerManagementController::class, 'approve'])
            ->whereNumber('id')
            ->name('sellers.approve');

        Route::post('/sellers/{id}/reject', [SellerManagementController::class, 'reject'])
            ->whereNumber('id')
            ->name('sellers.reject');
    });
