<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UsersController;

Route::resource('products', ProductController::class);

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Profile page (GET)
Route::get('/profile', function () {
    return view('users.profile', ['user' => auth()->user()]);
})->name('profile');

// Logout (POST)
Route::post('/logout', [UsersController::class, 'doLogout'])->name('do_logout');
Route::post('/login', [UsersController::class, 'doLogin'])->name('do_login');
Route::post('/register', [UsersController::class, 'doRegister'])->name('do_register');
// Login (GET)
Route::get('/login', function () {
    return view('users.login');
})->name('login');

// Register (GET)
Route::get('/register', function () {
    return view('users.register');
})->name('register');


