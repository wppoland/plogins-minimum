<?php
/**
 * Boot order: services listed here are resolved from the container and have
 * their registerHooks() called during Plugin::boot(). Each must implement
 * Minimum\Contract\HasHooks. Admin-only services are appended in wp-admin.
 *
 * @package Minimum
 *
 * @return array<class-string>
 */

declare(strict_types=1);

use Minimum\Rules\Admin;
use Minimum\Rules\Validator;

defined( 'ABSPATH' ) || exit;

$minimum_hooks = array(
	Validator::class,
);

if ( is_admin() ) {
	$minimum_hooks[] = Admin::class;
}

return $minimum_hooks;
