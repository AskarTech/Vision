# Stages 5–10 — Delivery Summary (2026-05-09)

This document closes **[MASTER_SQUAD_PLAN.md](../MASTER_SQUAD_PLAN.md)** checklist items against **actual behavior**, noting deliberate stubs or external work.

---

## Stage 5 — Inventory and Reservation System

| Item | Status | Evidence |
|------|--------|----------|
| Stock reservation | Done | `CardInventoryService::reserveNextActiveCard` → `reserved` + expiry |
| Checkout reservation flow | Done | `CheckoutWithWalletAction` reserves → confirms inside same DB transaction |
| Anti-oversell | Done | `lockNextActiveCard` + unique codes + transactional rollback |
| Reservation expiration / release | Done | `releaseExpiredReservations`, artisan `reservations:release-expired` |
| `lockForUpdate()` | Done | Active row lock before transition |
| Atomic checkout + reservation | Done | Single outer transaction around order + cards + wallet debit |
| Failed payment releases stock | Wallet path | Failure before commit rolls back card transitions |
| Successful payment finalizes | Done | `confirmReservedCard` → `sold` |
| Operational cleanup | Done | `Schedule::command('reservations:release-expired')->everyFiveMinutes()` in `routes/console.php` |

---

## Stage 6 — Checkout and Payment Flow

| Item | Status | Notes |
|------|--------|-------|
| Internal wallet checkout | Done | Web customer buy + `POST /api/v1/checkout/wallet` |
| Points redemption | Done | `points_to_redeem` on API; `MarketplacePoints::allocateTowardsOrder`; `WalletService::debitForPurchase`; `config/marketplace.php` → `MARKETPLACE_POINTS_CASH_PER_1000` |
| External gateway flow | Stub | `POST /api/v1/checkout/gateway/init` returns **503** + `gateway_stub` until drivers exist |
| Provider adapters (Kuraimi, OneCash, Floosak, Cash) | Stub config | `config/marketplace.php` `payment_gateways` registry + `.env` driver keys |
| Callback verification / signatures | Deferred | Implement per PSP when drivers ship |
| Timeout/retry without double charge | Partial | Wallet path: idempotency keys; gateway: future |
| Atomic reserve + pay + confirm | Wallet path | Single transaction for wallet checkout |
| Compensation after external failure | Deferred | Requires real gateway + saga/outbox pattern |

---

## Stage 7 — Seller Domain and Operations

| Item | Status | Notes |
|------|--------|-------|
| Seller networks UI | Read-focused | Route `seller.networks.index` (listing stub); admin owns CRUD |
| Seller inventory | Done | `Seller\InventoryController` CRUD-ish + generation |
| CSV voucher import | Done | `ImportCardsFromCsvAction` + `POST seller/inventory/import-csv` + UI on inventory create |
| Excel import | Deferred | CSV baseline; Excel can reuse same parser later |
| Row validation / partial failure | Partial | Per-row errors collected; response flashes count + sample errors |
| Import audit batch trail | Partial | `cards.meta.import_batch = csv` |
| Seller orders | Done | `Seller\OrdersController` index/show |
| Seller wallet / withdrawals | Done | Wallet view + withdrawal flows (settlement wallet accessor) |
| Analytics / payout overview | Partial | Sales/wallet views stubbed or lightweight; expand as needed |

---

## Stage 8 — Admin Operations and Governance

| Item | Status | Notes |
|------|--------|-------|
| Monitoring dashboard | Done | `Admin\DashboardController` metrics + pending topups/withdrawals + recent orders + card alerts |
| Topup / withdrawal audit fields | Done | `reviewed_by` / `reviewed_at` on approve/reject actions |
| Disputes / refunds | Done | Admin disputes + `ResolveDisputeAction` + ledger refund |
| Traceable finance actions | Done | Ledger + reviewer metadata + structured `Log::info` on wallet mutations |
| Audit views | Done | `Admin\AuditController` routes for wallet TX, topups, withdrawals, orders |
| Operational alerting surfaces | Partial | Dashboard queues + notifications/activity views stubbed |
| Reservation leak / idempotency KPIs | Partial | Ops rely on `cards_reserved` counts + logs; add metrics backend later |

---

## Stage 9 — Hardening and Testing

| Item | Status | Notes |
|------|--------|-------|
| Stock / checkout failure | Done | `CheckoutInsufficientStockTest` |
| Wallet idempotency | Done | `WalletServiceTest` + `CheckoutWithWalletTest` |
| Points checkout | Done | `CheckoutWithPointsTest` |
| Gateway stub contract | Done | `GatewayCheckoutStubTest` |
| Policy coverage | Done | `TopupRequestPolicyTest` |
| Concurrency stress | Partial | SQLite serializes writers; add MySQL-specific parallel tests if needed |
| Finance logging | Done | `wallet.debit`, `wallet.credit`, `wallet.refund`, `wallet.debit_checkout` |
| Observability metrics | Deferred | Hook logs/metrics to your APM when infra exists |

---

## Stage 10 — Release Readiness and Runbook

See **[RUNBOOK_RELEASE.md](RUNBOOK_RELEASE.md)** for migration rollback posture, environment checks, CDN/fonts exception, provider placeholders, points env, incident prompts, and go-live checklist.

---

## Key files added or touched

- `config/marketplace.php`
- `app/Support/MarketplacePoints.php`
- `app/Services/Wallet/WalletService.php` (`debitForPurchase`, logging)
- `app/Actions/Checkout/CheckoutWithWalletAction.php` (points)
- `app/Http/Controllers/Api/GatewayCheckoutController.php`
- `app/Actions/Card/ImportCardsFromCsvAction.php`
- `routes/console.php` (schedule)
- `routes/api.php`, `routes/web.php`
- `resources/views/seller/inventory/create.blade.php` (CSV upload)
