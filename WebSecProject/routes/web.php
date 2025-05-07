<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Mail\VerificationEmail;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CardController;
use App\Http\Middleware\CheckRole;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\NotificationController;

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('register', [UsersController::class, 'register'])->name('register');
Route::post('register', [UsersController::class, 'doRegister'])->name('do_register');
Route::get('login', [UsersController::class, 'login'])->name('login');
Route::post('login', [UsersController::class, 'doLogin'])->name('do_login');
Route::get('logout', [UsersController::class, 'doLogout'])->name('do_logout');

// Password reset routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('forgot_password');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('send_reset_link');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('do_reset_password');

// Employee routes (no verification needed)
Route::middleware(['auth', \App\Http\Middleware\EmployeeMiddleware::class])->group(function () {
    // Product management
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
});

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    // Profile routes
    Route::get('profile', [UsersController::class, 'profile'])->name('profile');
    Route::get('verify', [UsersController::class, 'verify'])->name('verify');
    Route::post('verify/resend', [UsersController::class, 'resendVerification'])->name('verification.resend');

    // Product routes (no verification needed)
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    
    // Routes that require email verification
    Route::middleware(['verified'])->group(function () {
        // Comment routes
        Route::post('/products/{product}/comments', [CommentController::class, 'store'])->name('comments.store');
        Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

        // Cart routes
        Route::middleware(['auth'])->group(function () {
            Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
            Route::post('/cart/items', [CartController::class, 'addItem'])->name('cart.add_item');
            Route::patch('/cart/items/{item}', [CartController::class, 'updateQuantity'])->name('cart.update_quantity');
            Route::delete('/cart/items/{item}', [CartController::class, 'removeItem'])->name('cart.remove_item');
            Route::post('/cart/apply-voucher', [CartController::class, 'applyVoucher'])->name('cart.apply_voucher');
            Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
        });

        // Card routes
        Route::get('/cards', [CardController::class, 'index'])->name('cards.index');
        Route::post('/cards', [CardController::class, 'store'])->name('cards.store');
        Route::delete('/cards/{card}/deactivate', [CardController::class, 'deactivate'])->name('cards.deactivate');
        Route::post('/cards/{card}/request-credit', [CardController::class, 'requestCredit'])->name('cards.request-credit');
        Route::get('/cards/credit-requests', [CardController::class, 'creditRequests'])->name('cards.credit-requests');
        Route::post('/cards/credit-requests/{creditRequest}/approve', [CardController::class, 'approveCredit'])->name('cards.approve-credit');
        Route::post('/cards/credit-requests/{creditRequest}/reject', [CardController::class, 'rejectCredit'])->name('cards.reject-credit');

        // Purchase History routes
        Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index');
        Route::get('/purchases/{purchase}', [PurchaseController::class, 'show'])->name('purchases.show');

        // Notification routes
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');

        // Customer voucher routes
        Route::get('/vouchers/{voucher}', [VoucherController::class, 'show'])->name('vouchers.show');
    });

    // Voucher routes (employee only)
    Route::middleware(['auth', \App\Http\Middleware\EmployeeMiddleware::class])->group(function () {
        Route::get('/vouchers', [VoucherController::class, 'index'])->name('vouchers.index');
        Route::get('/vouchers/create', [VoucherController::class, 'create'])->name('vouchers.create');
        Route::post('/vouchers', [VoucherController::class, 'store'])->name('vouchers.store');
        Route::get('/vouchers/{voucher}/edit', [VoucherController::class, 'edit'])->name('vouchers.edit');
        Route::put('/vouchers/{voucher}', [VoucherController::class, 'update'])->name('vouchers.update');
        Route::delete('/vouchers/{voucher}', [VoucherController::class, 'destroy'])->name('vouchers.destroy');
        Route::post('/vouchers/{voucher}/send', [VoucherController::class, 'send'])->name('vouchers.send');
    });
});

// Admin routes
Route::middleware(['auth', \App\Http\Middleware\EmployeeMiddleware::class])->group(function () {
    // Product deletion
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    
    // User management
    Route::get('/users', [UsersController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/edit', [UsersController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UsersController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UsersController::class, 'destroy'])->name('users.destroy');
    
    // Voucher deletion
    Route::delete('/vouchers/{voucher}', [VoucherController::class, 'destroy'])->name('vouchers.destroy');
});


