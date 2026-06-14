=== Minimum - Order Quantity Rules for WooCommerce ===
Contributors: wppoland
Tags: woocommerce, minimum order, quantity, order rules, minimum quantity
Requires at least: 6.5
Tested up to: 7.0
Requires PHP: 8.1
Requires Plugins: woocommerce
Stable tag: 0.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Set minimum, maximum and step quantity rules plus a minimum order total, enforced at the cart and checkout with clear blocking notices.

== Description ==

Minimum lets you control how much of each product customers must buy — and how much they must spend — before they can check out. Define quantity rules per product, per category, or globally, plus an optional minimum order total. Rules are enforced when products are added to the cart and again at checkout, with clear notices that block checkout until every rule is satisfied.

**What you can control**

* **Minimum quantity** — require at least N units of a product before checkout.
* **Maximum quantity** — cap how many units a customer can buy.
* **Step quantity** — force purchases in multiples (e.g. sold in packs of 6).
* **Minimum order total** — require a minimum cart subtotal to check out.
* **Custom messages** — write the exact notice shown when each rule is unmet, with replacement tokens.

**Why Minimum?**

* **Rule precedence that makes sense.** A specific product rule overrides a category rule, which overrides the global rule — per constraint.
* **Enforced where it matters.** Validation runs on add-to-cart, in the cart, and at checkout, covering both the classic and block-based cart/checkout.
* **HPOS and Cart/Checkout Blocks compatible.** No reliance on legacy WooCommerce internals.
* **Accessible admin.** A modern, dark-mode-aware settings screen with inline help, keyboard support and screen-reader text.
* **Clean uninstall.** No custom tables. Remove the plugin and your database is exactly as it was.
* **Self-contained.** No third-party runtime dependencies.

== Installation ==

1. Install and activate WooCommerce (8.0 or later).
2. Upload the `minimum` folder to `/wp-content/plugins/`, or install directly from the WordPress plugin directory.
3. Activate the plugin through the **Plugins** screen.
4. Go to **WooCommerce → Minimum** and add at least one rule (e.g. a product with a minimum quantity of 3), or set a minimum order total.

== Frequently Asked Questions ==

= Does Minimum require WooCommerce? =
Yes. Minimum is a WooCommerce extension and requires WooCommerce 8.0 or later.

= How do rules combine? =
For each constraint (min, max, step) the most specific matching rule wins: a product rule beats a category rule, which beats the global rule. Leave a field at 0 to ignore that constraint.

= Does it work with the block cart and checkout? =
Yes. Quantity and order-total rules are evaluated against the cart, so notices appear and checkout is blocked in both the classic and block-based cart/checkout.

= Can I customise the notices? =
Yes. Each notice is editable on the settings screen and supports tokens such as {min}, {max}, {step}, {product}, {total}.

= What happens when I delete the plugin? =
The uninstall routine removes the `minimum_settings` and `minimum_db_version` options. No custom tables are created, so your database is left clean.

== Screenshots ==

1. The Minimum settings screen — rules builder (scope, min, max, step), minimum order total and customisable notice messages.

== Changelog ==

= 0.1.0 =
* Initial release: per-product / per-category / global quantity rules (min, max, step), minimum order total, customisable notices, enforced on add-to-cart and at cart/checkout. HPOS and Cart/Checkout Blocks compatible. Self-contained, no jQuery.
