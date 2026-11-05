
<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Seller\SellerAuthController;
use App\Http\Controllers\Admin\SellerManagementController;


/*
|--------------------------------------------------------------------------
| صفحه اصلی و قطعات EPC
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('parts.index');
});

Route::get('/parts', [PartController::class, 'index'])
    ->name('parts.index');

Route::post('/parts', [PartController::class, 'store'])
    ->name('parts.store');


/*
|--------------------------------------------------------------------------
| ورود و ثبت نام کاربران
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


/*
|--------------------------------------------------------------------------
| خروج از حساب
|--------------------------------------------------------------------------
*/

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


/*
|--------------------------------------------------------------------------
| پنل فروشنده
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
| پنل مدیریت
|--------------------------------------------------------------------------
| فقط کاربران واردشده با دسترسی مدیر
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // فهرست فروشندگان
        Route::get('/sellers', [SellerManagementController::class, 'index'])
            ->name('sellers.index');

        // جزئیات فروشنده
        Route::get('/sellers/{id}', [SellerManagementController::class, 'show'])
            ->name('sellers.show');

        // تأیید فروشنده
        Route::post('/sellers/{id}/approve', [SellerManagementController::class, 'approve'])
            ->name('sellers.approve');

        // رد درخواست فروشندگی
        Route::post('/sellers/{id}/reject', [SellerManagementController::class, 'reject'])
            ->name('sellers.reject');

    });
