# Stage 3 — Database and Model Alignment

**Date:** 2026-05-09  

## Summary

Aligned Eloquent models with migrations (relations, casts), added **read-path indexes** for operational queues, and documented transaction expectations. No destructive schema changes.

## Constraints

- Existing foreign keys from migrations are unchanged (`card_order_items`, `wallets`, `wallet_transactions`, `topups`, `withdrawals`, `users.seller_id`, etc.).
- Cross-field rules (e.g. ledger `user_id` matches wallet owner) remain **application-enforced** inside Actions/Services and transactions—not DB CHECK constraints (portability and Laravel conventions).

## Indexes added

Migration `2026_05_09_120000_add_stage3_queue_indexes.php`:

| Table | Index | Purpose |
|-------|--------|---------|
| `card_orders` | `(status, created_at)` | Admin/customer order queues sorted by time |
| `topup_requests` | `(status, created_at)` | Topup review backlog |
| `withdrawals` | `(status, created_at)` | Withdrawal review backlog |

Existing indexes from earlier migrations (e.g. `cards(package_id, status, id)`, `wallet_transactions(wallet_id, type, status, id)`, `card_orders(status, reserved_at)`) are unchanged.

## Model relationships

| Model | Addition |
|-------|-----------|
| `Seller` | `withdrawals()` |
| `Package` | `orderItems()` → `CardOrderItem` |
| `WalletTransaction` | `cardOrder()` → `CardOrder` via `wallet_transaction_id` |
| `User` | `reviewedTopupRequests()`, `reviewedWithdrawals()` (audit / reviewer FKs) |

## Model casts

| Model | Change |
|-------|--------|
| `Wallet` | `points_balance` → `integer` |
| `WalletTransaction` | `points_amount` → `integer` |

## Transaction-safe patterns (verified)

- `CheckoutWithWalletAction`: single `DB::transaction`, wallet `lockForUpdate`, idempotent `external_reference`.
- Topup/withdrawal approve & reject: `DB::transaction` in Actions.
- `CardInventoryService`: reservation mutations intended to run inside caller transactions; `releaseExpiredReservations` uses per-row locking—run command inside scheduled transaction or accept short per-card commits (documented operational tradeoff).

## Backward compatibility

- New indexes are additive only.
- New relations are additive; no renamed columns or enum changes.

## References

- [STAGE_2_DOMAIN_RULES_FREEZE.md](STAGE_2_DOMAIN_RULES_FREEZE.md)
- [PROJECT_CONTEXT.md](PROJECT_CONTEXT.md) index constraints
