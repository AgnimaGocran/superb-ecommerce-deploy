<?php
/**
 * Install + activate the Superb Recent Posts plugin from the pinned zip.
 * Run: php f12.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	require '/var/www/html/wp-load.php';
}

require_once ABSPATH . 'wp-admin/includes/plugin.php';
require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
require_once ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php';
require_once ABSPATH . 'wp-admin/includes/class-wp-ajax-upgrader-skin.php';

$zip = '/var/www/html/spbr.zip';
$plugin = 'superb-recent-posts-with-thumbnail-images/superb-recent-posts-with-images.php';

if ( ! file_exists( $zip ) ) {
	$r = wp_remote_get( 'https://raw.githubusercontent.com/AgnimaGocran/superb-ecommerce-deploy/main/plugins/superb-recent-posts-with-thumbnail-images.1.5.0.zip' );
	if ( is_wp_error( $r ) || 200 !== wp_remote_retrieve_response_code( $r ) ) {
		echo "ZIP_DL_FAIL\n";
		exit( 1 );
	}
	file_put_contents( $zip, wp_remote_retrieve_body( $r ) );
}

if ( ! file_exists( WP_PLUGIN_DIR . '/superb-recent-posts-with-thumbnail-images/superb-recent-posts-with-images.php' ) ) {
	$skin     = new WP_Ajax_Upgrader_Skin();
	$upgrader = new Plugin_Upgrader( $skin );
	$result   = $upgrader->install( $zip );
	if ( is_wp_error( $result ) ) {
		echo 'INSTALL_FAIL ' . $result->get_error_message() . "\n";
		exit( 1 );
	}
	echo "INSTALLED\n";
}

if ( ! is_plugin_active( $plugin ) ) {
	$result = activate_plugin( $plugin );
	if ( is_wp_error( $result ) ) {
		echo 'ACTIVATE_FAIL ' . $result->get_error_message() . "\n";
		exit( 1 );
	}
	echo "ACTIVATED\n";
}

echo 'SHORTCODE_NOW: ' . var_export( shortcode_exists( 'spb-latest-posts' ), true ) . "\n";
echo "F12_DONE\n";
