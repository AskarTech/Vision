# Engineering Rules

## Core Principles

- Build incrementally.
- Preserve working behavior unless change is intentional.
- Keep code testable, traceable, and maintainable.

## Architecture Constraints

- No business logic in controllers.
- Use Action and Service layers for domain behavior.
- Keep classes small and focused.
- Prefer explicit domain naming over generic helpers.

## Data Integrity

- Use DB transactions for all financial writes.
- Enforce idempotency for retryable operations.
- Use row-level locking where inventory can race.
- Maintain append-oriented ledger records for wallet activity.

## Change Management

- Inspect related files before editing.
- Limit each change-set to one logical step.
- Avoid broad refactors in the same PR as behavior changes.
- Update docs when behavior or flow changes.

## Testing Rules

- Add or update tests with each feature change.
- Cover happy path, failure path, and retry/idempotency path.
- Add concurrency-focused tests for stock and wallet critical sections.

## Performance and Indexing

- Preserve indexed query paths for stock and ledger reports.
- Review index impact whenever schema changes.
- Avoid N+1 data loading in API/domain flows.

## Security and Ops

- Avoid trusting user-supplied identifiers when auth context is available.
- Ensure admin decisions are auditable.
- Log finance-critical failures with actionable context.
