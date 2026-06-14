<?php
/**
 * Service wiring. Returns a closure that registers every service into the DI
 * container. Bindings are lazy; the admin service is only registered in
 * wp-admin context.
 *
 * @package Minimum
 */

declare(strict_types=1);

use Minimum\Container;
use Minimum\Migrator;
use Minimum\Rules\Admin;
use Minimum\Rules\RulesRepository;
use Minimum\Rules\Settings;
use Minimum\Rules\Validator;

defined( 'ABSPATH' ) || exit;

return static function ( Container $c ): void {
	$c->singleton( Migrator::class, static fn (): Migrator => new Migrator() );

	$c->singleton( Settings::class, static fn (): Settings => new Settings() );

	$c->singleton(
		RulesRepository::class,
		static fn (): RulesRepository => new RulesRepository( $c->get( Settings::class ) ),
	);

	$c->singleton(
		Validator::class,
		static fn (): Validator => new Validator( $c->get( RulesRepository::class ) ),
	);

	if ( is_admin() ) {
		$c->singleton(
			Admin::class,
			static fn (): Admin => new Admin( $c->get( Settings::class ) ),
		);
	}
};
