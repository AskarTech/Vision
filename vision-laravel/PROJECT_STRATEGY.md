1. Project Vision
A modern, high-performance marketplace for selling local Wi-Fi vouchers in Yemen. The platform connects Wi-Fi network owners (Sellers) with users (Customers) via a seamless, mobile-first web interface.

2. Tech Stack Requirements (Strict)
Backend: Laravel 13 (Latest Stable) + PHP 8.4+.

Architecture: Action Classes & Service Layer (Modular Monolith).

Database: MySQL 8.0+ (Heavy use of composite indexing for card stock).

Frontend: Tailwind CSS + DaisyUI (Component Library).

Reactivity: Alpine.js (No heavy frameworks, must stay lightweight).

UX Design: Mobile-First, RTL (Arabic) support, Minimalist aesthetic.

3. Core Business Logic & Data Flow
Payment Flows:
Internal Wallet: Users can top-up their balance on the platform and buy cards with one click.

Direct Purchase: Instant checkout via local Yemeni wallet APIs (Kuraimi, OneCash, etc.).

Inventory Management:
Sellers upload cards in bulk (CSV/Excel).

Atomicity: Use Database Transactions and Row Locking during checkout to prevent duplicate sales of the same voucher.

4. Agile Roadmap (Development Sprints)
Sprint 0: Foundation & UI Skeleton
Initialize Laravel 13 with proper directory structure for Actions and Services.

Setup Migrations: users, sellers, networks, packages, cards, wallets, transactions.

Build Global Layouts:

Customer Layout: Ultra-responsive, mobile-first, RTL.

Seller Layout: Professional sidebar-based dashboard.

Sprint 1: Identity & Access Management (IAM)
Multi-auth system for Customers and Sellers.

Profile management and security settings.

Sprint 2: Seller Inventory System
Dashboard for Sellers to add Wi-Fi Networks.

Voucher management: Bulk upload, price categories, and stock status.

Sales analytics and reporting for Sellers.

Sprint 3: Financial Engine & Wallets
Develop WalletService for internal balance management (Credit/Debit).

Integrate Mock APIs for local Yemeni payment gateways.

Transaction ledger for full financial auditability.

Sprint 4: Marketplace & High-Speed Checkout
Customer-facing store: Browse networks, filter by category.

The PurchaseCardAction: Handling the entire sale logic in a single, safe transaction.

"My Cards" section for users to view and copy purchased codes.

Sprint 5: UX Excellence & Optimization
Optimize for slow connections (Asset minification, local assets only).

Enhanced UX: Alpine.js for smooth transitions, skeleton loaders, and intuitive feedback.

5. Development Guidelines for AI
Clean Code: Always separate logic into App\Actions and App\Services. Controllers should only handle requests/responses.

Type Safety: Use strict typing for all method arguments and return types.

UI Consistency: Always use DaisyUI components for buttons, modals, and forms to keep the design unified.

Arabic First: Ensure all generated Blade views are RTL-compatible and use professional Arabic terminology.

Performance: Every query on the cards table must be index-optimized.
