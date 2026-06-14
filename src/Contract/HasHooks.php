<?php
/**
 * Hook-subscriber contract.
 *
 * @package Minimum\Contract
 */

declare(strict_types=1);

namespace Minimum\Contract;

defined( 'ABSPATH' ) || exit;

/**
 * A service that registers its own WordPress hooks during boot.
 */
interface HasHooks {

	/**
	 * Register WordPress hooks for this service.
	 */
	public function registerHooks(): void;
}
