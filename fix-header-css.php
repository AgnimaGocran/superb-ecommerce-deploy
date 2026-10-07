<?php
/**
 * Match the demo header geometry: 78px header, 28px logo, same paddings.
 * Run: php f28.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	require '/var/www/html/wp-load.php';
}

$rule  = "\n/* Demo header geometry */\n";
$rule .= "#masthead { height: 78px; box-sizing: border-box; }\n";
$rule .= ".logo-container { height: 78px; padding: 0 25px; box-sizing: border-box; display: flex; align-items: center; }\n";
$rule .= ".logo-container img.custom-logo { width: auto; height: 28px; }\n";
$rule .= ".header-content-container { height: 78px; }\n";
$rule .= "#primary-site-navigation .wc-nav-content { margin-top: 0; }\n";

$existing = wp_get_custom_css( 'superb-ecommerce' );
if ( false === strpos( $existing, 'Demo header geometry' ) ) {
	wp_update_custom_css_post( $existing . $rule );
	echo "CSS_APPENDED\n";
} else {
	echo "ALREADY\n";
}
echo "F28_DONE\n";
