<?php

/**
 * Class Wp_Lookout_Cli - handles the WP CLI commands.
 */

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	return;
}

WP_CLI::add_command( 'wplookout', 'Wp_Lookout_Cli' );

/**
 * Manage WP Lookout settings.
 */
class Wp_Lookout_Cli extends WP_CLI_Command {
	/**
	 * Sets the WP Lookout account API key to use for updates.
	 *
	 * ## OPTIONS
	 *
	 * <key>
	 * : The API key to use for communications with the WP Lookout app.
	 *
	 * ## EXAMPLES
	 *
	 *     wp wplookout set_api_key 123456ABCDEF
	 *
	 * @when after_wp_load
	 */
	public function set_api_key( $args, $assoc_args ) {
		if ( empty( $args[0] ) ) {
			WP_CLI::error( __( 'No key specified.', 'wp-lookout' ) );
			return;
		}

		$wpl_options                       = get_option( WP_LOOKOUT_SETTINGS_OPTION );
		$wpl_options['wp_lookout_api_key'] = esc_attr( $args[0] );
		update_option( WP_LOOKOUT_SETTINGS_OPTION, $wpl_options );

		WP_CLI::success( __( 'WP Lookout settings updated.', 'wp-lookout' ) );
	}

	/**
	 * Hides or un-hides the WP Lookout settings page in the WordPress admin
	 *
	 * ## OPTIONS
	 *
	 * <boolean>
	 * : true to hide the page, false to un-hide the page.
	 *
	 * ## EXAMPLES
	 *
	 *     wp wplookout hide_settings_page true
	 *
	 * @when after_wp_load
	 */

	public function hide_settings_page( $args, $assoc_args ) {
		if ( empty( $args[0] ) || ! in_array( $args[0], array( 'true', 'false' ), true ) ) {
			WP_CLI::error( __( 'No boolean specified.', 'wp-lookout' ) );
			return;
		}

		$wpl_options                       = get_option( WP_LOOKOUT_SETTINGS_OPTION );
		$wpl_options['hide_settings_page'] = rest_sanitize_boolean( $args[0] );
		update_option( WP_LOOKOUT_SETTINGS_OPTION, $wpl_options );

		WP_CLI::success( __( 'WP Lookout settings updated.', 'wp-lookout' ) );
	}

}
