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

- credit
- debit
- hold
- release
- refund
- adjustment

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

## Future Extensions

- Multi-currency support.
- Points to cash conversion rules.
- Transfer between wallets with double-entry style constraints.
