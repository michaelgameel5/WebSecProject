<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
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


