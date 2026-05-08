<?php

namespace App\Providers;

use App\Models\User;
use App\Models\WalletTransaction;
use App\Policies\AuditPolicy;
use App\Policies\DashboardPolicy;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(WalletTransaction::class, AuditPolicy::class);

        // Register Gates for permissions
        $this->registerPermissionGates();

        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            
            // Release expired card reservations every minute
            $schedule->command('reservations:release-expired')
                     ->everyMinute()
                     ->withoutOverlapping()
                     ->onOneServer();
        });
    }

    /**
     * Register all permission gates.
     */
    protected function registerPermissionGates(): void
    {
        $permissions = config('permissions', []);

        foreach ($permissions as $roleGroup => $rolePermissions) {
            foreach ($rolePermissions as $permission => $config) {
                Gate::define($permission, function (User $user) use ($permission) {
                    $policy = new DashboardPolicy();
                    
                    // Map permission name to policy method
                    $method = $this->getPolicyMethodForPermission($permission);
                    
                    if ($method && method_exists($policy, $method)) {
                        return $policy->$method($user);
                    }
                    
                    return false;
                });
            }
        }
    }

    /**
     * Get the policy method name for a given permission.
     */
    protected function getPolicyMethodForPermission(string $permission): ?string
    {
        $mapping = [
            'view_dashboard' => 'viewDashboard',
            'manage_topups' => 'manageTopups',
            'manage_customers' => 'manageCustomers',
            'manage_sellers' => 'manageSellers',
            'manage_packages' => 'managePackages',
            'manage_inventory' => 'manageInventory',
            'manage_orders' => 'manageOrders',
            'manage_withdrawals' => 'manageWithdrawals',
            'manage_disputes' => 'manageDisputes',
            'view_audit_logs' => 'viewAuditLogs',
            'view_reports' => 'viewReports',
            'manage_settings' => 'manageSettings',
            'manage_networks' => 'manageNetworks',
            'view_sales' => 'viewSales',
            'request_withdrawal' => 'requestWithdrawal',
            'view_wallet' => 'viewWallet',
            'purchase_cards' => 'purchaseCards',
            'view_orders' => 'viewOrders',
            'request_topup' => 'requestTopup',
        ];

        return $mapping[$permission] ?? null;
    }
}
