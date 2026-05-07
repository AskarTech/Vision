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

## Scope

This file is the living project summary. Detailed domain rules and phased implementation notes live in the linked docs above.

## Strategy Alignment Note

- `PROJECT_STRATEGY.md` is now a referenced planning document.
- It currently states PHP 8.4+, while the application composer constraint is still PHP 8.3.
- This is a real alignment decision and should be resolved deliberately before any environment upgrade work.

## Product Idea

- Marketplace for selling Yemeni local Wi-Fi cards/packages.
- Two payment flows:
    - Direct external wallet APIs (for instant checkout).
    - Internal platform wallet + points (for smooth in-app purchases).

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
    - `WalletService` (wallet debit + ledger write).
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
    - role-aware session login for `admin`, `customer`, and `seller_manager`
    - admin dashboard protected by `auth` + `role:admin`
    - seller dashboard protected by `auth` + `role:seller_manager`
    - seller managers are routed to `GET /seller`
