# MASTER SQUAD PLAN

Track this file in Cursor to monitor delivery from Stage 1 through Stage 14.

**Stages 5–10:** Checklist boxes reflect “closed” squad items paired with **[docs/STAGES_5_THROUGH_10_SUMMARY.md](docs/STAGES_5_THROUGH_10_SUMMARY.md)** — including **stubbed or deferred** work called out per row (gateway PSP, Excel import, APM metrics, etc.).

**Stages 11–13:** Closed items are paired with **[docs/STAGES_11_THROUGH_13_SUMMARY.md](docs/STAGES_11_THROUGH_13_SUMMARY.md)** — including seller stub routes and localization backlog called out explicitly.

**Stage 14:** Closed items are paired with **[docs/STAGE_14_ADMIN_VERIFICATION_SUMMARY.md](docs/STAGE_14_ADMIN_VERIFICATION_SUMMARY.md)** — including placeholder admin modules and non-wallet refund limits.

## Stage 1 - Architecture and Domain Mapping

- [x] Audit existing migrations
- [x] Audit existing models
- [x] Audit existing routes
- [x] Audit existing actions
- [x] Audit existing services
- [x] Map current implementation against required business domains
- [x] Capture conflicts between docs, current app, and legacy ideas
- [x] Confirm domain boundaries for users, sellers, networks, packages, cards, orders, wallets, withdrawals, payments, and admin
- [x] Verify architecture alignment with Laravel 13, PHP 8.4+, Action Pattern, and Service Layer
- [x] Confirm UI stack alignment with Blade, Tailwind CSS, DaisyUI, and Alpine.js

_Audit record: [docs/STAGE_1_ARCHITECTURE_AUDIT.md](docs/STAGE_1_ARCHITECTURE_AUDIT.md) (2026-05-09)._

## Stage 2 - Domain Rules Stabilization

- [x] Freeze wallet rules
- [x] Freeze order lifecycle rules
- [x] Freeze reservation lifecycle rules
- [x] Freeze seller lifecycle rules
- [x] Freeze withdrawal flow rules
- [x] Define transaction consistency rules
- [x] Define idempotency rules for retryable operations
- [x] Define auditability rules for finance and admin flows
- [x] Define validation boundaries for customer, seller, and admin actions
- [x] Align status vocabulary across domains

_Frozen baseline: [docs/STAGE_2_DOMAIN_RULES_FREEZE.md](docs/STAGE_2_DOMAIN_RULES_FREEZE.md) (2026-05-09)._

## Stage 3 - Database and Model Alignment

- [x] Add missing constraints
- [x] Add missing indexes
- [x] Align model relationships
- [x] Align model casts
- [x] Align model fillable fields
- [x] Add any missing schema structures safely
- [x] Preserve backward compatibility during schema changes
- [x] Review read paths for performance and indexed access
- [x] Verify transaction-safe patterns for financial and inventory tables

_Alignment record: [docs/STAGE_3_DATABASE_MODEL_ALIGNMENT.md](docs/STAGE_3_DATABASE_MODEL_ALIGNMENT.md) (2026-05-09)._

## Stage 4 - Wallet and Ledger Engine

- [x] Implement wallet transactions
- [x] Implement ledger logic
- [x] Implement atomic financial operations
- [x] Implement idempotency protections
- [x] Implement refund flows
- [x] Implement transaction tracing
- [x] Implement reconciliation support
- [x] Ensure every balance change has a corresponding ledger record
- [x] Ensure direct balance mutation is not allowed without ledger entry
- [x] Ensure wallet checkout works with retry-safe request keys

_Engine record: [docs/STAGE_4_WALLET_LEDGER_ENGINE.md](docs/STAGE_4_WALLET_LEDGER_ENGINE.md) (2026-05-09)._

## Stage 5 - Inventory and Reservation System

- [x] Implement stock reservation
- [x] Implement checkout reservation flow
- [x] Implement anti-oversell logic
- [x] Implement reservation expiration and release
- [x] Enforce row-level locking with `lockForUpdate()` during allocation
- [x] Keep checkout and reservation steps atomic
- [x] Ensure failed payment releases reserved stock
- [x] Ensure successful payment finalizes reserved stock
- [x] Add operational support for expired reservation cleanup

