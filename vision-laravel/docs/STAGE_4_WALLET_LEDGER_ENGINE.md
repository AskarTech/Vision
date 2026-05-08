# Stage 4 — Wallet and Ledger Engine

**Date:** 2026-05-09  

## Summary

Centralized cash mutations in `WalletService` with row locking, append-only ledger rows, deterministic **`external_reference`** keys for retry-safe credits/debits/refunds, explicit **`refund` ledger type**, guarded wallet mass assignment, and a **ledger chain integrity** helper for reconciliation.

## Implemented behavior

| Requirement | Implementation |
|-------------|----------------|
| Wallet transactions / ledger | All cash balance changes go through `WalletService::credit`, `::debit`, or `::refund` (writes `wallet_transactions` + updates `wallets.balance` via query builder). |
| Atomic operations | Each public method runs inside `DB::transaction`; wallet row loaded with `lockForUpdate()` before read/modify/write. |
| Idempotency | `credit` / `debit`: optional `external_reference` — second call returns existing row for same wallet (cross-wallet collision throws). `refund`: default key `ledger_refund:{ModelBasename}:{referenceId}` or custom key. |
| Refund flows | `refund()` writes `type = refund`, `channel = manual_admin`, increases balance; idempotent by external reference. |
| Tracing | Optional `meta` array on credit/debit/refund merged into ledger `meta` (e.g. `reference_code` on topup approval). |
| Reconciliation | `WalletService::ledgerChainMatchesBalance(Wallet)` verifies contiguous `balance_before` → `balance_after` across approved rows vs current `wallets.balance`. |
| No silent balance edits | `Wallet::$fillable` excludes `balance` and `points_balance`; service persists balance with `Wallet::query()->whereKey()->update(...)`. Tests/fixtures may use `Wallet::forceCreate(...)`. |
| Checkout retries | Order-level idempotency unchanged (`card_orders.external_reference`); wallet debit additionally uses `wallet_debit:card_order:{id}`. |

## External reference conventions (internal)

- Checkout debit: `wallet_debit:card_order:{order_id}`
- Topup approval credit: `topup_approved:{topup_id}`
- Withdrawal debit: `withdrawal_debit:{withdrawal_id}`
- Refund (default): `ledger_refund:{ReferenceBasename}:{reference_id}`
- Demo seed opening credits: `demo_seed_opening:user:{user_id}`
- Demo historic orders: `demo_wallet_debit:card_order:{order_id}`

## Tests

- `tests/Unit/WalletServiceTest.php` — idempotent credit, refund type + idempotency, ledger chain check.
- `tests/Feature/CheckoutWithWalletTest.php` — unchanged behavior expectations for wallet balance after checkout.

## References

- [WALLET_ARCHITECTURE.md](WALLET_ARCHITECTURE.md)
- [STAGE_2_DOMAIN_RULES_FREEZE.md](STAGE_2_DOMAIN_RULES_FREEZE.md)
