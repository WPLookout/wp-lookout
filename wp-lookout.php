<?php
/**
 * Plugin Name:     WP Lookout
 * Plugin URI:      https://wplookout.com/
 * Description:     Tracks changes and updates to the plugins and themes you depend on.
 * Author:          Chris Hardie
 * Author URI:      https://chrishardie.com/
 * Text Domain:     wp-lookout
 * Domain Path:     /languages
 * Version:         1.0.0
 *
 * @package         Wp_Lookout
 */

// The API endpoint where the plugin sends plugin and theme data.
define( 'WP_LOOKOUT_IMPORT_API_URL', 'https://app.wplookout.com/api/import' );

// Configuration / Settings
require_once plugin_dir_path( __FILE__ ) . 'includes/class-config.php';
// Sending cron event
require_once plugin_dir_path( __FILE__ ) . 'includes/class-sender.php';

// Add a link to the Settings page to the plugin's entry in the site's plugin list
function wpl_filter_plugin_action_links( array $actions ) {
	return array_merge( array(
		'settings' => sprintf(
			'<a href="%s">%s</a>',
			admin_url( 'options-general.php?page=wp_lookout' ),
			esc_html__( 'Settings', 'wp-lookout' )
		)
	), $actions );
}
add_filter( 'plugin_action_links_wp-lookout/wp-lookout.php', 'wpl_filter_plugin_action_links' );

// When the plugin's settings are created or changed, try running the importer just once.
function wpl_updated_option_action() {
	$sender = new Wp_Lookout_Sender();
	$result = $sender->wp_lookout_send_data();
}
add_action( 'add_option_wp_lookout_settings', 'wpl_updated_option_action', 10 );
add_action( 'update_option_wp_lookout_settings', 'wpl_updated_option_action', 10 );

/**
 * Runs only when the plugin is activated.
 * @since 0.1.0
 */
function wp_lookout_activate() {
	// Create transient to indicate admin notice should be displayed
	set_transient( 'wpl_activate_admin_notice_display', true, 5 );
}
register_activation_hook( __FILE__, 'wp_lookout_activate' );

/**
 * Admin Notice on Activation.
 * @since 0.1.0
 */
function wpl_activation_admin_notice() {

	// Check transient, if available display notice
	if ( get_transient( 'wpl_activate_admin_notice_display' ) && is_admin() && current_user_can( 'activate_plugins' ) ) {
		?>
		<div class="updated notice is-dismissible">
			<p><?php echo __( 'WP Lookout is almost ready.', 'wp-lookout' ); ?>
				<strong><?php
					echo sprintf( '<a href="%s">%s</a>',
						admin_url( 'options-general.php?page=wp_lookout' ),
						__( 'Configure WP Lookout.', 'wp-lookout' )
						)
				?></strong>
			</p>
		</div>
		<?php
		// Delete transient, only display this notice once per activation.
		delete_transient( 'wpl_activate_admin_notice_display' );
	}
}
add_action( 'admin_notices', 'wpl_activation_admin_notice' );

// When the plugin is deactivated, remove the API key storage and scheduled import job.
function wp_lookout_deactivate() {
	delete_option( 'wp_lookout_settings' );
	wp_clear_scheduled_hook( 'wp_lookout_sender_event_hook' );
}
register_deactivation_hook( __FILE__, 'wp_lookout_deactivate' );
