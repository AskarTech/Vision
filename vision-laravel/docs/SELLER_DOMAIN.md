# Seller Domain

## Scope

Defines seller-side entities and responsibilities.

## Core Entities

- sellers
- seller_managers
- networks
- packages
- cards
- withdrawals
- banks

## Responsibilities

- Manage network definitions under seller scope.
- Manage package catalog and statuses.
- Upload and monitor card stock.
- Track sales and financial performance.
- Submit withdrawal requests.

## Access Rules

- Seller managers operate only inside their seller boundary.
- Cross-seller access is not allowed.
- Sensitive operations require explicit authorization checks.

## Inventory Rules for Sellers

- Card uploads must preserve uniqueness and provenance.
- Disabled package/network must block new sales.
- Seller analytics should use indexed dimensions by status and date.

## Withdrawal Rules

- Request must include amount and payout details.
- Status transitions: pending -> approved/rejected -> paid.
- All status changes must include reviewer and timestamp where applicable.

## Reporting Requirements

- Sales by package/network/time window.
- Stock availability and sold rate.
- Pending and completed withdrawals.
- Dispute count and resolution outcomes.

## Seller UI Baseline

- A dedicated seller dashboard exists at `GET /seller`.
- Seller managers authenticate through the shared login form and are redirected to their seller dashboard.
- The first dashboard slice focuses on inventory visibility: networks, packages, cards, and team members.