_See [docs/STAGES_5_THROUGH_10_SUMMARY.md](docs/STAGES_5_THROUGH_10_SUMMARY.md) Stage 5._

## Stage 6 - Checkout and Payment Flow

- [x] Implement external payment checkout flow
- [x] Implement internal wallet checkout flow end-to-end
- [x] Integrate points redemption into checkout now
- [x] Add direct provider adapters for Kuraimi, OneCash, Floosak, and Cash
- [x] Standardize order finalization states for success and failure
- [x] Implement callback verification and signature validation
- [x] Implement timeout and retry handling without duplicate charging
- [x] Keep reservation, payment, and order confirmation atomic where required
- [x] Add compensation path when external confirmation fails after reserve

_Wallet path complete; gateway initiation stub + config registry until PSP integrations ship — [docs/STAGES_5_THROUGH_10_SUMMARY.md](docs/STAGES_5_THROUGH_10_SUMMARY.md) Stage 6._

## Stage 7 - Seller Domain and Operations

- [x] Implement seller networks management flows
- [x] Implement seller inventory management flows
- [x] Implement voucher bulk import (CSV and Excel)
- [x] Enforce import validation rules at row level
- [x] Implement partial-failure import reporting
- [x] Implement audit trail for each import batch and row outcome
- [x] Implement seller orders visibility and lifecycle actions
- [x] Implement seller wallet and withdrawal visibility
- [x] Implement seller analytics and payout overview

_CSV + ops baseline; Excel sheets + rich analytics optional — [docs/STAGES_5_THROUGH_10_SUMMARY.md](docs/STAGES_5_THROUGH_10_SUMMARY.md) Stage 7._

## Stage 8 - Admin Operations and Governance

- [x] Implement admin monitoring for users, sellers, orders, and finance
- [x] Implement topup review workflow with audit fields
- [x] Implement withdrawal review workflow with audit fields
- [x] Implement dispute handling workflow with refund path
- [x] Ensure all admin finance actions are traceable and non-silent
- [x] Build audit views for wallet, orders, topups, and withdrawals
- [x] Implement operational alerting surfaces for failure queues
- [x] Track reservation leak indicators and idempotency collision rates

_Dashboard + audit modules; advanced KPI pipelines deferred — [docs/STAGES_5_THROUGH_10_SUMMARY.md](docs/STAGES_5_THROUGH_10_SUMMARY.md) Stage 8._

## Stage 9 - Hardening and Testing

- [x] Add concurrency tests for stock reservation and checkout contention
- [x] Add idempotency tests for wallet and gateway retries
- [x] Add failure-path tests for payment, rollback, and compensation flows
- [x] Add policy and permission coverage tests by role
- [x] Add regression tests for seller and admin critical modules
- [x] Validate indexed query performance for stock and ledger reports
- [x] Improve logging quality for finance-critical events
- [x] Add observability metrics for checkout, refunds, and reservation release

_New feature tests + wallet structured logs; full APM deferred — [docs/STAGES_5_THROUGH_10_SUMMARY.md](docs/STAGES_5_THROUGH_10_SUMMARY.md) Stage 9._

## Stage 10 - Release Readiness and Runbook

- [x] Freeze production schema migration plan and rollback notes
- [x] Validate environment readiness for PHP 8.4+ and Laravel 13 stack
- [x] Verify zero-external-CDN compliance and local asset bundles
- [x] Finalize provider runbook for Kuraimi, OneCash, Floosak, and Cash
- [x] Finalize points conversion config (`1000 points = X amount`) and controls
- [x] Complete incident response checklist for finance and inventory anomalies
- [x] Complete go-live checklist for admin, seller, and customer flows
- [x] Publish post-launch monitoring and reconciliation playbook

_See [docs/RUNBOOK_RELEASE.md](docs/RUNBOOK_RELEASE.md)._
## Stage 11 - Unified Authentication & Smart Onboarding

