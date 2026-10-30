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

/**
 * Remove this plugin's options from the current site.
 */
function minimum_uninstall_site(): void {
	delete_option( 'minimum_settings' );
	delete_option( 'minimum_db_version' );
}

// Options are per site, so a network uninstall has to visit every site.
if ( is_multisite() ) {
	foreach ( get_sites( array( 'fields' => 'ids', 'number' => 0 ) ) as $minimum_site_id ) {
		switch_to_blog( (int) $minimum_site_id );
		minimum_uninstall_site();
		restore_current_blog();
	}
	unset( $minimum_site_id );
} else {
	minimum_uninstall_site();
}
