<?php
/**
 * Un-squash the logo: the demo rule capped width at 105px (their "Superb."
 * logo is short); "Krylevsky Market" needs ~193px at the same 28px height.
 * Run: php f30.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	require '/var/www/html/wp-load.php';
}

$css = wp_get_custom_css( 'superb-ecommerce' );

$css = str_replace(
	'img.custom-logo { height: auto; max-width: 105px; }',
	'img.custom-logo { height: 28px; width: auto; max-width: none; }',
	$css
);

wp_update_custom_css_post( $css );

echo ( false !== strpos( $css, 'max-width: none' ) ) ? "LOGO_FIXED\n" : "RULE_NOT_FOUND\n";
echo "F30_DONE\n";
