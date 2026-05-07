<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TopupsController;
use App\Http\Controllers\Admin\WithdrawalsController;
use App\Http\Controllers\Admin\DisputesController;
use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\Seller\InventoryController;
use App\Http\Controllers\Seller\WithdrawalsController as SellerWithdrawalsController;
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
    Route::get('/topups', [TopupsController::class, 'index'])->name('topups.index');
    Route::post('/topups/{topup}/approve', [TopupsController::class, 'approve'])->name('topups.approve');
    Route::post('/topups/{topup}/reject', [TopupsController::class, 'reject'])->name('topups.reject');
    
    // Withdrawals
    Route::get('/withdrawals', [WithdrawalsController::class, 'index'])->name('withdrawals.index');
    Route::post('/withdrawals/{withdrawal}/approve', [WithdrawalsController::class, 'approve'])->name('withdrawals.approve');
    Route::post('/withdrawals/{withdrawal}/reject', [WithdrawalsController::class, 'reject'])->name('withdrawals.reject');
    
    // Disputes
    Route::get('/disputes', [DisputesController::class, 'index'])->name('disputes.index');
    Route::post('/disputes/{order}/refund', [DisputesController::class, 'refund'])->name('disputes.refund');
    Route::post('/disputes/{order}/reject', [DisputesController::class, 'reject'])->name('disputes.reject');
    
    // Placeholder routes for other admin modules (to be implemented)
    Route::get('/customers', fn() => view('admin.customers.index'))->name('customers.index');
    Route::get('/sellers', fn() => view('admin.sellers.index'))->name('sellers.index');
    Route::get('/networks', fn() => view('admin.networks.index'))->name('networks.index');
    Route::get('/packages', fn() => view('admin.packages.index'))->name('packages.index');
    Route::get('/inventory', fn() => view('admin.inventory.index'))->name('inventory.index');
    Route::get('/orders', fn() => view('admin.orders.index'))->name('orders.index');
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
    
    // Inventory
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/create', [InventoryController::class, 'create'])->name('inventory.create');
    Route::post('/inventory', [InventoryController::class, 'store'])->name('inventory.store');
    Route::patch('/inventory/{card}/status', [InventoryController::class, 'updateStatus'])->name('inventory.update-status');
    Route::delete('/inventory/{card}', [InventoryController::class, 'destroy'])->name('inventory.destroy');
    
    // Withdrawals
    Route::get('/withdrawals', [SellerWithdrawalsController::class, 'index'])->name('withdrawals.index');
    Route::get('/withdrawals/create', [SellerWithdrawalsController::class, 'create'])->name('withdrawals.create');
    Route::post('/withdrawals', [SellerWithdrawalsController::class, 'store'])->name('withdrawals.store');
    Route::get('/withdrawals/{withdrawal}', [SellerWithdrawalsController::class, 'show'])->name('withdrawals.show');
    
    // Placeholder routes for other seller modules (to be implemented)
    Route::get('/networks', fn() => view('seller.networks.index'))->name('networks.index');
    Route::get('/packages', fn() => view('seller.packages.index'))->name('packages.index');
    Route::get('/orders', fn() => view('seller.orders.index'))->name('orders.index');
    Route::get('/sales', fn() => view('seller.sales.index'))->name('sales.index');
    Route::get('/wallet', fn() => view('seller.wallet.index'))->name('wallet.index');
    Route::get('/settings', fn() => view('seller.settings.index'))->name('settings.index');
});
