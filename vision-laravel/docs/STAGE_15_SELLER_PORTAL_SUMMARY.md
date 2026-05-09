# Stage 15 — Seller Portal: Verification & QA

**Date:** 2026-05-09

## Seller URLs (expected working pages)

| Path | Route name | Purpose |
|------|------------|---------|
| `GET /seller` | `seller.dashboard` | Overview |
| `GET /seller/networks` | `seller.networks.index` | Networks list |
| `GET /seller/networks/create` | `seller.networks.create` | New network |
| `GET /seller/networks/{id}/edit` | `seller.networks.edit` | Edit network |
| `GET /seller/packages` | `seller.packages.index` | Packages |
| `GET /seller/packages/create` | `seller.packages.create` | New package |
| `GET /seller/packages/{id}/edit` | `seller.packages.edit` | Edit package |
| `GET /seller/inventory` | `seller.inventory.index` | Cards list |
| `GET /seller/inventory/create` | `seller.inventory.create` | Bulk / generate |
| `GET /seller/orders` | `seller.orders.index` | Orders |
| `GET /seller/orders/{order}` | `seller.orders.show` | Order detail |
| `GET /seller/sales` | `seller.sales.index` | Sales stats |
| `GET /seller/withdrawals` | `seller.withdrawals.index` | Withdrawals |
| `GET /seller/withdrawals/create` | `seller.withdrawals.create` | New withdrawal |
| `GET /seller/withdrawals/{id}` | `seller.withdrawals.show` | Withdrawal detail |
| `GET /seller/wallet` | `seller.wallet.index` | Wallet + ledger peek |
| `GET /seller/settings` | `seller.settings.index` | Profile + business |

There is **no** separate seller “package show” or “network show” page by design (edit flows cover management).

## Scope covered

- **Asset inventory:** Seller routes under `routes/web.php` (`seller.*`) map to Blade views for overview, networks (index/create/edit), packages (index/create/edit), inventory list + CSV upload, orders + sales, withdrawals, wallet, and settings (`resources/views/seller/**`).
- **Navigation:** `config/navigation.php` seller entries point at named routes (`seller.dashboard`, `seller.networks.index`, `seller.packages.index`, `seller.inventory.index`, `seller.orders.index`, `seller.sales.index`, `seller.inventory.create`, `seller.withdrawals.index`, `seller.wallet.index`, `seller.settings.index`). Sidebar visibility uses `$user->can(...)` against Gates registered in `AppServiceProvider`; **`view_dashboard` must resolve via `DashboardPolicy::viewDashboard()`** (role-aware `hasPermission`), not `viewAdmin()`, and **`view_orders`** / customer-only permissions must map to real policy methods—otherwise sellers see almost no links.
- **Account linkage:** Middleware `seller.linked` (`EnsureSellerManagerLinkedToSeller`) runs on all `/seller/*` routes. If `users.seller_id` is null or points at a missing `sellers` row, the user sees `seller/account-incomplete` (403 with Arabic explanation) instead of scattered opaque failures across controllers/policies.
- **Authorization:** `NetworkPolicy`, `PackagePolicy`, and `SellerPolicy` scope `seller_manager` access to the authenticated user’s `seller_id`. Controllers enforce matching `seller_id` on models where relevant.
- **Packages UX:** Creating a package requires at least one **active** network; otherwise the user is redirected to network creation with a flash message. Editing a package lists **active** networks plus the package’s current network (even if disabled) so assignments remain editable.
- **Wallet page:** Shows the seller settlement wallet (first `seller_manager` user’s wallet, by user id) and transaction list. **Estimated net after commission** is derived from **sold** cards’ `price` × `(100 − commission_rate)%` for quick insight; platform ledger balances and withdrawals remain authoritative for payouts—see disclaimer on the view.
- **Tests:** `tests/Feature/SellerPortalScopeTest.php` covers happy-path network + package creation, redirect when only disabled networks exist, 403 on foreign network/package edit, and settings profile + business PATCH flows. `tests/Feature/SellerAccountLinkageTest.php` covers unlinked `seller_manager` accounts.

## Honesty / backlog

- **Bulk upload:** Inventory import is **CSV** (RFC-style parsing in controller); Excel (`.xlsx`) is not a separate parser—users should export/save as CSV for bulk import.
- **Stress QA:** Row-by-row CSV error reporting is covered in code/UI; exhaustive edge-case files remain best validated with periodic manual runs alongside automated tests.
- **Financial reconciliation:** Seller wallet balance vs commission estimates should be periodically reconciled with admin audit tools when settlement rules change.

## Commands

```bash
php artisan test
```
