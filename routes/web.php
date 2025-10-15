<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\ReportsController;

Route::get('/', function () {
    return view('welcome');
});

// ✅ Custom registration logic to save role
Route::post('/register', function (Request $request) {
    Validator::make($request->all(), [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
        'role' => ['required', 'in:admin,manager,cashier'],
    ])->validate();

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role,
    ]);

    Auth::login($user);

    return redirect('/dashboard');
});


Route::middleware(['auth', 'role:cashier,manager'])->group(function () {
    Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
});

Route::middleware(['auth', 'role:entry_clerk,manager'])->group(function () {
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
});


Route::middleware(['auth', 'role:manager'])->group(function () {
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
});
// Role-based dashboard redirect
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Protected routes for each role
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/manager/dashboard', [ManagerController::class, 'index'])->name('manager.dashboard');
    Route::get('/cashier/dashboard', [CashierController::class, 'index'])->name('cashier.dashboard');
});

// Product routes
Route::middleware(['auth'])->group(function () {
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
});

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::get('/products/{id}/restock', [ProductController::class, 'showRestockForm'])->name('products.restock.form');
Route::post('/products/{id}/restock', [ProductController::class, 'restock'])->name('products.restock');

// POS routes
Route::middleware(['auth'])->group(function () {
    Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
    Route::post('/pos/add', [POSController::class, 'addToCart'])->name('sales.store');
    Route::delete('/pos/remove/{id}', [POSController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/pos/checkout', [POSController::class, 'checkout'])->name('sales.checkout');
});

// Reports (manager only)
Route::get('/reports', [ReportsController::class, 'index'])
    ->middleware(['auth', 'role:manager'])
    ->name('reports.index');


// Logout
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');



// Auth routes (login, register view, etc.)
require __DIR__.'/auth.php';