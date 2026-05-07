# Context Workflow

## Goal
Keep AI and human contributors aligned with full project context in every session.

## Session Start Checklist
1. Read `docs/PROJECT_CONTEXT.md`.
2. Read latest migration files.
3. Read models touched by last task.
4. Review legacy mapping section before adding features.

## Before Any New Feature
1. Define business flow in 3-5 bullets.
2. Identify impacted tables and indexes.
3. Verify if the feature maps to legacy behavior or is a new behavior.
4. Update `docs/PROJECT_CONTEXT.md` if scope changes.

## After Any Feature
1. Update or add tests.
2. Add a short "What changed" note in PR description.
3. Re-check this matrix:
   - Domain consistency
   - Performance/indexes
   - RTL/Arabic impact (for UI tasks)
   - Payment safety and idempotency

## Naming Standards
- Services end with `Service`.
- Actions end with `Action`.
- DTOs end with `Data`.
- Enums grouped under `App/Enums`.

## Non-Negotiables
- No direct business logic in controllers.
- No wallet balance mutation without ledger transaction.
- No card allocation without DB lock strategy.

## Implemented Reference Flow
- Wallet checkout already follows this pattern:
  - Request validation -> Action -> Services -> DB transaction -> JSON response.
- Reuse the same structure for topup approval and gateway checkout flows.
