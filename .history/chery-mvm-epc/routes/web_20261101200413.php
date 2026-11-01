
<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Seller\SellerAuthController;
use App\Http\Controllers\Admin\SellerManagementController;


/*
|--------------------------------------------------------------------------
| EPC Catalog
|--------------------------------------------------------------------------
*/

// // صفحه اصلی
// Route::get('/', [CatalogController::class, 'explorer'])
//     ->name('home');

// اکسپلورر دسته‌بندی‌ها
Route::get('/explorer', [CatalogController::class, 'explorer'])
    ->name('catalog.explorer');

// جست‌وجوی قطعات
Route::get('/', [CatalogController::class, 'index'])
    ->name('parts.index');

// دسته‌بندی و زیردسته‌ها
Route::get('/parts/category', [CatalogController::class, 'category'])
    ->name('parts.category');

// جزئیات قطعه
Route::get('/parts/{id}', [CatalogController::class, 'show'])
    ->name('parts.show');


/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

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


/*
|--------------------------------------------------------------------------
| Seller
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
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


/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/

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
