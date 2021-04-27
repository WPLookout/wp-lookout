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
	 * @return bool
	 */
	public function wp_lookout_send_data(): bool {

		// Get the options with the API key
		$plugin_settings = get_option( WP_LOOKOUT_SETTINGS_OPTION );

		// No key, nothing to do
		if ( empty( $plugin_settings ) || empty( $plugin_settings['wp_lookout_api_key'] ) ) {
			return false;
		}

		// Prep some variables for the API request
		$api_request_body = array(
			'version' => 2,
		);
		$object_data      = array();

		// Collect the site URL and version
		$api_request_body['site_url']     = get_site_url();
		$api_request_body['core_version'] = get_bloginfo( 'version' );

		// Get all plugins
		$all_plugins = get_plugins();

		// For each plugin we found, build an array
		foreach ( $all_plugins as $basename => $plugin ) {
			$slug = dirname( $basename );

			// Dirname can return '.' to indicate cwd.
			// We don't currently support plugins installed in the top level plugin directory.
			// In general, validate the slug as being an alphanumeric string at least 2 chars long.
			if ( preg_match( '/^[a-zA-Z0-9-_]{2,}$/', $slug ) ) {
				$object_data[] = array(
					'slug'    => $slug,
					'type'    => 'plugin',
					'version' => ! empty( $plugin['Version'] ) ? $plugin['Version'] : null,
				);
			}
		}

		// Get all themes
		$all_themes = wp_get_themes();

		// For each theme we found, build an array
		foreach ( $all_themes as $basename => $theme ) {
			// Validate the theme dir name as being an alphanumeric string at least 2 chars long.
			if ( preg_match( '/^[a-zA-Z0-9-_]{2,}$/', $basename ) ) {
				$object_data[] = array(
					'slug'    => $basename,
					'type'    => 'theme',
					'version' => ! empty( $theme['Version'] ) ? $theme['Version'] : null,
				);
			}
		}

		// Put together the API request body
		$api_request_body['objects'] = $object_data;

		// Send the data to the WP Lookout API
		$api_send_result = $this->send_api_request( $plugin_settings['wp_lookout_api_key'], $api_request_body );

		// If there are errors and debugging / debug logging is enabled, log those errors.
		if ( WP_DEBUG && WP_DEBUG_LOG && ( 200 !== wp_remote_retrieve_response_code( $api_send_result ) ) ) {
			// @codingStandardsIgnoreStart
			error_log( 'WP Lookout debug: there was a problem with the import API response.' );
			$response_body = json_decode( wp_remote_retrieve_body( $api_send_result ), false );
			if ( ! empty( $response_body->message ) ) {
				error_log( 'WP Lookout debug: ' . print_r( $response_body->message, true ) );
			}
			if ( ! empty( $response_body->data ) ) {
				error_log( 'WP Lookout debug: ' . print_r( $response_body->data, true ) );
			}
			// @codingStandardsIgnoreEnd
		}

		return true;
	}

	/**
	 * Make an API request to WP Lookout
	 * @param string $api_key
	 * @param array  $data
	 * @return array|WP_Error
	 */
	private function send_api_request( $api_key = '', $data = array() ) {

		$api_key = sanitize_text_field( $api_key );
		$api_url = WP_LOOKOUT_API_BASE_URL . '/import';

		if ( WP_DEBUG && WP_DEBUG_LOG ) {
			error_log( 'WP Lookout debug: sending theme and plugin data to ' . $api_url );
		}

		$api_request = wp_remote_post(
			$api_url,
			array(
				'method'  => 'POST',
				'timeout' => '20',
				'headers' => array(
					'Authorization' => "Bearer $api_key",
				),
				'body'    => $data,
			)
		);

		if ( WP_DEBUG && WP_DEBUG_LOG && is_wp_error( $api_request ) ) {
			$error_message = $api_request->get_error_message();
			error_log( "WP Lookout debug: something went wrong with the API call: $error_message" );
		}

		return $api_request;
	}
}

new Wp_Lookout_Sender();
