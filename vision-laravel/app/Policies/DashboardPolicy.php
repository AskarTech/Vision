<?php

namespace App\Policies;

use App\Models\User;

class DashboardPolicy
{
    /**
     * Determine if the user can view the admin dashboard.
     */
    public function viewAdmin(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine if the user can view the seller dashboard.
     */
    public function viewSeller(User $user): bool
    {
        return in_array($user->role, ['seller', 'seller_manager']);
    }

    /**
     * Determine if the user can view the customer dashboard.
     */
    public function viewCustomer(User $user): bool
    {
        return $user->role === 'customer';
    }

    /**
     * Determine if the user has a specific permission.
     */
    public function hasPermission(User $user, string $permission): bool
    {
        $permissions = config('permissions');
        
        // Check admin permissions
        if ($user->role === 'admin' && isset($permissions['admin'][$permission])) {
            return in_array('admin', $permissions['admin'][$permission]['roles']);
        }

        // Check seller permissions
        if (in_array($user->role, ['seller', 'seller_manager']) && isset($permissions['seller'][$permission])) {
            return in_array($user->role, $permissions['seller'][$permission]['roles']);
        }

        // Check customer permissions
        if ($user->role === 'customer' && isset($permissions['customer'][$permission])) {
            return in_array('customer', $permissions['customer'][$permission]['roles']);
        }

        return false;
    }

    /**
     * Determine if the user can manage topups.
     */
    public function manageTopups(User $user): bool
    {
        return $this->hasPermission($user, 'manage_topups');
    }

    /**
     * Determine if the user can manage customers.
     */
    public function manageCustomers(User $user): bool
    {
        return $this->hasPermission($user, 'manage_customers');
    }

    /**
     * Determine if the user can manage sellers.
     */
    public function manageSellers(User $user): bool
    {
        return $this->hasPermission($user, 'manage_sellers');
    }

    /**
     * Determine if the user can manage packages.
     */
    public function managePackages(User $user): bool
    {
        return $this->hasPermission($user, 'manage_packages');
    }

    /**
     * Determine if the user can manage inventory.
     */
    public function manageInventory(User $user): bool
    {
        return $this->hasPermission($user, 'manage_inventory');
    }

    /**
     * Determine if the user can manage orders.
     */
    public function manageOrders(User $user): bool
    {
        return $this->hasPermission($user, 'manage_orders');
    }

    /**
     * Determine if the user can manage withdrawals.
     */
    public function manageWithdrawals(User $user): bool
    {
        return $this->hasPermission($user, 'manage_withdrawals');
    }

    /**
     * Determine if the user can manage disputes.
     */
    public function manageDisputes(User $user): bool
    {
        return $this->hasPermission($user, 'manage_disputes');
    }

    /**
     * Determine if the user can view audit logs.
     */
    public function viewAuditLogs(User $user): bool
    {
        return $this->hasPermission($user, 'view_audit_logs');
    }

    /**
     * Determine if the user can view reports.
     */
    public function viewReports(User $user): bool
    {
        return $this->hasPermission($user, 'view_reports');
    }

    /**
     * Determine if the user can manage settings.
     */
    public function manageSettings(User $user): bool
    {
        return $this->hasPermission($user, 'manage_settings');
    }

    /**
     * Determine if the user can manage networks (seller).
     */
    public function manageNetworks(User $user): bool
    {
        return $this->hasPermission($user, 'manage_networks');
    }

    /**
     * Determine if the user can view sales analytics (seller).
     */
    public function viewSales(User $user): bool
    {
        return $this->hasPermission($user, 'view_sales');
    }

    /**
     * Determine if the user can request withdrawals (seller).
     */
    public function requestWithdrawal(User $user): bool
    {
        return $this->hasPermission($user, 'request_withdrawal');
    }

    /**
     * Determine if the user can view wallet.
     */
    public function viewWallet(User $user): bool
    {
        return $this->hasPermission($user, 'view_wallet');
    }
}
