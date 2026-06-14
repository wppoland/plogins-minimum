<?php
/**
 * Minimum uninstall routine.
 *
 * Removes plugin options when the user deletes the plugin. Minimum creates no
 * custom tables; all data lives in wp_options.
 *
 * @package Minimum
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

delete_option( 'minimum_settings' );
delete_option( 'minimum_db_version' );
