<?php
/**
 * Settings store and schema for Minimum.
 *
 * Owns the canonical option shape, default values, sanitisation and typed
 * accessors. The option key is `minimum_settings` (a single array).
 *
 * @package Minimum\Rules
 */

declare(strict_types=1);

namespace Minimum\Rules;

defined( 'ABSPATH' ) || exit;

/**
 * Reads, writes and sanitises the plugin settings.
 *
 * Stored shape (`minimum_settings`):
 *  - enabled            bool   Master on/off switch.
 *  - rules              array  List of rule rows (see sanitize_rule()).
 *  - min_order_total    float  Minimum cart subtotal required to check out (0 = off).
 *  - msg_min_qty        string Notice for under-minimum quantity. Tokens: {min} {product}.
 *  - msg_max_qty        string Notice for over-maximum quantity. Tokens: {max} {product}.
 *  - msg_step_qty       string Notice for invalid step. Tokens: {step} {product}.
 *  - msg_min_total      string Notice for under-minimum order total. Tokens: {min} {total}.
 *
 * Each rule row:
 *  - scope    string 'global' | 'product' | 'category' | 'role'
 *  - target   int    Product ID or term ID (0 for global/role)
 *  - role     string Role slug when scope is `role` (`guest` for visitors)
 *  - min      int    Minimum quantity (0 = no minimum)
 *  - max      int    Maximum quantity (0 = no maximum)
 *  - step     int    Quantity must be a multiple of this (0/1 = no step)
 */
final class Settings {

	public const OPTION = 'minimum_settings';

	/** Allowed rule scopes. */
	public const SCOPES = array( 'global', 'product', 'category', 'role' );

	/**
	 * Default settings used on first install and as a fallback.
	 *
	 * @return array<string, mixed>
	 */
	public static function defaults(): array {
		return array(
			'enabled'         => true,
			'rules'           => array(),
			'min_order_total' => 0.0,
			'msg_min_qty'     => __( 'You must buy at least {min} of "{product}".', 'plogins-minimum' ),
			'msg_max_qty'     => __( 'You can buy at most {max} of "{product}".', 'plogins-minimum' ),
			'msg_step_qty'    => __( '"{product}" must be bought in multiples of {step}.', 'plogins-minimum' ),
			'msg_min_total'   => __( 'Your order total must be at least {min} (currently {total}).', 'plogins-minimum' ),
		);
	}

	/**
	 * Return the full, defaults-merged settings array.
	 *
	 * @return array<string, mixed>
	 */
	public function all(): array {
		$stored = get_option( self::OPTION, array() );
		if ( ! is_array( $stored ) ) {
			$stored = array();
		}

		return array_merge( self::defaults(), $stored );
	}

	/**
	 * Whether enforcement is enabled.
	 */
	public function is_enabled(): bool {
		return (bool) $this->all()['enabled'];
	}

	/**
	 * Minimum required order total (0 = disabled).
	 */
	public function min_order_total(): float {
		$minimum = (float) $this->all()['min_order_total'];

		/**
		 * Filters the minimum order total required before checkout.
		 *
		 * @param float $minimum Minimum cart subtotal (0 = disabled).
		 */
		return (float) apply_filters( 'minimum/min_order_total', $minimum );
	}

	/**
	 * Normalised list of rule rows.
	 *
	 * @return array<int, array{scope: string, target: int, min: int, max: int, step: int}>
	 */
	public function rules(): array {
		$all   = $this->all();
		$rules = is_array( $all['rules'] ?? null ) ? $all['rules'] : array();
		$out   = array();

		foreach ( $rules as $rule ) {
			$clean = $this->sanitize_rule( is_array( $rule ) ? $rule : array() );
			if ( null !== $clean ) {
				$out[] = $clean;
			}
		}

		/**
		 * Filters the resolved quantity rule rows before they are matched to products.
		 *
		 * Add-ons (e.g. Minimum Pro) use this to inject per-role rules. Each row must
		 * use the array{scope: string, target: int, min: int, max: int, step: int}
		 * shape. Use scope `role`, `role` = role slug (`guest` for visitors).
		 *
		 * @param array<int, array{scope: string, target: int, min: int, max: int, step: int}> $out Rule rows.
		 */
		return apply_filters( 'minimum/rules', $out );
	}

