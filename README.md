# Minimum - Order Quantity Rules for WooCommerce

Define quantity rules (min / max / step) per product, per category, or globally, plus an optional
minimum order total. Rules are enforced when products are added to the cart and again at checkout,
with clear notices that block checkout until every rule is satisfied.

This is the **free, WordPress.org-ready** plugin. It is self-contained — no third-party runtime
dependencies. The premium add-on lives in the separate private repo `wppoland/minimum-pro`.

## Development

There is no build step; shipped assets are the source.

```bash
composer install          # dev tooling (PHPCS, PHPStan, WC stubs)
composer cs               # WordPress Coding Standards
composer cs:fix           # auto-fix
composer analyse          # PHPStan level 6
```

## Architecture

- **Bootstrap** (`minimum.php`): PHP/WC guards, HPOS + cart-blocks compat, boots on `init` priority
  0, fires `do_action('minimum/booted', Plugin::instance())` from `Plugin::boot()`.
- **Autoload** (`autoload.php`): self-contained PSR-4 autoloader for the `Minimum\` namespace.
- **DI**: `src/Plugin.php` singleton + `src/Container.php`; services in `config/services.php`, boot
  order in `config/hooks.php`; `src/Migrator.php` seeds defaults on first run.
- **Rules**: `src/Rules/Settings.php` (option schema + sanitisation), `RulesRepository.php`
  (resolves effective min/max/step per product, product > category > global), `Validator.php`
  (enforces on add-to-cart, cart, and checkout), `Admin.php` (WooCommerce submenu settings page).

## Extending

PRO companions and integrations can hook:

- `minimum/booted` — fires after the plugin boots, passing the `Plugin` instance.
- `minimum/product_constraints` — filter the resolved `{min, max, step}` constraints for a product.
