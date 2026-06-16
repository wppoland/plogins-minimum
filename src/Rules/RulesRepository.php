<?php
/**
 * Resolves which quantity rules apply to a given product.
 *
 * @package Minimum\Rules
 */

declare(strict_types=1);

namespace Minimum\Rules;

defined( 'ABSPATH' ) || exit;

/**
 * Computes the effective min/max/step constraints for a product by merging the
 * global rule with the most specific matching product/category rule.
 *
 * Specificity: a product-scoped rule beats a category-scoped rule, which beats
 * the global rule. For each constraint (min/max/step) the most specific rule
 * that sets a non-zero value wins.
 */
final class RulesRepository {

	/**
	 * Constructor.
	 *
	 * @param Settings $settings Settings store.
	 */
	public function __construct(
		private readonly Settings $settings,
	) {}

	/**
	 * Expose the settings store to collaborators (e.g. the validator).
	 */
	public function settings(): Settings {
		return $this->settings;
	}

	/**
	 * Resolve the effective constraints for a product.
	 *
	 * @param int $product_id A product or variation ID.
	 * @return array{min: int, max: int, step: int}
	 */
	public function constraints_for_product( int $product_id ): array {
		$effective = array(
			'min'  => 0,
			'max'  => 0,
			'step' => 0,
		);

		if ( $product_id <= 0 ) {
			return $effective;
		}

		$category_ids = $this->category_ids( $product_id );
		$parent_id    = $this->parent_id( $product_id );

		// Track the specificity weight of the rule that currently owns each value.
		$held_weight = array(
			'min'  => 0,
			'max'  => 0,
			'step' => 0,
		);

		foreach ( $this->settings->rules() as $rule ) {
			if ( ! $this->rule_matches( $rule, $product_id, $parent_id, $category_ids ) ) {
				continue;
			}

			$weight = $this->specificity( $rule['scope'] );

			foreach ( array( 'min', 'max', 'step' ) as $key ) {
				if ( $rule[ $key ] <= 0 ) {
					continue;
				}
				// Apply if nothing set yet, or this rule is at least as specific.
				if ( 0 === $effective[ $key ] || $weight >= $held_weight[ $key ] ) {
					$effective[ $key ]   = $rule[ $key ];
					$held_weight[ $key ] = $weight;
				}
			}
		}

		/**
		 * Filters the resolved min/max/step constraints for a product.
		 *
		 * Add-ons (e.g. Minimum Pro) use this to layer per-product overrides on
		 * top of the global/category/product rules resolved above. Returned
		 * values must keep the array{min: int, max: int, step: int} shape.
		 *
		 * @param array{min: int, max: int, step: int} $effective  Resolved constraints.
		 * @param int                                  $product_id Product or variation ID.
		 */
		$filtered = apply_filters( 'minimum/product_constraints', $effective, $product_id );

		return array(
			'min'  => isset( $filtered['min'] ) ? (int) $filtered['min'] : $effective['min'],
			'max'  => isset( $filtered['max'] ) ? (int) $filtered['max'] : $effective['max'],
			'step' => isset( $filtered['step'] ) ? (int) $filtered['step'] : $effective['step'],
		);
	}

	/**
	 * Does a rule apply to this product?
	 *
	 * @param array{scope: string, target: int, min: int, max: int, step: int} $rule         Rule row.
	 * @param int                                                              $product_id   Product/variation ID.
	 * @param int                                                              $parent_id    Parent product ID (0 if none).
	 * @param array<int, int>                                                  $category_ids Category term IDs.
	 */
	private function rule_matches( array $rule, int $product_id, int $parent_id, array $category_ids ): bool {
		switch ( $rule['scope'] ) {
			case 'global':
				return true;
			case 'product':
				return $rule['target'] === $product_id || ( $parent_id > 0 && $rule['target'] === $parent_id );
			case 'category':
				return in_array( $rule['target'], $category_ids, true );
			default:
				return false;
		}
	}

	/**
	 * Specificity weight per scope (higher = more specific).
	 *
	 * @param string $scope Rule scope.
	 */
	private function specificity( string $scope ): int {
		return match ( $scope ) {
			'product'  => 3,
			'category' => 2,
			default    => 1,
		};
	}

	/**
	 * Category term IDs for a product (resolving variations to their parent).
	 *
	 * @param int $product_id Product or variation ID.
	 * @return array<int, int>
	 */
	private function category_ids( int $product_id ): array {
		$parent    = $this->parent_id( $product_id );
		$lookup_id = $parent > 0 ? $parent : $product_id;
		$terms     = get_the_terms( $lookup_id, 'product_cat' );

		if ( ! is_array( $terms ) ) {
			return array();
		}

		return array_map( static fn ( $term ): int => (int) $term->term_id, $terms );
	}

	/**
	 * Parent product ID for a variation, or 0 for simple products.
	 *
	 * @param int $product_id Product or variation ID.
	 */
	private function parent_id( int $product_id ): int {
		if ( ! function_exists( 'wc_get_product' ) ) {
			return 0;
		}

		$product = wc_get_product( $product_id );
		if ( ! $product instanceof \WC_Product ) {
			return 0;
		}

		return (int) $product->get_parent_id();
	}
}
