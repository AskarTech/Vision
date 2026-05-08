# Checkout Flow

## Scope

Defines order lifecycle and payment orchestration for card purchases.

## Inputs

- user identity
- cart items (package + quantity)
- idempotency key
- payment channel
- payment mode: internal wallet, points, or direct provider

## Validation Layer

- User must be active.
- Package must exist and be active.
- Quantity must be positive and capped.

## Core Lifecycle

1. Request validation.
2. Idempotency check.
3. Compute totals server-side.
4. Reserve or allocate stock with `lockForUpdate()` inside an atomic DB transaction.
5. Execute payment using the selected wallet/points/provider flow.
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
- Points redemption is applied in the same DB transaction via `WalletService::debitForPurchase` (API field `points_to_redeem`; conversion `config/marketplace.php` / `MARKETPLACE_POINTS_CASH_PER_1000`).

## Gateway Checkout Notes

- Requires external reference and callback verification.
- Must handle timeout/retry without duplicate charge.
- Must support compensation when external confirm fails after local reserve.
- **`card_orders.payment_channel` (schema today):** `platform_wallet`, `floosak`, `jawali`, `bank_transfer`. Product strategy may name Kuraimi, OneCash, Cash, etc.; map to these channels or extend the enum via migration.
- Gateway flows must still respect atomic reservation and rollback behavior.

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
