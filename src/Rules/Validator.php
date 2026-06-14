<?php
/**
 * Enforces quantity and order-total rules on the cart and at checkout.
 *
 * @package Minimum\Rules
 */

declare(strict_types=1);

namespace Minimum\Rules;

defined( 'ABSPATH' ) || exit;

use Minimum\Contract\HasHooks;

/**
 * Hooks WooCommerce to block invalid quantities and under-minimum order totals.
 *
 * Three enforcement points:
 *  - woocommerce_add_to_cart_validation: stop bad quantities entering the cart.
 *  - woocommerce_check_cart_items: surface notices in cart and block checkout.
 *  - woocommerce_after_checkout_validation: hard stop on the classic checkout.
 *
 * All quantity violations and the order-total rule are evaluated by inspecting
 * the current cart; this covers both classic and block cart/checkout.
 */
final class Validator implements HasHooks {

	/**
	 * Constructor.
	 *
	 * @param RulesRepository $rules Rule resolver.
	 */
	public function __construct(
		private readonly RulesRepository $rules,
	) {}

	/**
	 * Register WordPress / WooCommerce hooks.
	 */
	public function registerHooks(): void {
		add_filter( 'woocommerce_add_to_cart_validation', array( $this, 'validate_add_to_cart' ), 10, 5 );
		add_action( 'woocommerce_check_cart_items', array( $this, 'validate_cart' ) );
		add_action( 'woocommerce_after_checkout_validation', array( $this, 'validate_checkout' ), 10, 2 );
	}

	/**
	 * Validate a single add-to-cart action against quantity rules.
	 *
	 * @param bool  $passed      Current validation state.
	 * @param int   $product_id  Product ID being added.
	 * @param int   $quantity    Quantity being added.
	 * @param int   $variation_id Variation ID (0 if none).
	 * @param mixed $variations Chosen variation attributes (unused).
	 */
	public function validate_add_to_cart( bool $passed, int $product_id, int $quantity, int $variation_id = 0, mixed $variations = array() ): bool {
		if ( ! $passed || ! $this->settings()->is_enabled() ) {
			return $passed;
		}

		$target_id = $variation_id > 0 ? $variation_id : $product_id;

		// Account for what is already in the cart for this product.
		$existing = $this->cart_quantity_for( $target_id, $product_id );
		$total    = $existing + max( 0, $quantity );

		$errors = $this->quantity_errors( $target_id, $product_id, $total );

		if ( array() === $errors ) {
			return $passed;
		}

		foreach ( $errors as $message ) {
			wc_add_notice( esc_html( $message ), 'error' );
		}

		return false;
	}

	/**
	 * Validate the whole cart: every line's quantity plus the order total.
	 */
	public function validate_cart(): void {
		if ( ! $this->settings()->is_enabled() || ! $this->cart_available() ) {
			return;
		}

		$cart     = WC()->cart;
		$reported = array();

		foreach ( $cart->get_cart() as $item ) {
			if ( ! is_array( $item ) ) {
				continue;
			}

			$product_id   = (int) ( $item['product_id'] ?? 0 );
			$variation_id = (int) ( $item['variation_id'] ?? 0 );
			$target_id    = $variation_id > 0 ? $variation_id : $product_id;
			$quantity     = (int) ( $item['quantity'] ?? 0 );

			if ( $target_id <= 0 || $quantity <= 0 ) {
				continue;
			}

			foreach ( $this->quantity_errors( $target_id, $product_id, $quantity ) as $message ) {
				// Avoid duplicate notices when several lines share a product.
				if ( in_array( $message, $reported, true ) ) {
					continue;
				}
				$reported[] = $message;
				wc_add_notice( esc_html( $message ), 'error' );
			}
		}

		$this->validate_order_total();
	}

	/**
	 * Hard-block the classic checkout when rules are unmet.
	 *
	 * @param array<string, mixed> $data   Posted checkout data (unused).
	 * @param \WP_Error            $errors Error bag to append to.
	 */
	public function validate_checkout( array $data, \WP_Error $errors ): void {
		if ( ! $this->settings()->is_enabled() || ! $this->cart_available() ) {
			return;
		}

		foreach ( WC()->cart->get_cart() as $item ) {
			if ( ! is_array( $item ) ) {
				continue;
			}
			$product_id   = (int) ( $item['product_id'] ?? 0 );
			$variation_id = (int) ( $item['variation_id'] ?? 0 );
			$target_id    = $variation_id > 0 ? $variation_id : $product_id;
			$quantity     = (int) ( $item['quantity'] ?? 0 );

			if ( $target_id <= 0 || $quantity <= 0 ) {
				continue;
			}

			foreach ( $this->quantity_errors( $target_id, $product_id, $quantity ) as $message ) {
				if ( ! in_array( $message, (array) $errors->get_error_messages( 'minimum_qty' ), true ) ) {
					$errors->add( 'minimum_qty', esc_html( $message ) );
				}
			}
		}

		$total_error = $this->order_total_error();
		if ( null !== $total_error ) {
			$errors->add( 'minimum_order_total', esc_html( $total_error ) );
		}
	}

