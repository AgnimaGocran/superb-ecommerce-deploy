<?php
/**
 * Populate the WooCommerce shop sidebar (sidebar-wc) like the demo:
 * Popular Categories, Search, Price filter, Follow Us, Attribute filter.
 * Run: php f33.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	require '/var/www/html/wp-load.php';
}

$blocks = get_option( 'widget_block' );
$add    = function ( $content ) use ( &$blocks ) {
	$i = 0;
	while ( isset( $blocks[ $i ] ) ) { $i++; }
	$blocks[ $i ] = array( 'content' => $content );
	return 'block-' . $i;
};

// Reuse the Follow Us social markup from the blog sidebar (block-27, spbsm SVGs).
$blocks = get_option( 'widget_block' );
$social = '';
foreach ( $blocks as $w ) {
	if ( isset( $w['content'] ) && is_string( $w['content'] ) && false !== strpos( $w['content'], 'spbsm-followbuttons-output-wrapper' ) ) {
		$social = $w['content'];
		break;
	}
}

$sidebar_wc = array(
	$add( '<!-- wp:heading --><h2 class="wp-block-heading">Popular Categories</h2><!-- /wp:heading -->' ),
	$add( '<!-- wp:woocommerce/product-categories {"hasCount":true,"hasImage":true} /-->' ),
	$add( ssf_spacer( 25 ) ),
	$add( '<!-- wp:heading --><h2 class="wp-block-heading">Looking for something?</h2><!-- /wp:heading -->' ),
	$add( ssf_spacer( 8 ) ),
	$add( '<!-- wp:woocommerce/product-search /-->' ),
	$add( ssf_spacer( 25 ) ),
	$add( '<!-- wp:woocommerce/price-filter {"showInputFields":true,"showFilterButton":false,"heading":"Filter by price","headingLevel":3} /-->' ),
	$add( ssf_spacer( 20 ) ),
	$add( $social ),
	$add( '<!-- wp:woocommerce/attribute-filter {"showCount":true,"heading":"Filter by attribute","headingLevel":3} /-->' ),
);

function ssf_spacer( $h ) {
	return '<div style="height:' . $h . 'px" aria-hidden="true" class="wp-block-spacer"></div>';
}

$sw = get_option( 'sidebars_widgets' );
$sw['sidebar-wc'] = $sidebar_wc;
unset( $sw['array_version'] );
update_option( 'sidebars_widgets', $sw );

echo "SHOP_SIDEBAR_OK\n";
echo "F33_DONE\n";