	/**
	 * Fetch a message by key, falling back to its default.
	 *
	 * @param string $key One of the msg_* keys.
	 */
	public function message( string $key ): string {
		$all = $this->all();
		$val = (string) ( $all[ $key ] ?? '' );

		if ( '' === trim( $val ) ) {
			$defaults = self::defaults();
			return (string) ( $defaults[ $key ] ?? '' );
		}

		return $val;
	}

	/**
	 * Sanitise and normalise the full settings array before saving.
	 *
	 * @param mixed $raw Raw POST data.
	 * @return array<string, mixed>
	 */
	public function sanitize( mixed $raw ): array {
		if ( ! is_array( $raw ) ) {
			return self::defaults();
		}

		$clean = array(
			'enabled'         => ! empty( $raw['enabled'] ),
			'rules'           => array(),
			'min_order_total' => $this->sanitize_amount( $raw['min_order_total'] ?? 0 ),
			'msg_min_qty'     => sanitize_text_field( (string) ( $raw['msg_min_qty'] ?? '' ) ),
			'msg_max_qty'     => sanitize_text_field( (string) ( $raw['msg_max_qty'] ?? '' ) ),
			'msg_step_qty'    => sanitize_text_field( (string) ( $raw['msg_step_qty'] ?? '' ) ),
			'msg_min_total'   => sanitize_text_field( (string) ( $raw['msg_min_total'] ?? '' ) ),
		);

		if ( is_array( $raw['rules'] ?? null ) ) {
			foreach ( $raw['rules'] as $rule ) {
				$row = $this->sanitize_rule( is_array( $rule ) ? $rule : array() );
				if ( null !== $row ) {
					$clean['rules'][] = $row;
				}
			}
		}

		return $clean;
	}

	/**
	 * Sanitise one rule row. Returns null for empty/meaningless rows so they
	 * are dropped rather than persisted as noise.
	 *
	 * @param array<string, mixed> $rule Raw rule row.
	 * @return array{scope: string, target: int, min: int, max: int, step: int}|null
	 */
	public function sanitize_rule( array $rule ): ?array {
		$scope = sanitize_key( (string) ( $rule['scope'] ?? 'global' ) );
		if ( ! in_array( $scope, self::SCOPES, true ) ) {
			$scope = 'global';
		}

		// Role rules are injected by add-ons via minimum/rules, not the FREE settings UI.
		if ( 'role' === $scope ) {
			return null;
		}

		$target = 'global' === $scope ? 0 : absint( $rule['target'] ?? 0 );
		$min    = absint( $rule['min'] ?? 0 );
		$max    = absint( $rule['max'] ?? 0 );
		$step   = absint( $rule['step'] ?? 0 );

		// A non-global rule with no valid target is meaningless.
		if ( 'global' !== $scope && $target <= 0 ) {
			return null;
		}

		// A rule that constrains nothing is dropped.
		if ( 0 === $min && 0 === $max && $step <= 1 ) {
			return null;
		}

		// If both bounds are set and inverted, drop the max (min wins).
		if ( $min > 0 && $max > 0 && $max < $min ) {
			$max = 0;
		}

		return array(
			'scope'  => $scope,
			'target' => $target,
			'min'    => $min,
			'max'    => $max,
			'step'   => $step,
		);
	}

	/**
	 * Normalise a monetary amount using WooCommerce's decimal formatter when
	 * available, otherwise a plain float cast.
	 *
	 * @param mixed $value Raw amount.
	 */
	private function sanitize_amount( mixed $value ): float {
		if ( function_exists( 'wc_format_decimal' ) ) {
			return (float) wc_format_decimal( $value, false, true );
		}

		return max( 0.0, (float) $value );
	}
}
