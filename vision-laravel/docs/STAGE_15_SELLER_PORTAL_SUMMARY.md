# Stage 15 — Seller Portal: Verification & QA

**Date:** 2026-05-09

## Scope covered

- **Asset inventory:** Seller routes under `routes/web.php` (`seller.*`) map to Blade views for overview, networks (index/create/edit), packages (index/create/edit), inventory list + CSV upload, orders + sales, withdrawals, wallet, and settings (`resources/views/seller/**`).
- **Navigation:** `config/navigation.php` seller entries point at named routes (`seller.dashboard`, `seller.networks.index`, `seller.packages.index`, `seller.inventory.index`, `seller.orders.index`, `seller.sales.index`, `seller.inventory.create`, `seller.withdrawals.index`, `seller.wallet.index`, `seller.settings.index`). Sidebar visibility uses `$user->can(...)` against Gates registered in `AppServiceProvider`; **`view_dashboard` must resolve via `DashboardPolicy::viewDashboard()`** (role-aware `hasPermission`), not `viewAdmin()`, and **`view_orders`** / customer-only permissions must map to real policy methods—otherwise sellers see almost no links.
- **Authorization:** `NetworkPolicy`, `PackagePolicy`, and `SellerPolicy` scope `seller_manager` access to the authenticated user’s `seller_id`. Controllers enforce matching `seller_id` on models where relevant.
- **Packages UX:** Creating a package requires at least one **active** network; otherwise the user is redirected to network creation with a flash message. Editing a package lists **active** networks plus the package’s current network (even if disabled) so assignments remain editable.
- **Wallet page:** Shows the seller settlement wallet (first `seller_manager` user’s wallet, by user id) and transaction list. **Estimated net after commission** is derived from **sold** cards’ `price` × `(100 − commission_rate)%` for quick insight; platform ledger balances and withdrawals remain authoritative for payouts—see disclaimer on the view.
- **Tests:** `tests/Feature/SellerPortalScopeTest.php` covers happy-path network + package creation, redirect when only disabled networks exist, 403 on foreign network/package edit, and settings profile + business PATCH flows.

## Honesty / backlog

- **Bulk upload:** Inventory import is **CSV** (RFC-style parsing in controller); Excel (`.xlsx`) is not a separate parser—users should export/save as CSV for bulk import.
- **Stress QA:** Row-by-row CSV error reporting is covered in code/UI; exhaustive edge-case files remain best validated with periodic manual runs alongside automated tests.
- **Financial reconciliation:** Seller wallet balance vs commission estimates should be periodically reconciled with admin audit tools when settlement rules change.

## Commands

```bash
php artisan test
```
