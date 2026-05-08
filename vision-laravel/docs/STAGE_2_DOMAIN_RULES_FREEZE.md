# Stage 2 — Domain Rules Stabilization (Frozen Baseline)

**Date:** 2026-05-09  
**Authority:** Schema enums + implemented Actions/Services. Product strategy may extend enums later via migrations.

This document **freezes** interpretable rules so UI, APIs, and docs use one vocabulary. If behavior changes, update this file and `DOMAIN_RULES.md` together.

---

## 1. Wallet rules (frozen)

- **Balance fields:** `wallets.balance` (decimal cash), `wallets.points_balance` (unsigned integer). Both are operational counters; **every cash movement that changes `balance` must have a `wallet_transactions` row** with matching `balance_before` / `balance_after`.
- **Wallet status:** `active` | `frozen`. Checkout and debit require `active`.
- **Writes:** Wallet mutation goes through `WalletService` (or successor) inside `DB::transaction`; callers must not update `wallets.balance` without a ledger row.
- **Ledger `wallet_transactions.type` (schema):** `credit`, `debit`, `hold`, `release`, `refund`, `adjustment`. Points are modeled via `points_amount` and/or `channel` (e.g. `reward_points`), **not** separate `points_*` type enum values until a migration adds them.
- **Ledger `wallet_transactions.status`:** `pending`, `approved`, `rejected`, `failed`, `reversed`. Immediate checkout debits use `approved` when committed.
- **Ledger `wallet_transactions.channel`:** `platform_wallet`, `bank_transfer`, `floosak`, `jawali`, `manual_admin`, `reward_points`.
- **Refunds:** Expressed as ledger entries (`type` = `refund` or compensating `credit` with clear `reference_type` / `reference_id`), not silent balance edits.
- **Points:** Product rule remains *points participate in checkout through the same ledger model*; conversion rate `1000 points = X amount` is configuration (see Stage 10). Implementation must not bypass the ledger when points affect cash equivalence.

---

## 2. Order lifecycle (`card_orders`) — frozen

- **`status`:** `pending` | `paid` | `failed` | `cancelled`.
- **`payment_channel` (schema):** `platform_wallet`, `floosak`, `jawali`, `bank_transfer`. Strategy docs may name additional brands (e.g. Kuraimi, OneCash); map them to these channels or extend the enum via migration.
- **Wallet checkout (`CheckoutWithWalletAction`):** Creates order as `paid`, sets `paid_at`, links `wallet_transaction_id` after debit. Idempotency via `external_reference` (unique).
- **Default migration default:** `status` default is `paid` historically; new gateway flows should explicitly set `pending` until payment confirms.

---

## 3. Reservation lifecycle (`cards`) — frozen

- **`cards.status`:** `active` → allocatable; `reserved` → held for a user until expiry or confirm; `sold` → delivered; `reported`, `refunded`, `disabled` → operational post-sale or blocked.
- **Reservation fields:** `reserved_by_user_id`, `reserved_at`, `reservation_expires_at`.
- **Allocation:** `CardInventoryService::lockNextActiveCard` uses `lockForUpdate()` on an `active` row; reserve sets `reserved`; confirm sets `sold` and `sold_to_user_id` / `sold_at`.
- **Release:** `releaseReservedCard` clears reservation fields and returns to `active`. **Expired:** `releaseExpiredReservations()` plus scheduled/manual `reservations:release-expired`.
- **Wallet checkout atomicity note:** Current implementation creates the order, allocates/confirms cards, then debits. A failure **after** confirm could leave inconsistent state; gateway flows should tighten ordering (reserve → pay → confirm) in a later stage.

---

## 4. Seller lifecycle — frozen

- **`sellers.status`:** `active` | `disabled`. Only **active** sellers (and active networks/packages) participate in sales per `DOMAIN_RULES.md`.
- **Settlement wallet:** There is **no** `seller_id` on `wallets`. Platform rule: **seller monetary balance for payouts is the wallet of the designated seller_manager user** (deterministic: first `seller_manager` by `id` for that `seller_id`). Application exposes this as `$seller->wallet` accessor; multi-manager treasury rules require a future migration or explicit designation.

---

## 5. Withdrawal flow — frozen

