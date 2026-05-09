# Project Context - Vision Marketplace

## Documentation Index

- [PROJECT_STRATEGY.md](../PROJECT_STRATEGY.md): product vision, technical targets, and sprint plan.
- [docs/PROJECT_OVERVIEW.md](PROJECT_OVERVIEW.md): entry point and project framing.
- [docs/CONTEXT_WORKFLOW.md](CONTEXT_WORKFLOW.md): working rules for sessions and feature changes.
- [docs/DOMAIN_RULES.md](DOMAIN_RULES.md): business rules for marketplace, wallet, inventory, seller, and admin domains.
- [docs/IMPLEMENTATION_PHASES.md](IMPLEMENTATION_PHASES.md): incremental delivery roadmap.
- [docs/LEGACY_GUIDE.md](LEGACY_GUIDE.md): safe usage rules for legacy_code.
- [docs/ENGINEERING_RULES.md](ENGINEERING_RULES.md): architecture and change-management guardrails.
- [docs/WALLET_ARCHITECTURE.md](WALLET_ARCHITECTURE.md): wallet ledger, idempotency, and reconciliation.
- [docs/INVENTORY_RESERVATION.md](INVENTORY_RESERVATION.md): card reservation lifecycle and anti-oversell rules.
- [docs/CHECKOUT_FLOW.md](CHECKOUT_FLOW.md): checkout orchestration and retry behavior.
- [docs/SELLER_DOMAIN.md](SELLER_DOMAIN.md): seller-scoped capabilities and reporting.
- [docs/ADMIN_OPERATIONS.md](ADMIN_OPERATIONS.md): review, dispute, and audit flows.
- [docs/STAGE_1_ARCHITECTURE_AUDIT.md](STAGE_1_ARCHITECTURE_AUDIT.md): Stage 1 migration/model/route/action/service audit and doc-vs-app conflicts.
- [docs/STAGE_2_DOMAIN_RULES_FREEZE.md](STAGE_2_DOMAIN_RULES_FREEZE.md): Stage 2 frozen domain rules, enums, idempotency, auditability, validation boundaries.
- [docs/STAGE_3_DATABASE_MODEL_ALIGNMENT.md](STAGE_3_DATABASE_MODEL_ALIGNMENT.md): Stage 3 model/migration alignment, queue indexes, casts, relations.
- [docs/STAGE_4_WALLET_LEDGER_ENGINE.md](STAGE_4_WALLET_LEDGER_ENGINE.md): Stage 4 wallet service, ledger idempotency, refunds, reconciliation helper.
- [docs/STAGES_5_THROUGH_10_SUMMARY.md](STAGES_5_THROUGH_10_SUMMARY.md): Stages 5–10 delivery vs stubs/deferred work.
- [docs/STAGES_11_THROUGH_13_SUMMARY.md](STAGES_11_THROUGH_13_SUMMARY.md): Stages 11–13 auth, seller onboarding, portal wiring, Arabic / UI polish vs backlog.
- [docs/STAGE_14_ADMIN_VERIFICATION_SUMMARY.md](STAGE_14_ADMIN_VERIFICATION_SUMMARY.md): Stage 14 admin route/policy fixes, paid-order refund + CSV export, verification tests.
- [docs/STAGE_15_SELLER_PORTAL_SUMMARY.md](STAGE_15_SELLER_PORTAL_SUMMARY.md): Stage 15 seller portal assets, scoping policies, navigation, wallet estimates vs ledger, CSV bulk import scope.
- [docs/STAGE_16_CUSTOMER_PORTAL_SUMMARY.md](STAGE_16_CUSTOMER_PORTAL_SUMMARY.md): Stage 16 customer marketplace stock UX, checkout guards, mobile RTL nav, dashboard points display vs earn backlog.
- [docs/RUNBOOK_RELEASE.md](RUNBOOK_RELEASE.md): Release checklist, points env, scheduling, incidents.

## Scope

This file is the living project summary. Detailed domain rules and phased implementation notes live in the linked docs above.

## Strategy Alignment Note

