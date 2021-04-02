<?php

/**
 * Class Wp_Lookout_Sender - transmit information about themes and plugins to WP Lookout
 */

class Wp_Lookout_Sender {

	public function __construct() {
		add_action( 'init', array( $this, 'init' ), 1 );
	}

	public function init() {

		// Schedule the sending event for once per day
		if ( ! wp_next_scheduled( 'wp_lookout_sender_event_hook' ) ) {
			wp_schedule_event( time(), 'daily', 'wp_lookout_sender_event_hook' );
		}
		add_action( 'wp_lookout_sender_event_hook', array( $this, 'wp_lookout_send_data' ) );

	}

	/**
	 * Perform the import to WP Lookout
	 * @return false
	 */
	public function wp_lookout_send_data() {

		// Get the options with the API key
		$plugin_settings = get_option( 'wp_lookout_settings' );

		// No key, nothing to do
		if ( empty( $plugin_settings ) || empty( $plugin_settings['wp_lookout_api_key'] ) ) {
			return false;
		}

		// Collect the site URL
		$site_url = get_site_url();

		// Get all plugins
		$plugin_slug_array = array();
		$all_plugins       = get_plugins();

		// For each plugin we found, build an array
		foreach ( $all_plugins as $basename => $plugin ) {
			$slug = dirname( $basename );

			// Dirname can return '.' to indicate cwd.
			// We don't currently support plugins installed in the top level plugin directory.
			// In general, validate the slug as being an alphanumeric string at least 2 chars long.
			if ( preg_match( '/^[a-zA-Z0-9-_]{2,}$/', $slug ) ) {
				$plugin_slug_array[] = $slug;
			}
		}

		// Make a string of that array for use in the API request.
		$plugin_comma_list = implode( ',', $plugin_slug_array );

		// Get all themes
		$theme_slug_array = array();
		$all_themes       = wp_get_themes();

		// For each theme we found, build an array
		foreach ( $all_themes as $basename => $theme ) {
			// Validate the theme dir name as being an alphanumeric string at least 2 chars long.
			if ( preg_match( '/^[a-zA-Z0-9-_]{2,}$/', $basename ) ) {
				$theme_slug_array[] = $basename;
			}
		}

		// Make a string of that array for use in the API import
		$theme_comma_list = implode( ',', $theme_slug_array );

		// Put together the plugin API request body
		$plugin_api_request_body = array(
			'site_url' => $site_url,
			'type'     => 'plugin',
			'slugs'    => $plugin_comma_list,
		);

		// Send the plugins to the WP Lookout API
		$plugin_send_result = $this->send_api_request( $plugin_settings['wp_lookout_api_key'], $plugin_api_request_body );

		// Put together the theme API request body
		$theme_api_request_body = array(
			'site_url' => $site_url,
			'type'     => 'theme',
			'slugs'    => $theme_comma_list,
		);

		// Send the themes to the WP Lookout API
		$theme_send_result = $this->send_api_request( $plugin_settings['wp_lookout_api_key'], $theme_api_request_body );

		// TODO better handling of failed API requests
	}

	/**
	 * Make an API request to WP Lookout
	 * @param string $api_key
	 * @param array  $data
	 * @return array|WP_Error
	 */
	private function send_api_request( $api_key = '', $data = array() ) {

		$api_key = sanitize_text_field( $api_key );

		$api_request = wp_remote_post(
			WP_LOOKOUT_IMPORT_API_URL,
			array(
				'method'  => 'POST',
				'timeout' => '20',
				'headers' => array(
					'Authorization' => "Bearer $api_key",
				),
				'body'    => $data,
			)
		);

		return $api_request;
	}
}

new Wp_Lookout_Sender();