- **`withdrawals.status`:** `pending` → `approved` | `rejected`; optional operational `paid` when payout is executed externally.
- **Approve:** Validates pending state and sufficient settlement wallet balance; debits via `WalletService::debit`; must set `reviewed_by`, `reviewed_at`.
- **Reject:** Sets `rejected`, `rejection_reason`, `reviewed_by`, `reviewed_at`.

---

## 6. Topup flow — frozen

- **`topup_requests.status`:** `pending` → `approved` | `rejected`.
- **Approve:** Credits customer wallet via `WalletService::credit` with channel `manual_admin`; sets `reviewed_by`, `reviewed_at`.
- **Reject:** Sets rejection fields and `reviewed_by`, `reviewed_at`.

---

## 7. Transaction consistency — frozen

- **Single DB transaction** for: approve/reject topup (credit only on approve); approve/reject withdrawal (debit only on approve); wallet checkout order path (order + items + debit in one transaction).
- **Isolation:** Wallet row locked with `lockForUpdate()` before balance checks where contention matters (checkout).
- **Compensation:** Failed external payments must release reservations and avoid orphan `paid` orders (gateway phase).

---

## 8. Idempotency — frozen

- **Wallet API checkout:** Client supplies `idempotency_key`; server builds scoped `external_reference` (e.g. `wallet_checkout:{user_id}:{key}`), unique on `card_orders`. Duplicate insert returns existing order (duplicate key handling + pre-check).
- **Gateway (future):** Same pattern: unique external reference per payment intent; retries must not double-charge or double-allocate.

---

## 9. Auditability — frozen

- **Admin finance actions** on topups and withdrawals record **`reviewed_by`** (FK to `users`) and **`reviewed_at`**.
- **Ledger:** `wallet_transactions` retains `reference_type` / `reference_id`, `external_reference`, `processed_at`, `description`.
- **No silent** approve/reject without state transition + reviewer metadata where schema provides columns.

---

## 10. Validation boundaries — frozen

| Actor | Typical gates |
|--------|----------------|
| **Customer** | `auth`, `role:customer`, `users.status = active`, wallet `active`, package/network/seller `active`, sufficient balance for wallet checkout |
| **Seller manager** | `auth`, `role:seller_manager`, row scoped by `seller_id`, policies on withdrawal/inventory views |
| **Admin** | `auth`, `role:admin`, policies on approve/reject/dispute |

Server-side totals and prices are authoritative; clients send identifiers and quantities only.

---

## 11. Status vocabulary matrix (canonical)

| Entity | Column | Values |
|--------|--------|--------|
| `users` | `role` | `customer`, `seller_manager`, `admin` |
| `users` | `status` | `active`, `disabled` |
| `wallets` | `status` | `active`, `frozen` |
| `sellers` | `status` | `active`, `disabled` |
| `networks` | `status` | `active`, `disabled` |
| `packages` | `status` | `active`, `disabled` |
| `cards` | `status` | `active`, `reserved`, `sold`, `reported`, `refunded`, `disabled` |
| `card_orders` | `status` | `pending`, `paid`, `failed`, `cancelled` |
| `card_orders` | `payment_channel` | `platform_wallet`, `floosak`, `jawali`, `bank_transfer` |
| `wallet_transactions` | `type` | `credit`, `debit`, `hold`, `release`, `refund`, `adjustment` |
| `wallet_transactions` | `status` | `pending`, `approved`, `rejected`, `failed`, `reversed` |
| `topup_requests` | `status` | `pending`, `approved`, `rejected` |
| `withdrawals` | `status` | `pending`, `approved`, `rejected`, `paid` |

UI filters and Blade must use **only** these literals.

---

## References

- Implementation: `CheckoutWithWalletAction`, `WalletService`, `CardInventoryService`, `ApproveTopupAction`, `RejectTopupAction`, `ApproveWithdrawalAction`, `RejectWithdrawalAction`.
- Related docs: `DOMAIN_RULES.md`, `WALLET_ARCHITECTURE.md`, `INVENTORY_RESERVATION.md`, `CHECKOUT_FLOW.md`, `SELLER_DOMAIN.md`, `ADMIN_OPERATIONS.md`.
