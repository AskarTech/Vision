# Stage 16 — Customer Portal: Verification & QA

**Date:** 2026-05-09

## Scope covered

- **Assets:** Customer routes under `routes/web.php` (`customer.*`): dashboard (`/customer`), marketplace index + package detail + `POST …/buy`, orders index/show, wallet, profile. Matching Blade views live under `resources/views/customer/**`.
- **Navigation:** `config/navigation.php` → **`customer`** array drives the shared header (desktop links + RTL mobile drawer) in `resources/views/components/layouts/customer.blade.php`. Links respect Gates (`view_dashboard`, `purchase_cards`, `view_orders`, `view_wallet`).
- **Mobile-first:** Header uses a slide-over drawer (`Alpine.js`), minimum tap targets on primary marketplace actions (`min-h-[44px]`), and `viewport` meta on the customer layout.
- **Stock UX:** Marketplace listing shows **نفد المخزون** / low-stock hints via `withCount` of `cards` where `status = active`. Package detail shows live availability text and **hides** the purchase form when stock is zero; related packages show an abbreviated stock hint.
- **Checkout enforcement:** `MarketplaceController::buy` rejects zero stock before calling `CheckoutWithWalletAction`. **`CheckoutWithWalletAction`** validates active-card counts **before** creating an order so wallet/API flows fail atomically with a clear Arabic `RuntimeException` (mapped to **422** on the JSON checkout endpoint).
- **Dashboard wallet/points:** Home dashboard adds a **النقاط** metric bound to `wallets.points_balance`. Points **redemption** at checkout is supported via API/request parameters; automatic **earning** points on every purchase is **not** implemented yet — balances update only when ledger logic credits points elsewhere.

## Honesty / backlog

- **Checkout UX:** Purchase uses an inline form on the **package detail** page (no separate modal); filters on `/customer/marketplace` substitute “network selection” before drilling into a package.
- **Optimistic UI:** Marketplace purchase uses Alpine (`submitting` state + spinner). The flow still completes with a **full redirect** to the order page after server confirmation — deeper SPA-like balances without reload remain optional.
- **Points loyalty earning:** Product requirement “award points on purchase” needs an explicit policy + `WalletService` credit hook; Stage 16 documents visibility of current balance only.
- **Manual QA:** Spot-check RTL on real devices (iOS Safari / Chrome Android) for the drawer and filter bar.

## Commands

```bash
php artisan test --filter=CustomerPortalStage16Test
php artisan test
```