- `PROJECT_STRATEGY.md` is the canonical planning document.
- The project standard is PHP 8.4+ across docs and Composer.
- The platform uses Laravel 13, Action/Service architecture, zero-CDN assets, and dual-payment checkout.

## Product Idea

- Marketplace for selling Yemeni local Wi-Fi cards/packages.
- Two payment flows:
    - Direct external wallet APIs (for instant checkout).
    - Internal platform wallet + points (for smooth in-app purchases).
- Direct providers are explicitly modeled for Kuraimi, OneCash, Floosak, and Cash.
- Points are core checkout logic, not a future extension.

## Legacy to New Mapping

- `customers` -> `users` (`role=customer`) + `wallets`.
- `partners` -> `sellers`.
- `network_managers` -> `seller_managers` + linked `users` (`role=seller_manager`).
- `packages` -> `packages`.
- `cards` -> `cards`.
- `transactions` -> `wallet_transactions` + `card_orders` + `card_order_items`.
- `topup_requests` -> `topup_requests`.
- `withdrawals` -> `withdrawals`.
- `banks` -> `banks`.
- `payment_settings` -> `payment_gateways`.

## Current Data Model (Core)

- Identity and access: `users`, `seller_managers`.
- Supply side: `sellers`, `networks`, `packages`, `cards`.
- Wallet and payment: `wallets`, `wallet_transactions`, `payment_gateways`, `topup_requests`.
- Purchase and fulfillment: `card_orders`, `card_order_items`.
- Finance ops: `withdrawals`, `banks`.

## High-Scale Constraints

- Card stock query must remain O(log n)-friendly using indexed columns:
    - `cards(package_id, status, id)`
    - `cards(status, id)`
- Wallet ledger queries are index-driven:
    - `wallet_transactions(wallet_id, type, status, id)`
    - `wallet_transactions(user_id, created_at)`
- Seller reporting paths are indexed:
    - `packages(seller_id, status, id)`
    - `cards(seller_id, status, id)`
    - `withdrawals(seller_id, status, id)`

## Implementation Rules (Always)

1. Any new feature must update this file if it changes product behavior or data flow.
2. Any schema change must include:
    - migration
    - model updates (`fillable`, `casts`, relations)
    - index review for read paths
3. Wallet-related writes must be transaction-safe and append-only at ledger level.
4. Card sale flow must lock stock row(s) during allocation to prevent duplicate sales.
5. Payment and checkout flows must remain aligned with the active strategy and never reintroduce CDN-based frontend dependencies.

## Next Architecture Layer

- Actions + Services to build:
    - `CheckoutWithGatewayAction`
    - `RequestTopupAction`
    - `ApproveTopupAction`
    - `ReportCardIssueAction`
    - `ResolveCardDisputeAction`

## Implemented Application Layer (Current)

- `CheckoutWithWalletAction` implemented.
- Supporting services:
    - `CardInventoryService` (row-level locking for available card allocation).
    - `WalletService` (locked debit/credit/refund, ledger rows, `external_reference` idempotency, `ledgerChainMatchesBalance` reconciliation helper; cash balance not mass-assignable on `Wallet`).
- API endpoint available:
    - `POST /api/v1/checkout/wallet`
    - Requires `idempotency_key` to prevent duplicate order/debit on client retries.
    - Maps idempotency into `card_orders.external_reference` using scoped value per user.
    - Stock allocation now records reservation audit fields on `cards` before final sale.
- Admin UI baseline available:
    - `GET /admin`
    - renders an operational dashboard with system metrics, pending queues, and quick action cards.
- Frontend baseline now uses shared Blade layouts and reusable UI components for auth and dashboards, with Tailwind CSS and DaisyUI as the active UI stack.
- IAM baseline available:
    - `GET /login`
    - `POST /login`
    - `POST /logout`
    - `GET|POST /register` (customer) and `GET|POST /register/seller` (seller onboarding wizard)
    - role-aware session login for `admin`, `customer`, and `seller_manager`
    - admin dashboard protected by `auth` + `role:admin`
    - seller dashboard protected by `auth` + `role:seller_manager`
    - seller managers are routed to `GET /seller`
    - default application locale `ar` with Arabic validation/auth strings; shared layouts load Tajawal/Cairo for typography
