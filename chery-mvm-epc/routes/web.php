
<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\BrakeBankController;
use App\Http\Controllers\SubscriptionController;

use App\Http\Controllers\Admin\SellerManagementController;
use App\Http\Controllers\Admin\SubscriptionTransactionController;
use App\Http\Controllers\Admin\DashboardController;

use App\Http\Controllers\Seller\SellerAuthController;
use App\Http\Controllers\Seller\SellerProductController;
use App\Http\Controllers\Seller\BrakeBankSellerController;


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
// خرید و تمدید اشتراک
// بدون نیاز به اشتراک فعال
// ==========================================

Route::middleware('auth')
    ->prefix('subscriptions')
    ->name('subscriptions.')
    ->controller(SubscriptionController::class)
    ->group(function () {

        Route::get('/', 'index')->name('index');

        Route::get('/my', 'my')->name('my');

        Route::post('/buy', 'store')->name('store');

        Route::get('/payment/{id}', 'payment')
            ->whereNumber('id')
            ->name('payment');

        Route::post('/payment/{id}/receipt', 'uploadReceipt')
            ->whereNumber('id')
            ->name('receipt');

    });


// ==========================================
// کاتالوگ EPC
// اشتراک کاربر یا فروشنده
// ==========================================

Route::middleware(['auth', 'subscription:user'])->group(function () {

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
// ثبت نام و وضعیت فروشنده
// بدون نیاز به اشتراک
// ==========================================

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


// ==========================================
// امکانات بانک لنت فروشنده
// فقط اشتراک فعال فروشنده
// ==========================================

Route::middleware(['auth', 'subscription:seller'])
    ->prefix('seller')
    ->name('seller.')
    ->group(function () {

        Route::get('/brake-bank', [BrakeBankSellerController::class, 'index'])
            ->name('brake-bank.index');

        Route::post('/brake-bank/{brakePad}', [BrakeBankSellerController::class, 'store'])
            ->name('brake-bank.store');

        Route::get('/brake-bank/offers', [BrakeBankSellerController::class, 'offers'])
            ->name('brake-bank.offers');

    });


// ==========================================
// محصولات فروشنده
// فقط اشتراک فعال فروشنده
// ==========================================

Route::middleware(['auth', 'subscription:seller'])
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


// ==========================================
// لینک خرید محصولات فروشندگان
// عمومی
// ==========================================

Route::get('/buy/seller-product/{product}', [
    SellerProductController::class,
    'visit'
])->name('seller.products.visit');


// ==========================================
// بانک لنت عمومی
// ==========================================

Route::prefix('brake-bank')
    ->name('brake-bank.')
    ->group(function () {

        Route::get('/', [BrakeBankController::class, 'index'])
            ->name('index');

        Route::get('/vehicle/{vehicle}', [BrakeBankController::class, 'vehicle'])
            ->name('vehicle');

        Route::get('/pad/{pad}', [BrakeBankController::class, 'show'])
            ->name('pad.show');

    });


// ==========================================
// پنل مدیریت
// ==========================================

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // مدیریت فروشندگان
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

        // مدیریت اشتراک‌ها
        Route::get('/subscription-transactions', [SubscriptionTransactionController::class, 'index'])
            ->name('subscription-transactions.index');

        Route::post('/subscription-transactions/{id}/approve', [SubscriptionTransactionController::class, 'approve'])
            ->whereNumber('id')
            ->name('subscription-transactions.approve');

        Route::post('/subscription-transactions/{id}/reject', [SubscriptionTransactionController::class, 'reject'])
            ->whereNumber('id')
            ->name('subscription-transactions.reject');

    });
