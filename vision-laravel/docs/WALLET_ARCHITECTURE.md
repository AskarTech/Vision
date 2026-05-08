# Wallet Architecture

## Scope

Defines wallet domain behavior for balance, ledger, and consistency.

## Core Entities

- wallets: account state for each user.
- wallet_transactions: append-style ledger of money/points operations.

## Design Principles

- Balance is a derived operational value backed by ledger events.
- Every balance change must have a matching wallet_transactions row.
- Financial writes are atomic and transaction-safe.

## Transaction Types

Schema enum on `wallet_transactions.type` (frozen):

- credit
- debit
- hold
- release
- refund
- adjustment

**Points:** Use `points_amount` on the ledger row and/or `channel = reward_points`. Do not rely on separate `points_credit` / `points_debit` type values unless a future migration adds them.

## Status Model

- pending
- approved
- rejected
- failed
- reversed

## Idempotency

- Retryable operations must include idempotency key.
- External and internal references must be unique where needed.
- Duplicate requests should return prior success state without double write.

### Implementation (`WalletService`)

- `wallet_transactions.external_reference` is globally unique when set; `credit` / `debit` / `refund` resolve duplicates inside a DB transaction after locking the wallet and return the existing row when the reference matches the same wallet.
- Checkout debits use `wallet_debit:card_order:{id}` in addition to order-level idempotency.

## Refunds

Compensating customer balance increases use ledger **`type = refund`** (via `WalletService::refund`), not a generic `credit`, unless a future product rule explicitly merges those flows.

## Balance persistence guard

`wallets.balance` is not mass-assignable on the `Wallet` model; the service updates balance through the query builder so ledger-accompanied writes are the supported path. Opening wallets at **zero** use normal creates with DB defaults; tests/fixtures may use `Wallet::forceCreate`.

## Points Engine

- Points are a core wallet-adjacent product feature.
- Points must be redeemable inside checkout flows.
- `1000 points = X amount`, where X is a configurable cash equivalent stored centrally.
- Points changes must create wallet_transactions entries or a linked ledger equivalent.

## Atomic Flow Pattern

1. Lock wallet row.
2. Validate balance and wallet status.
3. Compute before/after balances.
4. Write ledger entry.
5. Update wallet balance and activity timestamp.
6. Commit transaction.

## Reconciliation

- Reconciliation reports should compare wallet balance against ledger aggregates.
- Any mismatch should be reported and investigated with trace IDs.

## Checkout Integration

- Checkout must resolve wallet balance, points redemption, and payment channel before final confirmation.
- Gateway retries and wallet retries must be idempotent.
- No silent recalculation is allowed after the ledger write is committed.

## Future Extensions

- Multi-currency support.
- Points to cash conversion rules.
- Transfer between wallets with double-entry style constraints.
