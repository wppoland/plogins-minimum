# Minimum - Order Quantity Rules for WooCommerce

Define quantity rules (minimum, maximum and step) per product, per category, or
globally, plus an optional minimum order total. Rules are enforced when products
are added to the cart and again at checkout, with clear notices that block
checkout until every rule is satisfied.

## Features

- Set min / max / step quantity rules per product, per category, or store-wide.
- Optional minimum order total.
- Rules resolve in order of specificity: product overrides category, which overrides global.
- Enforced on add-to-cart, in the cart, and at checkout, with clear customer notices.
- Translation-ready and self-contained — no third-party runtime dependencies.

## Installation

1. Upload the plugin to `/wp-content/plugins/minimum`, or install it via Plugins → Add New.
2. Activate it. WooCommerce must be installed and active.
3. Configure rules under WooCommerce → Minimum, and set per-product or per-category overrides where needed.

## Frequently Asked Questions

**Does it require WooCommerce?**
Yes. WooCommerce must be installed and active.

**Which rule wins when several apply?**
The most specific one: a product rule overrides a category rule, which overrides
the global rule.

Built by WPPoland — https://plogins.com

License: GPL-2.0-or-later
