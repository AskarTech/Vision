# Implementation Phases

## Phase 1: Architecture and Domain Mapping

- Audit existing migrations, models, routes, actions, and services.
- Map current state vs required business domains.
- Capture conflicts between docs, current app, and legacy ideas.

## Phase 2: Domain Rules Stabilization

- Freeze wallet, checkout, reservation, seller, and admin rules.
- Define idempotency, auditability, and transaction boundaries.

## Phase 3: Database and Model Alignment

- Add missing constraints and indexes.
- Align model relationships, casts, and fillable fields.
- Keep schema changes incremental and backward-safe.

## Phase 4: Wallet and Ledger Engine

- Complete credit, debit, refund, adjustment flows.
- Introduce reusable wallet actions/services with idempotency support.
- Harden traceability and reconciliation paths.

## Phase 5: Inventory and Reservation

- Implement reservation lifecycle (reserve, confirm, release, expire).
- Add anti-oversell protections for high concurrency.
- Add release job/command for expired reservations.

## Phase 6: Checkout and Payment

- Complete gateway checkout actions and failure recovery.
- Standardize order lifecycle statuses and retry behavior.
- Add external reference and callback handling strategy.

## Phase 7: Seller Domain

- Build seller inventory management and bulk card upload flow.
- Add seller sales analytics and payout visibility.
- Complete withdrawal request lifecycle from seller side.

## Phase 8: Admin Operations

- Build operational dashboards for transactions and risk.
- Add review workflows for disputes, topups, and withdrawals.
- Improve audit views for incident handling.

## Phase 9: Hardening and Testing

- Add concurrency tests and failure-path tests.
- Improve logging, metrics, and observability.
- Validate performance on critical indexed read/write paths.

## Delivery Strategy

- Each phase is delivered in small verifiable increments.
- No large rewrites; preserve working behavior unless explicitly changed.
