<?php
/**
 * Hide the archive page-title header (the demo's tag pages have no
 * "Tag: ..." heading — their template renders raw content without it).
 * Run: php f23.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	require '/var/www/html/wp-load.php';
}

$rule = "\n/* Demo parity: archive pages render without the page-title header */\n.archive .page-header { display: none; }\n";

$existing = wp_get_custom_css( 'superb-ecommerce' );
if ( false === strpos( $existing, '.archive .page-header' ) ) {
	wp_update_custom_css_post( $existing . $rule );
	echo "CSS_APPENDED\n";
} else {
	echo "ALREADY_PRESENT\n";
}

echo "F23_DONE\n";
