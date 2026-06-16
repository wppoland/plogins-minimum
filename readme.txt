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

Set minimum, maximum and step quantity rules plus a minimum order total, enforced at the cart and checkout with notices that block checkout.

== Description ==

Minimum adds quantity and spend rules to your WooCommerce store. You decide how many units of a product a customer has to buy, cap how many they may buy, sell in fixed pack sizes, and require a minimum order total before checkout is allowed. Rules can be set for a single product, a whole category, or every product at once.

The plugin checks the cart when an item is added and again at checkout. If a rule is not met, the customer sees a notice explaining what to change, and checkout stays blocked until it is fixed.

Source code and bug reports live on GitHub: https://github.com/wppoland/minimum

What you can set up:

* Minimum quantity: require at least N units of a product before checkout.
* Maximum quantity: cap how many units a customer can buy.
* Step quantity: only allow multiples, for products sold in packs (for example, packs of 6).
* Minimum order total: require a cart subtotal before checkout is allowed.
* Notice wording: edit the message shown for each unmet rule, using tokens like {min}, {max}, {step}, {product} and {total}.

When more than one rule could apply to a product, the more specific one wins for each value: a product rule beats a category rule, and a category rule beats the global rule. Min, max and step are resolved separately, so you can mix them across scopes.

Other things worth knowing:

* Works with HPOS (custom order tables) and the Cart and Checkout blocks, as well as the classic cart and checkout. Validation reads the cart contents, so both layouts are covered.
* Settings screen follows the WordPress admin style, respects the dark colour scheme, and labels every field for keyboard and screen-reader use.
* No custom database tables. Deleting the plugin removes its two options and leaves the rest of your database untouched.
* No bundled libraries or jQuery on the settings screen.

== Installation ==

1. Install and activate WooCommerce 8.0 or later.
2. Upload the `minimum` folder to `/wp-content/plugins/`, or install the zip from the **Plugins → Add New → Upload Plugin** screen.
3. Activate the plugin through the **Plugins** screen.
4. Go to **WooCommerce → Minimum** and add a rule (for example, a product with a minimum quantity of 3), or set a minimum order total.

== Frequently Asked Questions ==

= Does it need WooCommerce? =
Yes. Minimum is a WooCommerce extension and needs WooCommerce 8.0 or later active. If WooCommerce is missing, the plugin stays dormant and shows an admin notice.

= What happens if two rules cover the same product? =
Each value is resolved on its own. For min, max and step separately, a product rule overrides a category rule, which overrides the global rule. A field left at 0 means that value is not enforced.

= Does it work with the block-based cart and checkout? =
Yes. Rules are checked against the cart contents rather than a specific template, so the same notices appear and checkout is blocked whether you use the classic pages or the Cart and Checkout blocks.

= Can I change the wording shown to customers? =
Yes. Each notice has its own field on the settings screen. Tokens such as {min}, {max}, {step}, {product} and {total} are swapped in for the matching values.

= Can I turn rules off without deleting them? =
Yes. The Enforcement switch on the settings screen stops rules being applied at the cart and checkout while keeping them saved.

= What is left behind when I delete the plugin? =
The uninstall step deletes the `minimum_settings` and `minimum_db_version` options. There are no custom tables, so nothing else is added to or left in your database.

== Screenshots ==

1. The Minimum settings screen — rules builder (scope, min, max, step), minimum order total and customisable notice messages.

== Changelog ==

= 0.1.0 =
* Initial release. Per-product, per-category and global quantity rules (min, max, step), a minimum order total, and editable notice messages. Enforced on add-to-cart and at the cart and checkout. Compatible with HPOS and the Cart and Checkout blocks. No jQuery on the settings screen.
