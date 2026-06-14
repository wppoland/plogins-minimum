<?php
/**
 * Idempotent version migrations and first-run defaults.
 *
 * @package Minimum
 */

declare(strict_types=1);

namespace Minimum;

defined( 'ABSPATH' ) || exit;

use Minimum\Rules\Settings;

/**
 * Idempotent schema/version migrations, run on every boot. Compares a stored
 * option against VERSION and applies forward steps (currently just seeding
 * sane defaults on first run) as needed.
 */
final class Migrator {

	private const OPTION = 'minimum_db_version';

	/**
	 * Run any pending migrations. Safe to call on every boot.
	 */
	public function maybeMigrate(): void {
		$current = (string) get_option( self::OPTION, '0' );

		if ( version_compare( $current, VERSION, '>=' ) ) {
			return;
		}

		// Seed defaults on first install so the settings screen is never empty.
		if ( false === get_option( Settings::OPTION, false ) ) {
			add_option( Settings::OPTION, Settings::defaults() );
		}

		update_option( self::OPTION, VERSION, false );
	}
}
