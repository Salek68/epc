
<?php

use App\Http\Controllers\Admin\SellerManagementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\Seller\SellerAuthController;
use App\Http\Controllers\Seller\SellerProductController;
use Illuminate\Support\Facades\Route;

// ==========================================
// EPC - کاتالوگ قطعات
// ==========================================
// ==========================================
// EPC - کاتالوگ قطعات (فقط کاربران لاگین‌شده)
// ==========================================

Route::middleware('auth')->group(function () {

    Route::get('/', [CatalogController::class, 'index'])
        ->name('catalog.index');

    Route::get('/catalog/category', [CatalogController::class, 'category'])
        ->name('catalog.category');

    Route::get('/catalog/part/{id}', [CatalogController::class, 'show'])
        ->whereNumber('id')
        ->name('catalog.show');

    Route::get('/epc/tiggo-7-pro', [CatalogController::class, 'explorer'])
        ->name('catalog.explorer');

});


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




    // پنل محصولات فروشنده
Route::middleware('auth')
    ->prefix('seller/products')
    ->name('seller.products.')
    ->controller(SellerProductController::class)
    ->group(function () {
        Route::get('/catalog', 'catalog')->name('catalog');
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{product}/edit', 'edit')->name('edit');
        Route::put('/{product}', 'update')->name('update');
        Route::delete('/{product}', 'destroy')->name('destroy');
    });

// لینک خرید مشتری و ثبت کلیک
Route::get('/buy/seller-product/{product}', [
    SellerProductController::class,
    'visit'
])->name('seller.products.visit');



use App\Http\Controllers\BrakeBankController;

Route::prefix('brake-bank')
    ->name('brake-bank.')
    ->group(function () {

        // صفحه جست‌وجوی خودرو
        Route::get('/', [BrakeBankController::class, 'index'])
            ->name('index');

        // نمایش لنت‌های جلو و عقب یک خودرو
        Route::get('/vehicle/{vehicle}', [BrakeBankController::class, 'vehicle'])
            ->name('vehicle');

        // جزئیات یک لنت
        Route::get('/pad/{pad}', [BrakeBankController::class, 'show'])
            ->name('pad.show');
    });