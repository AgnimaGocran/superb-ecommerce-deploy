<?php
/**
 * Set "Krylevsky Market" (logo.png from the deploy repo) as the site logo.
 * Run: php f27.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	require '/var/www/html/wp-load.php';
}

require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

$tmp = download_url( 'https://raw.githubusercontent.com/AgnimaGocran/superb-ecommerce-deploy/main/media/logo.png' );
if ( is_wp_error( $tmp ) ) {
	echo 'DL_FAIL ' . $tmp->get_error_message() . "\n";
	exit( 1 );
}

$att = media_handle_sideload( array( 'name' => 'logo.png', 'tmp_name' => $tmp ), 0 );
if ( is_wp_error( $att ) ) {
	@unlink( $tmp );
	echo 'ATT_FAIL ' . $att->get_error_message() . "\n";
	exit( 1 );
}

set_theme_mod( 'custom_logo', $att );
echo 'LOGO_SET att=' . $att . ' url=' . wp_get_attachment_image_url( $att, 'full' ) . "\n";
echo "F27_DONE\n";
