<?php
/**
 * Main plugin class.
 *
 * Wires the DI container and boots every HasHooks service listed in
 * config/hooks.php.
 *
 * @package Minimum
 */

declare(strict_types=1);

namespace Minimum;

defined( 'ABSPATH' ) || exit;

use Minimum\Contract\HasHooks;

/**
 * Plugin singleton.
 */
final class Plugin {

	/**
	 * Shared singleton instance.
	 *
	 * @var self|null
	 */
	private static ?self $instance = null;

	/**
	 * Dependency-injection container.
	 *
	 * @var Container
	 */
	private Container $container;

	/**
	 * Whether the plugin has already booted.
	 *
	 * @var bool
	 */
	private bool $booted = false;

	/**
	 * Private constructor — use Plugin::instance().
	 *
	 * Service factories are registered eagerly so that activation hooks never
	 * encounter an empty container.
	 */
	private function __construct() {
		$this->container = new Container();
		( require PLUGIN_DIR . '/config/services.php' )( $this->container );
	}

	/**
	 * Returns the shared plugin instance, creating it on first call.
	 */
	public static function instance(): self {
		return self::$instance ??= new self();
	}

	/**
	 * Returns the DI container.
	 */
	public function container(): Container {
		return $this->container;
	}

	/**
	 * Absolute path to the plugin directory (with optional relative segment appended).
	 *
	 * @param string $relative Optional relative path to append.
	 */
	public function path( string $relative = '' ): string {
		return PLUGIN_DIR . ( '' !== $relative ? '/' . ltrim( $relative, '/' ) : '' );
	}

	/**
	 * URL to the plugin directory (with optional relative segment appended).
	 *
	 * @param string $relative Optional relative path to append.
	 */
	public function url( string $relative = '' ): string {
		return plugins_url( $relative, PLUGIN_FILE );
	}

	/**
	 * Boot the plugin: run migrations, then register every hook subscriber.
	 */
	public function boot(): void {
		if ( $this->booted ) {
			return;
		}
		$this->booted = true;

		$this->container->get( Migrator::class )->maybeMigrate();

		/**
		 * Ordered list of hook-subscriber class names to boot.
		 *
		 * @var array<class-string<HasHooks>> $hooks
		 */
		$hooks = require PLUGIN_DIR . '/config/hooks.php';
		foreach ( $hooks as $id ) {
			if ( ! $this->container->has( $id ) ) {
				continue;
			}
			$service = $this->container->get( $id );
			if ( $service instanceof HasHooks ) {
				$service->registerHooks();
			}
		}

		/**
		 * Fires after the plugin has fully booted and all services are registered.
		 *
		 * Add-ons (e.g. Minimum Pro) listen for this to extend the shared
		 * container and register their own hooks.
		 *
		 * @param Plugin $plugin The booted plugin instance.
		 */
		do_action( 'minimum/booted', $this );
	}
}
