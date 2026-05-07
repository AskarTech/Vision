---
name: vision-marketplace-workflow
description: "Use when working in YemenWi-Fi Hub on incremental Laravel changes, docs-first architecture review, wallet/inventory/checkout flows, or admin dashboard work with legacy_code as read-only reference."
---

# Vision Marketplace Workflow

## Purpose

Use this skill when making incremental changes in the YemenWi-Fi Hub Laravel project. It captures the docs-first, legacy-aware, transaction-safe workflow used for architecture review, feature delivery, and admin or marketplace UI work.

## Usage Rules

- Use this skill for incremental Laravel work in YemenWi-Fi Hub.
- Use this skill when the task needs architecture review, domain analysis, or implementation planning.
- Use this skill when the work touches wallet, checkout, inventory, seller, admin, or docs updates.
- Use this skill when you need to compare current code with docs or legacy_code before changing behavior.
- Do not use this skill for unrelated one-off tasks that do not need the project workflow.
- Do not use this skill as a replacement for direct code review when the user only wants a quick answer.

## What It Uses

- `docs/PROJECT_CONTEXT.md` for the living state of the implementation.
- `docs/PROJECT_OVERVIEW.md` for project framing and documentation index.
- `docs/DOMAIN_RULES.md` for business rules.
- `docs/IMPLEMENTATION_PHASES.md` for roadmap guidance.
- `docs/LEGACY_GUIDE.md` for safe use of legacy_code.
- `docs/ENGINEERING_RULES.md` for coding guardrails.
- `docs/WALLET_ARCHITECTURE.md` for financial behavior.
- `docs/INVENTORY_RESERVATION.md` for stock reservation behavior.
- `docs/CHECKOUT_FLOW.md` for purchase orchestration.
- `docs/SELLER_DOMAIN.md` for seller-side operations.
- `docs/ADMIN_OPERATIONS.md` for operational review flows.

## Stack Rules

- Backend stays on Laravel 13 with PHP aligned to the repository constraint unless the project explicitly upgrades it.
- Use the Laravel service container, Eloquent, FormRequest, Actions, and Services first before introducing new packages.
- Add a package only when it solves a real recurring need that cannot be handled cleanly with built-in Laravel features.
- Prefer lightweight, well-known packages over large framework replacements.
- Avoid adding state-heavy frontend frameworks unless the project strategy explicitly changes.
- Keep asset handling compatible with Vite and local-first delivery.

## Frontend Rules

- Build UI with Blade, Tailwind CSS, DaisyUI, and Alpine.js.
- Keep interfaces mobile-first, RTL-friendly, and Arabic-first.
- Prefer server-rendered pages and lightweight interactivity over SPA complexity.
- Use DaisyUI components for buttons, forms, alerts, modals, tables, and dashboard cards where possible.
- Use Alpine.js only for small interactions, toggles, drawers, dropdowns, tabs, and modal state.
- Keep layouts responsive and optimized for slow connections.
- Include loading, empty, and error states in any new screen.
- Preserve readable Arabic labels and avoid awkward machine-translated text.

## Package Guidance

- Use built-in Laravel features for auth, validation, queues, mail, cache, filesystem, and pagination unless a stronger reason exists.
- For coding standards or formatting, prefer existing project tools and lightweight utilities.
- For admin or dashboard pages, prefer reusable Blade components instead of introducing a component framework.
- Do not add a package just to reduce a few lines of code if it increases maintenance or bundle weight.
- If a package is added, document why it was chosen and what it replaces.

## Tool Selection Rules

- Use read-only file tools first when you need context: `read_file`, `list_dir`, `grep_search`, `file_search`.
- Use `get_errors` after edits to validate the touched files quickly.
- Use `run_in_terminal` for focused executable checks such as tests, artisan commands, or lint/build steps.
- Use `apply_patch` for all manual code edits.
- Use `create_file` only for brand-new files and `create_directory` only when a folder does not exist.
- Use `manage_todo_list` when a task spans multiple controlled steps and needs visible sprint tracking.
- Use `vscode_askQuestions` only when a requirement is genuinely ambiguous and blocks safe progress.
- Use `get_changed_files` when you need to confirm the exact blast radius of the current sprint slice.
- Use `file_search` and `grep_search` before broad navigation so exploration stays local.

## Sprint Control Loop

### Sprint Start