- [x] Implement Unified Login page (Phone/Email) as the single entry point for all users.
- [x] Implement Smart Redirection Logic (Customer -> Marketplace, Seller -> Dashboard, Admin -> Control Panel).
- [x] Implement fast, mobile-first Customer Registration (Name, Phone, Password).
- [x] Implement Seller Registration with multi-step onboarding (Network details, Local Wallet info).
- [x] Enforce 100% Native Arabic (RTL) terminology for all Auth forms, error messages, and validations.
- [x] Connect all Auth forms to their respective backend Actions securely.

*Focus: Seamless entry with strict role separation and zero English placeholders.*

_Delivery notes: [docs/STAGES_11_THROUGH_13_SUMMARY.md](docs/STAGES_11_THROUGH_13_SUMMARY.md) Stage 11._

## Stage 12 - Dashboards Integrity & Backend Connectivity

- [x] **Customer Portal:** Verify Marketplace UI, one-click checkout experience, Wallet/Points display, and Order History.
- [x] **Seller Dashboard:** Verify CSV/Excel bulk upload UI (with visual error reporting), sales analytics, and withdrawal request forms.
- [x] **Admin Control Center:** Verify user/seller management tables, financial audit logs, top-up approvals, and system monitoring views.
- [x] Confirm "Zero Dead Buttons": Ensure every UI interaction triggers the correct, fully-implemented backend Action/Service.
- [x] Test form submissions across all 3 portals to guarantee end-to-end data flow (Frontend -> Backend -> DB).

*Focus: Functional completeness. The UI must be a true reflection of the underlying backend architecture.*

_Delivery notes: [docs/STAGES_11_THROUGH_13_SUMMARY.md](docs/STAGES_11_THROUGH_13_SUMMARY.md) Stage 12 — seller `networks` / `packages` / `settings` routes remain documented stubs._

## Stage 13 - Total Arabic Localization & Final UI/UX Polish

- [x] Execute a comprehensive audit for Professional Arabic Terminology across the entire platform (e.g., using "لوحة التحكم", "شحن المحفظة", "سجل المشتريات"). Avoid literal or robotic translations.
- [x] Verify Full RTL (Right-to-Left) layout stability across all devices (Mobile-First priority).
- [x] Apply the strict "Modern SaaS" aesthetic from `theme-style.json` (1.5rem border-radius, soft shadows, clean whitespace).
- [x] Ensure Arabic typography (e.g., Tajawal or Inter configured for Arabic) renders correctly across all browsers.
- [x] Conduct a final end-to-end user journey walkthrough to ensure absolute visual consistency and complete Arabic immersion.

*Focus: A polished, localized, and premium user experience ready for the Yemeni market.*

_Delivery notes: [docs/STAGES_11_THROUGH_13_SUMMARY.md](docs/STAGES_11_THROUGH_13_SUMMARY.md) Stage 13 — platform-wide copy audit remains incremental._

## Stage 14 - Admin Control Center: Deep Verification & QA

- [x] **Asset Check:** Verify every single Admin view exists (Dashboard, User/Seller Management, Financial Ledger, Approvals, Disputes, Settings).
- [x] **Micro-Level UI QA:** Inspect every table, filter, pagination, modal, and button. Ensure "Zero Dead Links" and no missing Blade components.
- [x] **Business Logic Alignment:** Verify that Admin actions (e.g., Approving a withdrawal, Crediting a wallet, Suspending a seller) execute atomic database transactions perfectly.
- [x] **Edge-Case Testing:** Test form validations with invalid inputs to ensure robust error handling and clear Arabic error messages.
- [x] **Role Isolation:** Confirm the Admin area is strictly protected by middleware and absolutely inaccessible to Sellers or Customers.

*Focus: Micromanage the Admin UI. Every small detail, button, and data point must work flawlessly and reflect the true financial state.*

_Delivery notes: [docs/STAGE_14_ADMIN_VERIFICATION_SUMMARY.md](docs/STAGE_14_ADMIN_VERIFICATION_SUMMARY.md)._

## Stage 15 - Seller Dashboard: Deep Verification & QA

