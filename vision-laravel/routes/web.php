<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\SessionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [SessionController::class, 'create'])->name('login');
    Route::post('/login', [SessionController::class, 'store'])->name('login.store');
});

Route::post('/logout', [SessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Topups
    Route::get('/topups', [DashboardController::class, 'topups'])->name('topups.index');
    
    // Placeholder routes for other admin modules (to be implemented)
    Route::get('/customers', fn() => view('admin.customers.index'))->name('customers.index');
    Route::get('/sellers', fn() => view('admin.sellers.index'))->name('sellers.index');
    Route::get('/networks', fn() => view('admin.networks.index'))->name('networks.index');
    Route::get('/packages', fn() => view('admin.packages.index'))->name('packages.index');
    Route::get('/inventory', fn() => view('admin.inventory.index'))->name('inventory.index');
    Route::get('/orders', fn() => view('admin.orders.index'))->name('orders.index');
    Route::get('/withdrawals', fn() => view('admin.withdrawals.index'))->name('withdrawals.index');
    Route::get('/disputes', fn() => view('admin.disputes.index'))->name('disputes.index');
    Route::get('/reports', fn() => view('admin.reports.index'))->name('reports.index');
    Route::get('/audit', fn() => view('admin.audit.index'))->name('audit.index');
    Route::get('/settings', fn() => view('admin.settings.index'))->name('settings.index');
});

/*
|--------------------------------------------------------------------------
| Seller Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:seller_manager'])->prefix('seller')->name('seller.')->group(function (): void {
    Route::get('/', [\App\Http\Controllers\Seller\DashboardController::class, 'index'])->name('dashboard');
    
    // Placeholder routes for seller modules (to be implemented)
    Route::get('/networks', fn() => view('seller.networks.index'))->name('networks.index');
    Route::get('/packages', fn() => view('seller.packages.index'))->name('packages.index');
    Route::get('/inventory', fn() => view('seller.inventory.index'))->name('inventory.index');
    Route::get('/orders', fn() => view('seller.orders.index'))->name('orders.index');
    Route::get('/sales', fn() => view('seller.sales.index'))->name('sales.index');
    Route::get('/withdrawals', fn() => view('seller.withdrawals.index'))->name('withdrawals.index');
    Route::get('/wallet', fn() => view('seller.wallet.index'))->name('wallet.index');
    Route::get('/settings', fn() => view('seller.settings.index'))->name('settings.index');
});