1. Restate the sprint goal in one sentence.
2. Read the relevant docs and the nearest implementation files.
3. Identify the exact slice to change and the files likely involved.
4. Record the scope in a short todo list if the work spans more than one step.

### Sprint Planning

1. Define the business flow in 3-5 bullets.
2. Identify impacted tables, models, routes, views, actions, and services.
3. Note the one biggest risk and the cheapest validation for it.
4. Decide whether the sprint is docs-only, code-only, or mixed.

### Sprint Execution

1. Make the smallest useful edit first.
2. Validate immediately with the narrowest executable check.
3. If validation fails, fix the same slice before expanding scope.
4. If the sprint touches finance or inventory, verify locking, idempotency, and auditability.
5. Update docs in the same sprint before moving on.

### Sprint Closure

1. Confirm the modified behavior matches the original sprint goal.
2. Confirm no unrelated files were changed.
3. Confirm docs and code say the same thing.
4. Confirm the focused test or command passed.
5. Summarize what is complete, what remains, and the next sprint slice.

## Control Gates

- Do not start a second implementation slice until the first slice is validated.
- Do not widen scope because adjacent code looks interesting unless the current validation disproves the current plan.
- If a sprint needs several files, keep them in one coherent change-set and document the dependency chain.
- If the work starts feeling like a rewrite, stop and break it into smaller sprints.

## Core Principles

- Read the source-of-truth docs before changing code.
- Inspect the current implementation before editing.
- Treat legacy_code as read-only historical reference.
- Make the smallest safe change that advances the workflow.
- Update documentation whenever behavior or data flow changes.
- Verify with a focused test or lint check after each substantive change.
- Keep every change incremental, reversible, and easy to explain.
- Prefer facts from docs and code over assumptions from legacy behavior.
- If a change affects finance, inventory, or admin review, treat auditability as a requirement.

## Required Workflow

1. Gather context from docs, current code, and any relevant legacy reference.
2. State a local hypothesis about the behavior or gap.
3. Identify one cheap check that could disconfirm the hypothesis.
4. Define a narrow scope: exact files, purpose, impact, risk, and verification.
5. Implement one small change only.
6. Run the narrowest useful verification immediately.
7. Repair the same slice if validation fails.
8. Update docs to reflect the new behavior.
9. Re-read the touched files only if validation or the patch changes the local understanding.
10. Stop and summarize if the change would require widening the scope beyond the original slice.

## Decision Rules

- If a feature touches wallet, checkout, inventory, or orders, require transaction safety and idempotency checks.
- If a feature touches cards or stock allocation, require row-level locking or reservation behavior.
- If a feature touches seller or admin operations, preserve auditability and reviewability.
- If a question can be answered from the docs, prefer the docs over assumptions.
- If legacy behavior conflicts with docs or current architecture, document the conflict and choose the safest incremental path.

## Implementation Style

- Prefer Action classes and Service classes over controller business logic.
- Keep classes small and domain-focused.
- Preserve backward compatibility when practical.
- Avoid large refactors unless a defect or architectural conflict requires it.

## Documentation Rules

- Update docs/PROJECT_CONTEXT.md when implementation state changes.
- Update domain docs when business behavior changes.
- Keep docs synchronized with the live code path.
- Record new routes, actions, services, migrations, and state transitions in the relevant docs.
- When behavior changes, document both the expected path and the failure/retry path.

## Post-Change Checklist

- Confirm the change matches the original scope.
- Confirm no files outside the intended slice were edited.
- Confirm the relevant docs were updated in the same session.
- Confirm the touched code path still follows Action/Service separation.
- Confirm finance or stock paths still use transactions or locking where required.
- Confirm the narrowest useful test or lint check passed.
- Confirm the change does not introduce duplicate writes, double charges, or stock leaks.
- Confirm any new status, field, or endpoint is described in PROJECT_CONTEXT or the relevant domain doc.

## Completion Checks

- The change is scoped and understandable.
- The modified code passes focused validation.
- No files under legacy_code were edited.
- Documentation reflects the latest behavior.
- The post-change checklist is satisfied for the touched slice.

## Example Uses

- Review the current checkout flow and add idempotency.
- Add a new admin review screen and document the queue behavior.
- Stabilize inventory reservation before implementing gateway checkout.
- Analyze a conflict between legacy_code ideas and current docs.
