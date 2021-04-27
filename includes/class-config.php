<?php

/**
 * Class Wp_Lookout_Config - handles the wp-admin configuration screens for API key management.
 */

class Wp_Lookout_Config {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'wpl_add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'wpl_settings_init' ) );

	}

	public function wpl_add_admin_menu() {
		add_options_page( 'WP Lookout', 'WP Lookout', 'manage_options', 'wp_lookout', array( $this, 'wpl_options_page' ) );
	}

	public function wpl_settings_init() {

		register_setting(
			'wplPluginPage',
			WP_LOOKOUT_SETTINGS_OPTION,
			array(
				'sanitize_callback' => array( $this, 'wpl_validate_api_input' ),
			)
		);

		add_settings_section(
			'wplPluginPage_config_section',
			'',
			array( $this, 'wpl_settings_config_section_callback' ),
			'wplPluginPage'
		);

		add_settings_field(
			'wp_lookout_api_key',
			__( 'WP Lookout API Token', 'wp-lookout' ),
			array( $this, 'wpl_text_field_render' ),
			'wplPluginPage',
			'wplPluginPage_config_section',
			array(
				'slug' => 'wp_lookout_api_key',
			)
		);
	}

	/**
	 * Sanitize and validate form input.
	 * @param $input
	 * @return mixed
	 */
	public function wpl_validate_api_input( $input ) {
		// Make sure we're dealing with a regular text field.
		$input['wp_lookout_api_key'] = sanitize_text_field( $input['wp_lookout_api_key'] );

		// Check the API key against the WP Lookout key check endpoint, to confirm validity.
		if ( ! empty( $input['wp_lookout_api_key'] ) ) {
			$key_check_request = wp_remote_post(
				WP_LOOKOUT_API_BASE_URL . '/key-check',
				array(
					'body' => array(
						'token' => $input['wp_lookout_api_key'],
					),
				)
			);

			// If the check HTTP request couldn't complete or the API endpoint didn't return success, add error message.
			if ( is_wp_error( $key_check_request ) || 200 !== wp_remote_retrieve_response_code( $key_check_request ) ) {
				add_settings_error(
					WP_LOOKOUT_SETTINGS_OPTION,
					'wp_lookout_setting_message',
					__( 'The WP Lookout API token could not be verified. Please check and try again.' ),
					'error'
				);

				// Return the previously saved values.
				return get_option( WP_LOOKOUT_SETTINGS_OPTION );
			}
		}

		// Return the new values as validated.
		return $input;
	}

	public function wpl_text_field_render( $args ) {

		$options = get_option( WP_LOOKOUT_SETTINGS_OPTION );

		printf(
			'<input type="password" autocomplete="off" name="wp_lookout_settings[%s]" value="%s" size="40">',
			esc_attr( $args['slug'] ),
			! empty( $options[ $args['slug'] ] ) ? esc_attr( $options[ $args['slug'] ] ) : ''
		);

	}

	public function wpl_settings_config_section_callback() {

		?>
		<div class="wplookout-settings-notices">
			<p><?php _e( 'Welcome to WP Lookout! To get started, follow these steps:', 'wp-lookout' ); ?></p>
			<ol>
				<li><a href="https://app.wplookout.com/register" target="_blank"><?php _e( 'Create a free WP Lookout account.', 'wp-lookout' ); ?></a></li>
				<li><a href="https://app.wplookout.com/settings#/api" target="_blank"><?php _e( 'Create an API token.', 'wp-lookout' ); ?></a></li>
				<li><?php _e( 'Enter the API token in the field below and click "Save Changes."', 'wp-lookout' ); ?></li>
			</ol>
		</div>
		<?php
	}

	public function wpl_options_page() {

		?>
		<form action='options.php' method='post'>

			<h2><?php echo esc_html_x( 'WP Lookout', 'wp-lookout' ); ?></h2>

			<?php
			settings_fields( 'wplPluginPage' );
			do_settings_sections( 'wplPluginPage' );
			submit_button();
			?>

		</form>

		<div class="wplookout-settings-notices">
			<p>On a regular basis this plugin will send several pieces of information to your WP Lookout account:</p>
			<ul style="list-style-type: disc; margin-inline-start: 20px;">
				<li>The URL and core version for this WordPress site</li>
				<li>A list of the plugins installed on this site, with current version</li>
				<li>A list of the themes installed on this site, with current version</li>
			</ul>
			<p>No other part of your site configuration or content is transmitted or stored.
			You can disable this connection at any time by removing the API token from the field above,
			or by disabling or deleting this plugin from your WordPress site.</p>
			<p>By enabling a connection between your site and WP Lookout, you agree
				to the <a href="https://wplookout.com/terms-and-conditions/" target="_blank">WP Lookout terms of service</a>.
			</p>
		</div>
		<?php

	}


}

new Wp_Lookout_Config();
