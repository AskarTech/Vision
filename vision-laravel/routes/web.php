<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TopupsController;
use App\Http\Controllers\Admin\WithdrawalsController;
use App\Http\Controllers\Admin\DisputesController;
use App\Http\Controllers\Admin\CustomersController;
use App\Http\Controllers\Admin\SellersController;
use App\Http\Controllers\Admin\NetworksController;
use App\Http\Controllers\Admin\PackagesController;
use App\Http\Controllers\Admin\InventoryController as AdminInventoryController;
use App\Http\Controllers\Admin\OrdersController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\AuditController;
use App\Http\Controllers\Admin\SettingsController;
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
    
    // Customers
    Route::get('/customers', [CustomersController::class, 'index'])->name('customers.index');
    Route::get('/customers/{customer}', [CustomersController::class, 'show'])->name('customers.show');
    Route::get('/customers/{customer}/edit', [CustomersController::class, 'edit'])->name('customers.edit');
    Route::patch('/customers/{customer}', [CustomersController::class, 'update'])->name('customers.update');
    Route::patch('/customers/{customer}/suspend', [CustomersController::class, 'suspend'])->name('customers.suspend');
    Route::patch('/customers/{customer}/activate', [CustomersController::class, 'activate'])->name('customers.activate');

    // Sellers
    Route::get('/sellers', [SellersController::class, 'index'])->name('sellers.index');
    Route::get('/sellers/{seller}', [SellersController::class, 'show'])->name('sellers.show');
    Route::get('/sellers/create', [SellersController::class, 'create'])->name('sellers.create');
    Route::post('/sellers', [SellersController::class, 'store'])->name('sellers.store');
    Route::get('/sellers/{seller}/edit', [SellersController::class, 'edit'])->name('sellers.edit');
    Route::patch('/sellers/{seller}', [SellersController::class, 'update'])->name('sellers.update');
    Route::post('/sellers/{seller}/approve', [SellersController::class, 'approve'])->name('sellers.approve');
    Route::post('/sellers/{seller}/suspend', [SellersController::class, 'suspend'])->name('sellers.suspend');

    // Networks
    Route::get('/networks', [NetworksController::class, 'index'])->name('networks.index');
    Route::get('/networks/{network}', [NetworksController::class, 'show'])->name('networks.show');
    Route::get('/networks/create', [NetworksController::class, 'create'])->name('networks.create');
    Route::post('/networks', [NetworksController::class, 'store'])->name('networks.store');
    Route::get('/networks/{network}/edit', [NetworksController::class, 'edit'])->name('networks.edit');
    Route::patch('/networks/{network}', [NetworksController::class, 'update'])->name('networks.update');
    Route::delete('/networks/{network}', [NetworksController::class, 'destroy'])->name('networks.destroy');

    // Packages
    Route::get('/packages', [PackagesController::class, 'index'])->name('packages.index');
    Route::get('/packages/{package}', [PackagesController::class, 'show'])->name('packages.show');
    Route::get('/packages/create', [PackagesController::class, 'create'])->name('packages.create');
    Route::post('/packages', [PackagesController::class, 'store'])->name('packages.store');
    Route::get('/packages/{package}/edit', [PackagesController::class, 'edit'])->name('packages.edit');
    Route::patch('/packages/{package}', [PackagesController::class, 'update'])->name('packages.update');
    Route::delete('/packages/{package}', [PackagesController::class, 'destroy'])->name('packages.destroy');

    // Inventory (Admin)
    Route::get('/inventory', [AdminInventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/{card}', [AdminInventoryController::class, 'show'])->name('inventory.show');
    Route::patch('/inventory/{card}/status', [AdminInventoryController::class, 'updateStatus'])->name('inventory.update-status');
    Route::post('/inventory/bulk-update', [AdminInventoryController::class, 'bulkUpdate'])->name('inventory.bulk-update');
    Route::get('/inventory/export', [AdminInventoryController::class, 'export'])->name('inventory.export');

    // Orders
    Route::get('/orders', [OrdersController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrdersController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/cancel', [OrdersController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{order}/refund', [OrdersController::class, 'refund'])->name('orders.refund');

    // Reports
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::get('/reports/sales', [ReportsController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/customers', [ReportsController::class, 'customers'])->name('reports.customers');

    // Audit
    Route::get('/audit', [AuditController::class, 'index'])->name('audit.index');
    Route::get('/audit/{transaction}', [AuditController::class, 'show'])->name('audit.show');
    Route::get('/audit/topups', [AuditController::class, 'topups'])->name('audit.topups');
    Route::get('/audit/withdrawals', [AuditController::class, 'withdrawals'])->name('audit.withdrawals');
    Route::get('/audit/orders', [AuditController::class, 'orders'])->name('audit.orders');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::get('/settings/general', [SettingsController::class, 'general'])->name('settings.general');
    Route::post('/settings/general', [SettingsController::class, 'updateGeneral'])->name('settings.update-general');
    Route::get('/settings/payment', [SettingsController::class, 'payment'])->name('settings.payment');
    Route::post('/settings/payment', [SettingsController::class, 'updatePayment'])->name('settings.update-payment');
    Route::get('/settings/email', [SettingsController::class, 'email'])->name('settings.email');
    Route::post('/settings/email', [SettingsController::class, 'updateEmail'])->name('settings.update-email');
    Route::get('/settings/security', [SettingsController::class, 'security'])->name('settings.security');
    Route::post('/settings/security', [SettingsController::class, 'updateSecurity'])->name('settings.update-security');
});
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
