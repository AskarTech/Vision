# Domain Rules

## Purpose

This document defines stable business rules for YemenWi-Fi Hub domains.

## Marketplace Rules

- Only active sellers, networks, and packages can be sold.
- Each card belongs to exactly one seller, one network, and one package.
- A card code must remain globally unique.
- A sold card is immutable for ownership history purposes.

## Wallet Rules

- Wallet writes must happen inside DB transactions.
- Every money movement must produce a wallet_transactions record.
- No direct balance mutation is allowed without ledger entry.
- Wallet checkout must be idempotent by request key.
- Refunds must be represented as explicit ledger entries, never silent updates.
- Points are a core platform feature and must be applied through the same auditable ledger model.
- `1000 points = X amount`, where X is a centrally configured cash equivalent.

## Inventory Rules

- Card allocation must use row-level locking with `lockForUpdate()`.
- A card cannot be sold twice.
- Reservation and sale must follow a defined lifecycle.
- Expired reservations must be released in a deterministic way.

## Checkout Rules

- Checkout must validate user status and wallet status before allocation.
- Total amount must be computed server-side from active package price.
- If allocation fails for any item, the full transaction must rollback.
- Successful checkout must produce card_order, card_order_items, and wallet_transactions.
- Checkout must support both internal wallet + points and direct provider payment flows.
- Direct payment providers are Kuraimi, OneCash, Floosak, and Cash.
- Reservation and payment confirmation must remain atomic.

## Seller Rules

- Seller managers act only within their assigned seller scope.
- Seller withdrawals must be auditable and reviewable.
- Seller reporting must rely on indexed tables and immutable financial records.
- Seller card imports must support CSV/Excel, validation, partial failure handling, and audit logging.

## Admin Rules

- Admin review actions (topup, withdrawal, disputes) must be traceable.
- Review actions must include reviewer identity and timestamp.
- No destructive finance operation without an audit trail.

## Rule Governance

- Any behavioral change must update this file and related architecture docs.
- In conflicts: docs are source of truth, legacy is reference-only.

## Frozen baseline (Stage 2)

Operational enums, idempotency patterns, settlement-wallet semantics, and audit fields for admin finance actions are documented in [STAGE_2_DOMAIN_RULES_FREEZE.md](STAGE_2_DOMAIN_RULES_FREEZE.md). Prefer that matrix when implementing filters, policies, and migrations.
