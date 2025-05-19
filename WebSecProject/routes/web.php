<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CreditController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Mail\VerificationEmail;
use App\Http\Controllers\Auth\AuthController;


Route::get('/', function () {
    return view('welcome');
})->name('home');


Route::get('register', [UsersController::class, 'register'])->name('register');
Route::post('register', [UsersController::class, 'doRegister'])->name('do_register');
Route::get('login', [UsersController::class, 'login'])->name('login');
Route::post('login', [UsersController::class, 'doLogin'])->name('do_login');
Route::get('logout', [UsersController::class, 'doLogout'])->name('do_logout');
Route::get('profile', [UsersController::class, 'profile'])->name('profile');
Route::get('verify', [UsersController::class, 'verify'])->name('verify');

Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('forgot_password');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('send_reset_link');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('do_reset_password');

// Product routes
Route::resource('products', ProductController::class);

// Comment routes
Route::post('/products/{product}/comments', [CommentController::class, 'store'])->name('comments.store');
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

// Order routes
Route::post('/products/{product}/purchase', [OrderController::class, 'store'])->name('orders.store');
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('/orders/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
Route::post('/orders/checkout', [OrderController::class, 'processCheckout'])->name('orders.process-checkout');

// Credit routes
Route::get('/credit', [CreditController::class, 'show'])->name('credits.show');
Route::get('/credits', [CreditController::class, 'index'])->name('credits.index');
Route::get('/credits/{user}/edit', [CreditController::class, 'edit'])->name('credits.edit');
Route::put('/credits/{user}', [CreditController::class, 'update'])->name('credits.update');
Route::post('/credits/{user}/add', [CreditController::class, 'add'])->name('credits.add');

Route::get('/auth/google', [UsersController::class, 'redirectToGoogle'])->name('login_with_google');
Route::get('/auth/google/callback', [UsersController::class, 'handleGoogleCallback']);

Route::middleware(['auth'])->group(function () {
    // ... existing code ...
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
    Route::post('/orders/checkout', [OrderController::class, 'processCheckout'])->name('orders.process-checkout');
    Route::get('/users', [UsersController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/edit', [UsersController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UsersController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UsersController::class, 'destroy'])->name('users.destroy');
    Route::get('/users/{user}/change-password', [UsersController::class, 'showChangePasswordForm'])->name('users.change_password_form');
    Route::post('/users/{user}/change-password', [UsersController::class, 'changePassword'])->name('users.change_password');
    Route::get('/users/create', [UsersController::class, 'create'])->name('users.create');
    Route::post('/users', [UsersController::class, 'store'])->name('users.store');
    // ... existing code ...
});
