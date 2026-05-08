# Release Runbook — YemenWi-Fi Hub (Vision Laravel)

**Last updated:** 2026-05-09  

## 1. Schema migrations

- Apply with `php artisan migrate --force` in CI/production.
- Rollback: use `php artisan migrate:rollback --step=1` only on unreleased migrations after verifying down() matches data expectations.
- **Never** rollback finance migrations after live traffic without a reconciliation plan.

## 2. Runtime stack

- **PHP** `^8.4`, **Laravel** `^13` (see `composer.json`).
- Queue/workers: not required for baseline wallet checkout; schedule runner **must** execute `php artisan schedule:run` every minute so `reservations:release-expired` fires.

## 3. Assets and CDN

- JS/CSS via Vite (`npm run build`).
- **Fonts:** Cairo is loaded from Google Fonts in some Blade layouts — conflicts with strict zero-CDN posture; self-host subset fonts before strict compliance audits.

## 4. Payment providers

- Wallet checkout: `POST /api/v1/checkout/wallet` (production-ready baseline).
- External gateways: **`POST /api/v1/checkout/gateway/init`** returns **503 stub** until PSP drivers implement initiation + signed callbacks.
- Registry: `config/marketplace.php` `payment_gateways` + env `PAYMENT_*_DRIVER`.

## 5. Points conversion

- Env: `MARKETPLACE_POINTS_CASH_PER_1000` — cash value **per 1000 points** (same currency unit as package prices). Default `0` disables redemption beyond allocation returning full cash.

## 6. Incident prompts (finance / inventory)

1. Stop destructive admin actions if ledger mismatch suspected.
2. Run `WalletService::ledgerChainMatchesBalance` for affected wallets (tinker or diagnostic command).
3. Compare `cards.status` reserved vs `reservation_expires_at` vs `reservations:release-expired` logs.
4. Search logs for `wallet.debit`, `wallet.refund`, `wallet.debit_checkout`.

## 7. Go-live checklist (minimal)

- [ ] Migrations applied; schedule daemon active.
- [ ] `APP_ENV=production`, `APP_DEBUG=false`, secure `APP_KEY`.
- [ ] Wallet + checkout smoke tests on staging.
- [ ] Demo seed **not** enabled in production DB.

## 8. Post-launch monitoring

- Pending **topups** / **withdrawals** counts on admin dashboard.
- Growth of **`reserved`** cards without matching orders (possible leak if workers fail).
- Duplicate `external_reference` collisions in logs for wallet ledger.
