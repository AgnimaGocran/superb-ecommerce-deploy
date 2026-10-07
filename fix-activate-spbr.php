<?php
/**
 * Activate the Superb Recent Posts plugin (shortcode + widget).
 * Run: php f11.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	require '/var/www/html/wp-load.php';
}

require_once ABSPATH . 'wp-admin/includes/plugin.php';

$plugin = 'superb-recent-posts-with-thumbnail-images/superb-recent-posts-with-images.php';

if ( is_plugin_active( $plugin ) ) {
	echo "ALREADY_ACTIVE\n";
} else {
	$result = activate_plugin( $plugin );
	if ( is_wp_error( $result ) ) {
		echo 'ACTIVATE_FAIL ' . $result->get_error_message() . "\n";
	} else {
		echo "ACTIVATED\n";
	}
}

echo 'SHORTCODE_NOW: ' . var_export( shortcode_exists( 'spb-latest-posts' ), true ) . "\n";
echo "F11_DONE\n";
