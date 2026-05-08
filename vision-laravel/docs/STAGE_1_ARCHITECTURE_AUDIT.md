# Stage 1 — Architecture and Domain Mapping Audit

**Date:** 2026-05-09  
**Scope:** `vision-laravel` application layer vs `docs/` and `.cursor/rules/`.

## 1. Migrations (audited)

Nineteen migration files under `database/migrations/` cover:

- Identity: `users`, password reset tables (framework), `seller_managers`, seller FK on users
- Marketplace supply: `sellers`, `networks`, `packages`, `cards`
- Reservation extensions: `cards` reservation fields, `card_orders.reserved_at`
- Commerce: `card_orders`, `card_order_items`
- Wallet / finance: `wallets`, `wallet_transactions`, `topup_requests`, `withdrawals`, `banks`, `payment_gateways`
- Framework: `jobs`, `cache`

**Note:** No dedicated `disputes` table; dispute/refund behavior is expected to ride on `card_orders` + ledger actions (see controllers under `Admin\DisputesController`).

## 2. Models (audited)

Fourteen models in `app/Models/` align with the tables above (`User`, `Seller`, `SellerManager`, `Network`, `Package`, `Card`, `Wallet`, `WalletTransaction`, `CardOrder`, `CardOrderItem`, `TopupRequest`, `Withdrawal`, `Bank`, `PaymentGateway`).

## 3. Routes (audited)

`routes/web.php` defines:

- Guest auth: login, register, password reset
- Customer (`auth` + `role:customer`): dashboard, marketplace (index/show/buy), orders, wallet, profile
- Admin (`auth` + `role:admin`): dashboard, topups, withdrawals, disputes, customers, sellers, networks, packages, inventory, orders, reports, audit, settings, plus stubs per navigation (activity, notifications, roles as applicable)
- Seller (`auth` + `role:seller_manager`): dashboard area, inventory, orders, withdrawals, wallet/sales/settings views per routes file tail

API checkout routes (e.g. wallet idempotency) should remain documented in `PROJECT_CONTEXT.md`; web routes are Blade/session-first.

## 4. Actions (audited)

`app/Actions/`:

- `Checkout\CheckoutWithWalletAction`
- `Card\GenerateCardsAction`
- `Topup\ApproveTopupAction`, `Topup\RejectTopupAction`
- `Withdrawal\ApproveWithdrawalAction`, `Withdrawal\RejectWithdrawalAction`
- `Dispute\ResolveDisputeAction`

**Gap vs docs:** `PROJECT_CONTEXT.md` lists desirable actions such as `CheckoutWithGatewayAction`, `RequestTopupAction`, `ReportCardIssueAction` — not all are present as named classes; some flows may live in controllers/services.

## 5. Services (audited)

- `App\Services\Wallet\WalletService`
- `App\Services\Cards\CardInventoryService`

Inventory reservation / release semantics remain governed by `docs/INVENTORY_RESERVATION.md` and `ReleaseExpiredReservations` console command.

## 6. Domain mapping

| Domain        | Primary persistence                         | Application entrypoints |
|---------------|---------------------------------------------|-------------------------|
| Users / IAM   | `users`, `seller_managers`                  | Middleware `role:*`, policies |
| Sellers       | `sellers`                                   | Admin + seller routes |
| Networks      | `networks`                                  | Admin CRUD; seller read-only UI |
| Packages      | `packages`                                  | Admin CRUD; marketplace |
| Cards         | `cards`                                     | Inventory, checkout allocation |
| Orders        | `card_orders`, `card_order_items`         | Customer/admin/seller controllers |
| Wallets       | `wallets`, `wallet_transactions`          | `WalletService`, customer wallet |
| Topups        | `topup_requests`                          | Admin approve/reject actions |
| Withdrawals   | `withdrawals`, `banks`                    | Admin + seller flows |
| Payments      | `payment_gateways`                        | Settings/UI; gateway adapters TBD |

## 7. Architecture alignment

- **PHP / Laravel:** `composer.json` requires `php:^8.4`, `laravel/framework:^13` — matches documentation standard.
- **Patterns:** Feature areas use dedicated controllers; mutations for wallet/checkout/topups/disputes prefer Actions + Services — aligned with `.cursor/rules/backend-business-logic.mdc`.
- **UI stack:** Blade views, Tailwind CSS v4 (`@import "tailwindcss"`), DaisyUI via `@plugin "daisyui"` in `resources/css/app.css`, Alpine.js in layouts — aligned with project docs.

## 8. Conflicts and open points

| Topic | Conflict / gap |
|--------|----------------|
| Zero CDN | Docs emphasize local bundles; several layouts load **Google Fonts** (`fonts.googleapis.com`) for Cairo. Track under Stage 10 or self-host subset fonts. |
| External checkout | Stage 6 items (Kuraimi, OneCash, etc.) not implemented in reviewed Actions list. |
| Points redemption | Documented as core in strategy; verify single source of truth in checkout code vs docs. |
| Status vocabulary | Enum values must stay consistent with migrations; UI filters must not invent statuses (see prior disputes/orders fixes). |

## 9. Conclusion

Stage 1 checklist items are satisfied **with documented gaps** above. Later stages should close gaps without breaking ledger/reservation invariants.
