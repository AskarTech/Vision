# Inventory Reservation

## Scope

Defines card inventory lifecycle to prevent oversell and race conditions.

## Inventory Entity

- cards table is the source of truth for individual stock units.

## Card Lifecycle

- active: available for allocation.
- reserved: temporarily held during checkout.
- sold: finalized and delivered.
- reported/refunded/disabled: post-sale operational states.

## Reservation Principles

- Allocation must use row-level locking.
- Reservation has explicit owner and expiration time.
- Expired reservations must be released back to active.
- Final sale transition must happen only from reserved/active under lock.

## Recommended Reservation Data

- reservation_token
- reserved_by_user_id
- reserved_at
- reservation_expires_at

## Checkout Interaction

1. Request arrives with idempotency key.
2. Validate packages and quantities.
3. Reserve required cards using lock strategy.
4. Execute payment (wallet/gateway).
5. Confirm reservation to sold on success.
6. Release reservations on failure.

## Concurrency Controls

- Use deterministic ordering when selecting stock.
- Keep lock scope minimal and inside transactions.
- Fail fast if stock is insufficient.

## Operational Recovery

- Background job/command releases expired reservations.
- Monitoring should track reservation leak rate and release latency.
