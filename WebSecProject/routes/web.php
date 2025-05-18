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
Route::middleware(['auth', 'employee'])->group(function () {
    // Product management
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    
    // User management
    Route::get('/users', [UsersController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/edit', [UsersController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UsersController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UsersController::class, 'destroy'])->name('users.destroy');
    Route::get('/users/{user}/add-credit', [UsersController::class, 'addCredit'])->name('users.add-credit');
    Route::post('/users/{user}/add-credit', [UsersController::class, 'storeCredit'])->name('users.store-credit');
    
    // Product deletion
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
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
        Route::post('/credits/request', [CardController::class, 'requestGeneralCredit'])->name('credits.request-credit');

        // Notification routes
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');

        // Purchase History routes
        Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index');
        Route::get('/purchases/{purchase}', [PurchaseController::class, 'show'])->name('purchases.show');
    });
});

// Temporary route to assign employee role
Route::get('/assign-employee-role', function () {
    $employeeRole = \App\Models\Role::firstOrCreate(['name' => 'employee']);
    $user = auth()->user();
    
    if ($user) {
        $user->roles()->syncWithoutDetaching([$employeeRole->id]);
        return 'Employee role assigned successfully to: ' . $user->email;
    }
    
    return 'No user logged in';
})->middleware('auth');

// Temporary route to set up roles
Route::get('/setup-roles', function () {
    // Create roles
    $employeeRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'employee']);
    $customerRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'customer']);
    $adminRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);

    // Create permissions
    $permissions = [
        'manage products',
        'manage vouchers',
        'manage users',
        'manage credit requests',
        'view products',
        'view vouchers',
        'view purchase history',
        'manage cards',
    ];

    foreach ($permissions as $permission) {
        \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permission]);
    }

    // Assign permissions to roles
    $employeeRole->syncPermissions([
        'manage products',
        'manage vouchers',
        'manage credit requests',
        'view products',
        'view vouchers',
        'view purchase history',
    ]);

    $adminRole->syncPermissions($permissions);

    $customerRole->syncPermissions([
        'view products',
        'view vouchers',
        'view purchase history',
        'manage cards',
    ]);

    // Assign employee role to current user
    $user = auth()->user();
    if ($user) {
        $user->assignRole($employeeRole);
        return 'Roles and permissions set up successfully. Employee role assigned to: ' . $user->email;
    }

    return 'Roles and permissions set up successfully. No user logged in.';
})->middleware('auth');


