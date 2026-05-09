<?php

use App\Http\Controllers\Admin\ActivityMonitoringController;
use App\Http\Controllers\Admin\AuditController;
use App\Http\Controllers\Admin\CustomersController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DisputesController;
use App\Http\Controllers\Admin\InventoryController as AdminInventoryController;
use App\Http\Controllers\Admin\NetworksController;
use App\Http\Controllers\Admin\NotificationsHubController;
use App\Http\Controllers\Admin\OrdersController;
use App\Http\Controllers\Admin\PackagesController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\SellersController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TopupsController;
use App\Http\Controllers\Admin\WithdrawalsController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Auth\SellerRegistrationController;
use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\MarketplaceController as CustomerMarketplaceController;
use App\Http\Controllers\Customer\OrdersController as CustomerOrdersController;
use App\Http\Controllers\Customer\ProfileController as CustomerProfileController;
use App\Http\Controllers\Customer\WalletController as CustomerWalletController;
use App\Http\Controllers\Seller\InventoryController;
use App\Http\Controllers\Seller\NetworksController as SellerNetworksController;
use App\Http\Controllers\Seller\OrdersController as SellerOrdersController;
use App\Http\Controllers\Seller\PackagesController as SellerPackagesController;
use App\Http\Controllers\Seller\SalesController as SellerSalesController;
use App\Http\Controllers\Seller\SettingsController as SellerSettingsController;
use App\Http\Controllers\Seller\WalletController as SellerWalletController;
use App\Http\Controllers\Seller\WithdrawalsController as SellerWithdrawalsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [SessionController::class, 'create'])->name('login');
    Route::post('/login', [SessionController::class, 'store'])->name('login.store');
    Route::get('/register', [RegistrationController::class, 'create'])->name('register');
    Route::post('/register', [RegistrationController::class, 'store'])->name('register.store');
    Route::get('/register/seller', [SellerRegistrationController::class, 'create'])->name('register.seller.create');
    Route::post('/register/seller', [SellerRegistrationController::class, 'store'])->name('register.seller.store');
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');
});

