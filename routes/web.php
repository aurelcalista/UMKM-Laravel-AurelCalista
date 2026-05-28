<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\TrashController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PasswordResetController;


/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    if (Auth::check()) {

        if (Auth::user()->role == 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('home');
    }

    return app(HomeController::class)->index(request());
});

Route::get('/home',
    [HomeController::class, 'index'])
    ->name('home');

Route::get('/produks',
    [HomeController::class, 'produks'])
    ->name('produks');

Route::get('/produk/{id}',
    [HomeController::class, 'produkDetail'])
    ->name('produkDetail');

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::get('/login',
    [AuthController::class, 'showLogin'])
    ->name('login')
    ->middleware('guest');

Route::post('/login',
    [AuthController::class, 'login'])
    ->name('login.post');

Route::get('/register',
    [AuthController::class, 'showRegister'])
    ->name('register')
    ->middleware('guest');

Route::post('/register',
    [AuthController::class, 'register'])
    ->name('register.post');

Route::post('/logout',
    [AuthController::class, 'logout'])
    ->name('logout');

/*
|--------------------------------------------------------------------------
| LUPA PASSWORD (RESET PASSWORD)
|--------------------------------------------------------------------------
*/

// User routes
Route::get('/lupa-password', [PasswordResetController::class, 'showRequestForm'])->name('password.request')->middleware('guest');
Route::post('/lupa-password/request', [PasswordResetController::class, 'sendRequest'])->name('password.send-request')->middleware('guest');
Route::post('/lupa-password/status', [PasswordResetController::class, 'checkStatus'])->name('password.check-status')->middleware('guest');
Route::post('/lupa-password/login-temp', [PasswordResetController::class, 'loginWithTempPassword'])->name('password.temp-login')->middleware('guest');
Route::get('/force-change-password', [PasswordResetController::class, 'showForceChangeForm'])->name('password.force-change')->middleware('auth');
Route::post('/force-change-password', [PasswordResetController::class, 'forceChangePassword'])->name('password.force-change.post')->middleware('auth');

/*
|--------------------------------------------------------------------------
| REVIEW
|--------------------------------------------------------------------------
*/

Route::post('/review',
    [ReviewController::class, 'store'])
    ->name('review.store')
    ->middleware('auth');

