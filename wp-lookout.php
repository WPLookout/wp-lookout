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

// When the plugin's settings are changed, try running the importer just once.
add_action( 'update_option_wp_lookout_settings', function() {
	$sender = new Wp_Lookout_Sender();
	$result = $sender->wp_lookout_send_data();
});

// When the plugin is deactivated, remove the API key storage and scheduled import job.
function wp_lookout_deactivate() {
	delete_option( 'wp_lookout_settings' );
	wp_clear_scheduled_hook( 'wp_lookout_sender_event_hook' );
}
register_deactivation_hook( __FILE__, 'wp_lookout_deactivate' );
