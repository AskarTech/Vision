# Checkout Flow

## Scope

Defines order lifecycle and payment orchestration for card purchases.

## Inputs

- user identity
- cart items (package + quantity)
- idempotency key
- payment channel

## Validation Layer

- User must be active.
- Package must exist and be active.
- Quantity must be positive and capped.

## Core Lifecycle

1. Request validation.
2. Idempotency check.
3. Compute totals server-side.
4. Reserve or allocate stock with lock strategy.
5. Execute payment.
6. Create/confirm card order.
7. Write ledger and response payload.

## Status Model

- pending: initiated, not finalized.
- paid: payment completed and stock finalized.
- failed: checkout failed and rolled back/released.
- cancelled: user/system cancellation.

## Wallet Checkout Notes

- Must debit once per idempotency key.
- Must link order to wallet transaction.
- Must return stable order reference for retries.

## Gateway Checkout Notes

- Requires external reference and callback verification.
- Must handle timeout/retry without duplicate charge.
- Must support compensation when external confirm fails after local reserve.

## Error Handling

- Validation errors: 422 with clear reason.
- Business rule violations: deterministic error messages.
- Unexpected errors: rollback and observable logs.

## Testing Matrix

- successful checkout
- insufficient balance
- insufficient stock
- duplicate idempotency key retry
- concurrent checkout contention