/*
|--------------------------------------------------------------------------
| PELANGGAN (USER)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // CART
    Route::get('/cart',
        [CartController::class, 'index'])
        ->name('cart.index');

    Route::post('/cart/add',
        [CartController::class, 'add'])
        ->name('cart.add');

    Route::post('/cart/update',
        [CartController::class, 'update'])
        ->name('cart.update');

    Route::delete('/cart/remove/{id}',
        [CartController::class, 'remove'])
        ->name('cart.remove');

    Route::get('/cart/checkout',
        [CartController::class, 'checkoutPage'])
        ->name('cart.checkout');

    Route::post('/cart/checkout', [CartController::class, 'checkoutProcess'])->name('cart.checkout.process');
    Route::get('/clear-cart', function () {

        session()->forget('cart');

        return 'cart cleared';
    });
    // PROFILE
    Route::get('/profile',
        [ProfileController::class, 'index'])
        ->name('profile.show');

    Route::get('/profile/edit',
        [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile',
        [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::put('/profile/password',
        [ProfileController::class, 'updatePassword'])
        ->name('profile.updatePassword');

    Route::post('/profile/photo',
        [ProfileController::class, 'updatePhoto'])
        ->name('profile.updatePhoto');

    Route::delete('/profile/photo',
        [ProfileController::class, 'deletePhoto'])
        ->name('profile.deletePhoto');

    Route::delete('/profile',
        [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    // FAVORIT
    Route::post('/favorit/toggle',
        [\App\Http\Controllers\FavoritController::class, 'toggle'])
        ->name('favorit.toggle');
        
    // INVOICE USER
    Route::get('/invoice/{id}',
        [TransaksiController::class, 'invoice'])
        ->name('invoice.index');

    Route::get('/invoice/{id}/print',
        [TransaksiController::class, 'printInvoice'])
        ->name('invoice.print');
});

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    // DASHBOARD
    Route::get('/dashboard',
        [DashboardController::class, 'index'])
        ->name('dashboard');

    // =========================
    // TRASH GLOBAL
    // =========================
    Route::get('/trash',
        [TrashController::class, 'index'])
        ->name('trash');

    // =========================
    // APPROVALS
    // =========================
    Route::get('/approvals',
        [App\Http\Controllers\Admin\ApprovalController::class, 'index'])
        ->name('approvals.index');

    Route::get('/approvals/{id}',
        [App\Http\Controllers\Admin\ApprovalController::class, 'show'])
        ->name('approvals.show');

    Route::post('/approvals/{id}/approve',
        [App\Http\Controllers\Admin\ApprovalController::class, 'approve'])
        ->name('approvals.approve');

    Route::post('/approvals/{id}/reject',
        [App\Http\Controllers\Admin\ApprovalController::class, 'reject'])
        ->name('approvals.reject');

    Route::post('/approvals/{id}/complete',
        [App\Http\Controllers\Admin\ApprovalController::class, 'complete'])
        ->name('approvals.complete');

    // =========================
    // PASSWORD RESET REQUESTS (ADMIN)
    // =========================
    Route::get('/password-resets', [PasswordResetController::class, 'adminIndex'])->name('password-resets.index');
    Route::post('/password-resets/{id}/approve', [PasswordResetController::class, 'approve'])->name('password-resets.approve');
    Route::delete('/password-resets/{id}/reject', [PasswordResetController::class, 'reject'])->name('password-resets.reject');

    // =========================
    // PRODUK
    // =========================

    Route::delete('/produk/bulk-destroy',
        [ProdukController::class, 'bulkDestroy'])
        ->name('produk.bulkDestroy');

    Route::post('/produk/{id}/restore',
        [ProdukController::class, 'restore'])
        ->name('produk.restore');

    Route::delete('/produk/{id}/force-delete',
        [ProdukController::class, 'forceDelete'])
        ->name('produk.forceDelete');

    Route::get('/produk',
        [ProdukController::class, 'index'])
        ->name('produk.index');

    Route::get('/produk/create',
        [ProdukController::class, 'create'])
        ->name('produk.create');

    Route::post('/produk',
        [ProdukController::class, 'store'])
        ->name('produk.store');

    Route::get('/produk/{id}/edit',
        [ProdukController::class, 'edit'])
        ->name('produk.edit');

    Route::put('/produk/{id}',
        [ProdukController::class, 'update'])
        ->name('produk.update');

    Route::delete('/produk/{id}',
        [ProdukController::class, 'destroy'])
        ->name('produk.destroy');

    // =========================
    // KATEGORI
    // =========================

    Route::delete('/kategori/bulk-destroy',
        [KategoriController::class, 'bulkDestroy'])
        ->name('kategori.bulkDestroy');

    Route::post('/kategori/{id}/restore',
        [KategoriController::class, 'restore'])
        ->name('kategori.restore');

    Route::delete('/kategori/{id}/force-delete',
        [KategoriController::class, 'forceDelete'])
        ->name('kategori.forceDelete');

    Route::get('/kategori',
        [KategoriController::class, 'index'])
        ->name('kategori.index');

    Route::post('/kategori',
        [KategoriController::class, 'store'])
        ->name('kategori.store');

    Route::get('/kategori/{id}/edit',
        [KategoriController::class, 'edit'])
        ->name('kategori.edit');

    Route::put('/kategori/{id}',
        [KategoriController::class, 'update'])
        ->name('kategori.update');

    Route::delete('/kategori/{id}',
        [KategoriController::class, 'destroy'])
        ->name('kategori.destroy');

    // =========================
    // PROMO
    // =========================

    Route::post('/promo/{id}/restore',
        [PromoController::class, 'restore'])
        ->name('promo.restore');

    Route::delete('/promo/{id}/force-delete',
        [PromoController::class, 'forceDelete'])
        ->name('promo.forceDelete');

    Route::get('/promo',
        [PromoController::class, 'index'])
        ->name('promo.index');

    Route::post('/promo',
        [PromoController::class, 'store'])
        ->name('promo.store');

    Route::put('/promo/{id}',
        [PromoController::class, 'update'])
        ->name('promo.update');

    Route::delete('/promo/{id}',
        [PromoController::class, 'destroy'])
        ->name('promo.destroy');

    // =========================
    // PELANGGAN
    // =========================

    Route::delete('/pelanggan/bulk-destroy',
        [PelangganController::class, 'bulkDestroy'])
        ->name('pelanggan.bulkDestroy');

    Route::get('/pelanggan',
        [PelangganController::class, 'index'])
        ->name('pelanggan.index');

    Route::delete('/pelanggan/{id}',
        [PelangganController::class, 'destroy'])
        ->name('pelanggan.destroy');

    Route::post('/pelanggan/{id}/reset-password',
        [PelangganController::class, 'resetPassword'])
        ->name('pelanggan.resetPassword');
    Route::post('/pelanggan/{id}/reset-password-fast',
        [PelangganController::class, 'resetPasswordFast'])
        ->name('pelanggan.resetPasswordFast');

    // =========================
    // TRANSAKSI
    // =========================

    Route::get('/transaksi',
        [TransaksiController::class, 'index'])
        ->name('transaksi.index');

    Route::get('/transaksi/{id}',
        [TransaksiController::class, 'show'])
        ->name('transaksi.show');

    Route::delete('/transaksi/{id}',
        [TransaksiController::class, 'destroy'])
        ->name('transaksi.destroy');

    Route::get('/transaksi/{id}/invoice',
        [TransaksiController::class, 'adminInvoice'])
        ->name('transaksi.invoice');

    Route::get('/transaksi/{id}/print',
        [TransaksiController::class, 'printInvoice'])
        ->name('transaksi.print');

    // =========================
    // NOTIFICATIONS
    // =========================

    Route::get('/notifications',
        [NotificationController::class, 'index'])
        ->name('notifications.index');

    Route::get('/notifications/unread',
        [NotificationController::class, 'getUnread'])
        ->name('notifications.unread');

    Route::post('/notifications/{id}/read',
        [NotificationController::class, 'markAsRead'])
        ->name('notifications.mark-read');

    Route::post('/notifications/mark-all-read',
        [NotificationController::class, 'markAllRead'])
        ->name('notifications.mark-all-read');
});