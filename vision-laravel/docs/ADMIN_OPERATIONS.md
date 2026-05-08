# Admin Operations

## Scope

Defines operational and governance flows for platform administration.

## Responsibilities

- Monitor users, sellers, and transaction health.
- Review topup requests and withdrawals.
- Handle disputes and refunds.
- Maintain auditability and operational visibility.

## Topup Review Flow

1. Request submitted as pending.
2. Admin validates receipt/reference.
3. Approve or reject with reason; persist **`reviewed_by`** and **`reviewed_at`** on every terminal transition.
4. On approval, ledger credit is recorded atomically.

## Withdrawal Review Flow

1. Seller submits withdrawal request.
2. Admin verifies balance/rules and payout details.
3. Approve/reject, then mark paid with reference when settled.
4. Preserve reviewer identity and timestamps.

## Dispute Handling Flow

1. Card issue reported.
2. Admin investigates evidence.
3. Resolve as refunded or rejected.
4. Refund path must produce wallet ledger entries.

## Audit and Compliance

- All review actions must be traceable.
- No silent finance mutation without an auditable event.
- Keep immutable references for internal and external payment traces.

## Monitoring Signals

- checkout failure rate
- duplicate idempotency attempts
- reservation leak indicators
- pending review backlog age
- refund/chargeback anomalies

## Incident Response

- Prioritize financial consistency first.
- Freeze risky flows if needed.
- Reconcile ledger/order/inventory after incident resolution.

## Admin UI Baseline

- A dedicated admin dashboard exists at `GET /admin`.
- The dashboard should surface metrics, pending review queues, operational alerts, and quick action entry points.
- Any future admin action screen should link back to these governance rules and preserve auditability.
