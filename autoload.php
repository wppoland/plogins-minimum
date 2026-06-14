<?php
/**
 * PSR-4 autoloader for the Minimum plugin.
 *
 * Maps the Minimum\ namespace to src/, with no Composer overhead. The plugin is
 * fully self-contained and ships no third-party runtime dependencies.
 *
 * @package Minimum
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

spl_autoload_register(
	static function ( string $class_name ): void {
		if ( 0 !== strncmp( $class_name, 'Minimum\\', 8 ) ) {
			return;
		}

		$relative = str_replace( '\\', '/', substr( $class_name, 8 ) );
		$base_dir = __DIR__ . '/src/';
		$file     = $base_dir . $relative . '.php';

		if ( file_exists( $file ) ) {
			require_once $file;
		}
	}
);
