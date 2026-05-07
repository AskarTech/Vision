# Legacy Guide

## Objective

Use legacy_code as historical context only.

## Non-Negotiables

- Do not modify files under legacy_code.
- Do not copy-paste legacy implementation into current Laravel app.
- Do not treat legacy schema or naming as final design.

## What Legacy Is Good For

- Understanding old business flows and user expectations.
- Discovering historical naming and operational patterns.
- Extracting dashboard and workflow ideas.

## What Must Be Reimplemented Cleanly

- Transaction safety and rollback behavior.
- Checkout and stock locking behavior.
- Topup and withdrawal review logic.
- Dispute and refund processes.

## Migration Mapping Reference

- customers -> users + wallets
- partners -> sellers
- network_managers -> seller_managers (+ users role)
- transactions -> wallet_transactions + card_orders + card_order_items
- payment_settings -> payment_gateways

## Conflict Handling

When docs, current implementation, and legacy differ:

1. Document the exact conflict.
2. Describe risk and business impact.
3. Propose options with trade-offs.
4. Recommend the safest incremental option.
5. Implement only after alignment.
