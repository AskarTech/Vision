# YemenWi-Fi Hub Project Strategy

## Purpose

This document is the canonical product and technical strategy for YemenWi-Fi Hub.
It defines the target system behavior, stack decisions, UI direction, and non-negotiable platform rules.

## Product Vision

YemenWi-Fi Hub is a centralized marketplace for Yemeni Wi-Fi network vouchers.

Primary actors:
- Sellers manage networks, inventory, sales, and withdrawals.
- Customers browse nearby networks and purchase vouchers instantly from mobile-first screens.

## Strategic Non-Negotiables

- Backend: Laravel 13 only.
- Runtime: PHP 8.4+ only.
- Architecture: Action Pattern + Service Layer inside a modular monolith.
- Frontend: Blade + Tailwind CSS + DaisyUI + Alpine.js.
- Asset policy: zero external CDNs, local assets only.
- UI direction: professional, high-end, minimalist, RTL-friendly, Arabic-first.
- Inventory safety: atomic DB transactions and row-level locking for stock allocation.
- Financial safety: append-only wallet ledger, idempotency, and auditability.

## Core Domain Systems

### Dual Payment System

Two first-class payment paths must always be supported:

1. Internal wallet checkout.
2. Direct external wallet API checkout.

Supported direct providers:
- Kuraimi
- OneCash
- Floosak
- Cash

The payment layer must be provider-agnostic, but each provider must be explicitly modeled and tested.

### Points Engine

The points engine is a core platform feature, not a future extension.

- Points participate in checkout logic now.
- Conversion rate is centrally configurable.
- The canonical rule is: `1000 points = X amount`, where X is a platform configuration value, not a hardcoded constant.
- Points adjustments, redemptions, and refunds must be ledgered and auditable.

### Inventory Integrity

- Cards are single-use inventory units.
- Allocation must use atomic transactions and `lockForUpdate()`.
- Double-spending, duplicate sale, and oversell conditions are forbidden.
- Reservation, confirmation, release, and expiration must be deterministic.

### Voucher Imports

- Seller voucher imports must support CSV and Excel.
- Each import requires validation, partial-failure handling, and a complete audit trail.
- Import results must record row-level success, failure, and rejection reasons.

## UX Direction

- Mobile-first and optimized for slow connections.
- High border radius styling aligned with the theme tokens.
- Clean spacing, vibrant accents, and strong visual hierarchy.
- Minimalist, operational dashboards without generic SaaS clutter.
- No layout shift during loading; use stable component shells and placeholders.

## Delivery Principle

All work must remain incremental.
Every feature change should preserve working behavior, update the relevant docs, and avoid uncontrolled rewrites.