Route::post('/logout', [SessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function (): void {
    Route::get('/', [CustomerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/marketplace', [CustomerMarketplaceController::class, 'index'])->name('marketplace.index');
    Route::get('/marketplace/packages/{package}', [CustomerMarketplaceController::class, 'show'])->name('marketplace.show');
    Route::post('/marketplace/packages/{package}/buy', [CustomerMarketplaceController::class, 'buy'])->name('marketplace.buy');
    Route::get('/orders', [CustomerOrdersController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [CustomerOrdersController::class, 'show'])->name('orders.show');
    Route::get('/wallet', [CustomerWalletController::class, 'index'])->name('wallet.index');
    Route::get('/profile', [CustomerProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [CustomerProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [CustomerProfileController::class, 'updatePassword'])->name('profile.password');
});

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

    // Sellers (static paths before {seller})
    Route::get('/sellers', [SellersController::class, 'index'])->name('sellers.index');
    Route::get('/sellers/create', [SellersController::class, 'create'])->name('sellers.create');
    Route::post('/sellers', [SellersController::class, 'store'])->name('sellers.store');
    Route::get('/sellers/{seller}', [SellersController::class, 'show'])->name('sellers.show');
    Route::get('/sellers/{seller}/edit', [SellersController::class, 'edit'])->name('sellers.edit');
    Route::patch('/sellers/{seller}', [SellersController::class, 'update'])->name('sellers.update');
    Route::post('/sellers/{seller}/approve', [SellersController::class, 'approve'])->name('sellers.approve');
    Route::post('/sellers/{seller}/suspend', [SellersController::class, 'suspend'])->name('sellers.suspend');

    // Networks (static paths before {network})
    Route::get('/networks', [NetworksController::class, 'index'])->name('networks.index');
    Route::get('/networks/create', [NetworksController::class, 'create'])->name('networks.create');
    Route::post('/networks', [NetworksController::class, 'store'])->name('networks.store');
    Route::get('/networks/{network}', [NetworksController::class, 'show'])->name('networks.show');
    Route::get('/networks/{network}/edit', [NetworksController::class, 'edit'])->name('networks.edit');
    Route::patch('/networks/{network}', [NetworksController::class, 'update'])->name('networks.update');
    Route::delete('/networks/{network}', [NetworksController::class, 'destroy'])->name('networks.destroy');

    // Packages (static paths before {package})
    Route::get('/packages', [PackagesController::class, 'index'])->name('packages.index');
    Route::get('/packages/create', [PackagesController::class, 'create'])->name('packages.create');
    Route::post('/packages', [PackagesController::class, 'store'])->name('packages.store');
    Route::get('/packages/{package}', [PackagesController::class, 'show'])->name('packages.show');
    Route::get('/packages/{package}/edit', [PackagesController::class, 'edit'])->name('packages.edit');
    Route::patch('/packages/{package}', [PackagesController::class, 'update'])->name('packages.update');
    Route::delete('/packages/{package}', [PackagesController::class, 'destroy'])->name('packages.destroy');

    // Inventory (Admin) — export & bulk before {card}
    Route::get('/inventory', [AdminInventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/export', [AdminInventoryController::class, 'export'])->name('inventory.export');
    Route::post('/inventory/bulk-update', [AdminInventoryController::class, 'bulkUpdate'])->name('inventory.bulk-update');
    Route::get('/inventory/{card}', [AdminInventoryController::class, 'show'])->name('inventory.show');
    Route::patch('/inventory/{card}/status', [AdminInventoryController::class, 'updateStatus'])->name('inventory.update-status');

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

    // Additional admin modules
    Route::get('/notifications', [NotificationsHubController::class, 'index'])->name('notifications.index');
    Route::view('/roles-permissions', 'admin.roles.index')->name('roles.index');
    Route::get('/activity-monitoring', [ActivityMonitoringController::class, 'index'])->name('activity.index');
});

/*
|--------------------------------------------------------------------------
| Seller Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:seller_manager', 'seller.linked'])->prefix('seller')->name('seller.')->group(function (): void {
    Route::get('/', [App\Http\Controllers\Seller\DashboardController::class, 'index'])->name('dashboard');

    // Inventory
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/create', [InventoryController::class, 'create'])->name('inventory.create');
    Route::post('/inventory', [InventoryController::class, 'store'])->name('inventory.store');
    Route::post('/inventory/import-csv', [InventoryController::class, 'importCsv'])->name('inventory.import-csv');
    Route::patch('/inventory/{card}/status', [InventoryController::class, 'updateStatus'])->name('inventory.update-status');
    Route::delete('/inventory/{card}', [InventoryController::class, 'destroy'])->name('inventory.destroy');

    // Withdrawals
    Route::get('/withdrawals', [SellerWithdrawalsController::class, 'index'])->name('withdrawals.index');
    Route::get('/withdrawals/create', [SellerWithdrawalsController::class, 'create'])->name('withdrawals.create');
    Route::post('/withdrawals', [SellerWithdrawalsController::class, 'store'])->name('withdrawals.store');
    Route::get('/withdrawals/{withdrawal}', [SellerWithdrawalsController::class, 'show'])->name('withdrawals.show');

    Route::get('/networks', [SellerNetworksController::class, 'index'])->name('networks.index');
    Route::get('/networks/create', [SellerNetworksController::class, 'create'])->name('networks.create');
    Route::post('/networks', [SellerNetworksController::class, 'store'])->name('networks.store');
    Route::get('/networks/{network}/edit', [SellerNetworksController::class, 'edit'])->name('networks.edit');
    Route::patch('/networks/{network}', [SellerNetworksController::class, 'update'])->name('networks.update');
    Route::delete('/networks/{network}', [SellerNetworksController::class, 'destroy'])->name('networks.destroy');

    Route::get('/packages', [SellerPackagesController::class, 'index'])->name('packages.index');
    Route::get('/packages/create', [SellerPackagesController::class, 'create'])->name('packages.create');
    Route::post('/packages', [SellerPackagesController::class, 'store'])->name('packages.store');
    Route::get('/packages/{package}/edit', [SellerPackagesController::class, 'edit'])->name('packages.edit');
    Route::patch('/packages/{package}', [SellerPackagesController::class, 'update'])->name('packages.update');
    Route::delete('/packages/{package}', [SellerPackagesController::class, 'destroy'])->name('packages.destroy');

    Route::get('/orders', [SellerOrdersController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [SellerOrdersController::class, 'show'])->name('orders.show');
    Route::get('/sales', [SellerSalesController::class, 'index'])->name('sales.index');
    Route::get('/wallet', [SellerWalletController::class, 'index'])->name('wallet.index');

    Route::get('/settings', [SellerSettingsController::class, 'index'])->name('settings.index');
    Route::patch('/settings/profile', [SellerSettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::patch('/settings/business', [SellerSettingsController::class, 'updateBusiness'])->name('settings.business');
});
