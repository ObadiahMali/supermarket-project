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
use Spatie\Permission\Models\Role;

// 🌐 Public landing page
Route::get('/', function () {
    return view('welcome');
});

// 📝 Registration with Spatie role assignment
Route::post('/register', function (Request $request) {
    Validator::make($request->all(), [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
        'role' => ['required', 'in:manager,entry_clerk,cashier'],
    ])->validate();

    Role::firstOrCreate(['name' => $request->role, 'guard_name' => 'web']);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    $user->assignRole($request->role);
    Auth::login($user);

    switch ($request->role) {
        case 'manager':
            return redirect()->route('manager.dashboard');
        case 'cashier':
            return redirect()->route('cashier.dashboard');
        case 'entry_clerk':
            return redirect()->route('entryClerk.dashboard');
        default:
            return redirect('/dashboard');
    }
});

// 🔐 Logout
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

// 🧭 Default dashboard (fallback)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// 🧑‍💼 Manager dashboard
Route::get('/manager/dashboard', [ManagerController::class, 'index'])
    ->middleware(['auth', 'role:manager'])
    ->name('manager.dashboard');

// 🧾 Entry Clerk dashboard
Route::get('/entry-clerk/dashboard', [ProductController::class, 'entryClerkDashboard'])
    ->middleware(['auth', 'role:entry_clerk'])
    ->name('entryClerk.dashboard');

// 💰 Cashier dashboard
Route::get('/cashier/dashboard', [CashierController::class, 'index'])
    ->middleware(['auth', 'role:cashier'])
    ->name('cashier.dashboard');

// 📦 Product routes (Entry Clerk + Manager)
Route::middleware(['auth', 'role:manager|entry_clerk'])->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/restock', [ProductController::class, 'showRestockForm'])->name('products.restock.form');
    Route::post('/products/{id}/restock', [ProductController::class, 'restock'])->name('products.restock');
});

// ✏️ Product editing and reporting (Manager only)
Route::middleware(['auth', 'role:manager'])->group(function () {
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
});

// 🛒 POS routes (Cashier + Manager)
Route::middleware(['auth', 'role:manager|cashier'])->group(function () {
    Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
    Route::post('/pos/add', [POSController::class, 'addToCart'])->name('sales.store');
    Route::delete('/pos/remove/{id}', [POSController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/pos/checkout', [POSController::class, 'checkout'])->name('sales.checkout');
});

// 🔐 Auth scaffolding
require __DIR__.'/auth.php';