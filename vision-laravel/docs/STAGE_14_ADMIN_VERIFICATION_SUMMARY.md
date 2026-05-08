# Stage 14 — Admin Control Center: Verification & QA

**Date:** 2026-05-09

## Scope covered

- **Asset inventory:** Admin routes in `routes/web.php` map to existing Blade views under `resources/views/admin/` (dashboard, customers, sellers, networks, packages, inventory, orders, topups, withdrawals, disputes, reports, audit, settings, plus notifications / roles / activity placeholder pages).
- **Route correctness:** Static paths are registered **before** `{model}` segments so `…/create`, `…/export`, and `…/bulk-update` are not captured as IDs (`sellers`, `networks`, `packages`, `inventory`).
- **Authorization:** Base `App\Http\Controllers\Controller` now uses `AuthorizesRequests`, so `$this->authorize()` works consistently on admin (and other) controllers.
- **Financial actions:**
  - **Paid order refund:** `App\Actions\Admin\RefundPaidOrderAction` runs inside `DB::transaction`, reverses the checkout debit via `WalletService::reverseCheckoutDebit` (cash + **points**), restores sold cards to `active`, sets order `cancelled`.
  - **Inventory bulk status:** Wrapped in `DB::transaction`.
  - Topup / withdrawal approval paths remain in existing Actions (already transactional).
- **Inventory export:** `GET /admin/inventory/export` streams UTF-8 CSV (with BOM) using the same filters as the index query (no “coming soon” redirect).
- **Policies:** `CardPolicy` defines `updateBulk` and `export` so admin inventory bulk/export gates resolve correctly.
- **Arabic UX:** Shared admin flash strings live in `lang/ar/admin.php`; topups, withdrawals, disputes, sellers, orders cancel/refund success paths use `__()`.
- **Tests:** `tests/Feature/Stage14AdminVerificationTest.php` — seller_manager forbidden on admin; create/export routes return 200; topup reject validation (`reason` max 500); full **points + cash** checkout then admin refund restores wallet and card.

## Honesty / backlog

- **`admin.notifications`:** Operational hub with live counts + deep links (no separate notification engine).
- **`admin.activity`:** Recent wallet transactions + filters; full audit remains under **`admin.audit`**.
- **`admin.roles`:** Arabic documentation of Gates/`role` middleware (no visual RBAC editor yet).
- **Non–`platform_wallet` orders:** Admin refund action refuses automatic reversal; gateway-paid refunds remain a product/integration task.
- **Manual QA:** Stage 14 checklist included visual inspection of every table/filter — automated tests cover critical paths; periodic manual RTL and copy review is still recommended.

## Commands

```bash
php artisan test
```