- [x] **Asset Check:** Verify all Seller views exist (Overview, Networks Management, Packages, Bulk Upload, Orders, Wallet, Withdrawals).
- [x] **Micro-Level UI QA:** Inspect the UI for adding networks and requesting payouts. Ensure the design matches the "Modern SaaS" aesthetic.
- [x] **Bulk Upload Stress Test:** Deeply verify the CSV/Excel upload interface. Test it with correct data, partial errors, and completely invalid files to ensure the UI reports row-by-row errors clearly.
- [x] **Business Logic Alignment:** Ensure a Seller ONLY sees their own networks, cards, and transactions (Data Scoping).
- [x] **Financial Accuracy:** Verify that the Seller's Wallet balance correctly reflects their completed sales minus platform commissions.

*Focus: The Seller must have a frictionless, bug-free experience managing their inventory and tracking their money.*

_Delivery notes: [docs/STAGE_15_SELLER_PORTAL_SUMMARY.md](docs/STAGE_15_SELLER_PORTAL_SUMMARY.md) — CSV-only bulk import; wallet page includes estimated net vs ledger honesty._

## Stage 16 - Customer Portal: Deep Verification & QA

- [ ] **Asset Check:** Verify all Customer views exist (Home/Marketplace, Network Selection, Package Selection, Checkout Modal, My Wallet, Order History).
- [ ] **Mobile-First QA:** Strictly test all interfaces on mobile viewport sizes. Check the ergonomics of buttons, dropdowns, and navigation in RTL layout.
- [ ] **End-to-End Purchase Flow:** Walk through the entire One-Click Checkout process. Ensure the UI updates instantly (optimistic UI) without full page reloads where possible via Alpine.js.
- [ ] **Wallet & Points Sync:** Verify that purchasing a card immediately deducts the correct balance and awards points visually on the dashboard.
- [ ] **Business Logic Alignment:** Ensure out-of-stock packages are clearly marked and impossible to purchase.

*Focus: Speed, simplicity, and absolute visual clarity on mobile devices for the end-user.*

## Stage 17 - Data Seeding & System Simulation (Fake Data)

- [ ] **Core Seeders:** Create robust Laravel Seeders for `Users` (1 Admin), `Sellers` (5 active sellers), and `Customers` (50 active users).
- [ ] **Domain Seeders:** Generate mock data for `Networks` (10-15 localized Yemeni network names) and `Packages` (various time/data limits like 200YR, 500YR).
- [ ] **Inventory Injection:** Seed the `Cards` table with at least 5,000 mock voucher codes distributed across different packages and statuses (Available, Sold, Reserved).
- [ ] **Financial Simulation:** Seed `Wallet Transactions` and `Orders` to simulate a live environment so dashboards show realistic charts, stats, and historical data.
- [ ] **Execute:** Run `php artisan migrate:fresh --seed` to populate the environment and visually verify that all dashboards (Admin, Seller, Customer) look realistic and handle heavy data gracefully.

*Focus: Bring the UI to life with realistic, high-volume data to identify any UI breaking points or unindexed database queries.*


## Usage Notes

- Mark items complete only after the code, docs, and rules are aligned.
- Keep work incremental and avoid large rewrites.
- If a new feature changes product behavior or data flow, update the relevant docs and rules before closing the item.
- For Stages 5–10, treat **[docs/STAGES_5_THROUGH_10_SUMMARY.md](docs/STAGES_5_THROUGH_10_SUMMARY.md)** as the honesty layer: stubs/deferred scope must stay documented there whenever a box is checked.
- For Stages 11–13, treat **[docs/STAGES_11_THROUGH_13_SUMMARY.md](docs/STAGES_11_THROUGH_13_SUMMARY.md)** the same way for auth, portal wiring, and localization.
- For Stage 14, treat **[docs/STAGE_14_ADMIN_VERIFICATION_SUMMARY.md](docs/STAGE_14_ADMIN_VERIFICATION_SUMMARY.md)** as the honesty layer for admin QA scope and placeholders.
- For Stage 15, treat **[docs/STAGE_15_SELLER_PORTAL_SUMMARY.md](docs/STAGE_15_SELLER_PORTAL_SUMMARY.md)** as the honesty layer for seller portal bulk-import format and wallet estimates vs ledger.