	/**
	 * Add an order-total notice if the cart subtotal is under the minimum.
	 */
	private function validate_order_total(): void {
		$error = $this->order_total_error();
		if ( null !== $error ) {
			wc_add_notice( esc_html( $error ), 'error' );
		}
	}

	/**
	 * Build the order-total error message, or null if the rule passes/disabled.
	 */
	private function order_total_error(): ?string {
		$minimum = $this->settings()->min_order_total();
		if ( $minimum <= 0 || ! $this->cart_available() ) {
			return null;
		}

		$subtotal = (float) WC()->cart->get_subtotal();

		if ( $subtotal >= $minimum ) {
			return null;
		}

		return strtr(
			$this->settings()->message( 'msg_min_total' ),
			array(
				'{min}'   => wp_strip_all_tags( wc_price( $minimum ) ),
				'{total}' => wp_strip_all_tags( wc_price( $subtotal ) ),
			),
		);
	}

	/**
	 * Collect quantity error messages for one product at a given total quantity.
	 *
	 * @param int $target_id  Product or variation ID (for constraint lookup).
	 * @param int $product_id Parent product ID (for naming).
	 * @param int $quantity   Effective quantity to validate.
	 * @return array<int, string>
	 */
	private function quantity_errors( int $target_id, int $product_id, int $quantity ): array {
		$constraints = $this->rules->constraints_for_product( $target_id );
		$errors      = array();
		$name        = $this->product_name( $target_id, $product_id );

		if ( $constraints['min'] > 0 && $quantity < $constraints['min'] ) {
			$errors[] = strtr(
				$this->settings()->message( 'msg_min_qty' ),
				array(
					'{min}'     => (string) $constraints['min'],
					'{product}' => $name,
				),
			);
		}

		if ( $constraints['max'] > 0 && $quantity > $constraints['max'] ) {
			$errors[] = strtr(
				$this->settings()->message( 'msg_max_qty' ),
				array(
					'{max}'     => (string) $constraints['max'],
					'{product}' => $name,
				),
			);
		}

		if ( $constraints['step'] > 1 && 0 !== $quantity % $constraints['step'] ) {
			$errors[] = strtr(
				$this->settings()->message( 'msg_step_qty' ),
				array(
					'{step}'    => (string) $constraints['step'],
					'{product}' => $name,
				),
			);
		}

		return $errors;
	}

	/**
	 * Human-readable product name for messages.
	 *
	 * @param int $target_id  Variation ID if set, else product ID.
	 * @param int $product_id Parent product ID (used in the fallback label).
	 */
	private function product_name( int $target_id, int $product_id ): string {
		if ( ! function_exists( 'wc_get_product' ) ) {
			return '#' . $product_id;
		}

		$product = wc_get_product( $target_id > 0 ? $target_id : $product_id );
		if ( $product instanceof \WC_Product ) {
			return $product->get_name();
		}

		return '#' . $product_id;
	}

	/**
	 * Quantity already in the cart for a product (matching variation if given).
	 *
	 * @param int $target_id  Variation ID if set, else product ID.
	 * @param int $product_id Parent product ID.
	 */
	private function cart_quantity_for( int $target_id, int $product_id ): int {
		if ( ! $this->cart_available() ) {
			return 0;
		}

		$count = 0;
		foreach ( WC()->cart->get_cart() as $item ) {
			if ( ! is_array( $item ) ) {
				continue;
			}
			$item_variation = (int) ( $item['variation_id'] ?? 0 );
			$item_product   = (int) ( $item['product_id'] ?? 0 );
			$item_target    = $item_variation > 0 ? $item_variation : $item_product;

			if ( $item_target === $target_id ) {
				$count += (int) ( $item['quantity'] ?? 0 );
			}
		}

		return $count;
	}

	/**
	 * Whether the WooCommerce cart is usable in the current request.
	 */
	private function cart_available(): bool {
		return function_exists( 'WC' ) && WC()->cart instanceof \WC_Cart;
	}

	/**
	 * Convenience accessor for the settings store.
	 */
	private function settings(): Settings {
		return $this->rules->settings();
	}
}
