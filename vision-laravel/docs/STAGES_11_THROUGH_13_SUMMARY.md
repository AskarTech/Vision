# Stages 11–13 Summary (Auth, Portal Integrity, Arabic / SaaS Polish)

**Date:** 2026-05-09

This document is the honesty layer for closing **Stages 11–13** in `MASTER_SQUAD_PLAN.md`: what shipped, what remains deferred, and where to look in code.

## Stage 11 — Unified authentication and onboarding

**Delivered**

- Single login (`SessionController`): phone or email + password + explicit role (`admin` | `customer` | `seller_manager`). Failed attempts use `__('auth.failed')` and validation errors (aligned with `.cursor/rules/auth-and-roles.mdc`).
- Smart redirects after login: customer → marketplace, seller_manager → seller dashboard, admin → admin dashboard.
- Fast customer registration via `RegisterCustomerAction` + `RegistrationController`; redirect to `customer.marketplace.index` with Arabic flash messaging.
- Seller multi-step onboarding UI (`auth/register-seller.blade.php`, Alpine.js) backed by `RegisterSellerOnboardingAction` + `SellerRegistrationController`: creates `Seller`, manager `User`, `SellerManager`, settlement `Wallet`, first `Network` (optional `local_wallet_label` in `network.meta`).
- Routes: `GET|POST /register/seller` (`register.seller.*`).
- Arabic-first auth copy; `lang/ar/auth.php`, `lang/ar/validation.php`; app default locale `ar` (see `config/app.php`).

**Deferred / follow-up**

- Full Arabic audit of every non-auth Blade screen (Stage 13 baseline is layouts + auth; other modules may still mix English labels).
- Email-as-primary identifier UX polish if product requires it beyond phone.

## Stage 12 — Dashboard integrity and backend wiring

**Delivered**

- Seller **wallet** and **sales** routes call real controllers (`Seller\WalletController`, `Seller\SalesController`) instead of view-only closures; views consume `$sellerWallet`, `$sellerTransactions`, `$stats`.
- Customer marketplace and checkout paths covered by existing checkout tests; new auth/onboarding tests exercise customer redirects and seller HTTP surfaces.

**Still placeholder (closures / static views)**

- Seller `networks`, `packages`, and `settings` routes remain view stubs until wired to inventory/network APIs already present under other prefixes.

**Honesty on “zero dead buttons”**

- Goal: every labeled action in shipping surfaces maps to a controller/action. Remaining seller sidebar items that are stubs should be treated as backlog until CRUD matches admin parity.

## Stage 13 — Arabic terminology, RTL, Modern SaaS polish

**Delivered**

- Shared layouts load **Tajawal** and **Cairo** (Google Fonts) with Tajawal-first stack on auth; dashboard layout aligned for Arabic typography.
- Auth cards use elevated radius/shadow consistent with `theme-style.json` (≈1.5rem corners, soft shadow).
- RTL: layouts use `dir="rtl"` where applicable; Alpine `x-cloak` styled in auth layout to prevent wizard flicker.

**Deferred**

- Line-by-line terminology pass on admin and seller modules (replace literal English where it remains).
- Full-device RTL regression (manual QA recommended beyond automated tests).

## Verification

- Feature tests: `tests/Feature/StageAuthOnboardingTest.php` (customer login/register redirects, seller onboarding persistence, seller wallet & sales `200 OK`).
- Run: `php artisan test`.

## Related rules

- `.cursor/rules/auth-and-roles.mdc` — session, roles, onboarding boundaries.
- `.cursor/rules/dashboard-ui.mdc` / `customer-ui-patterns.mdc` — layout and component conventions.
