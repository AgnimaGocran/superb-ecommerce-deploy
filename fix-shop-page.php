<?php
/**
 * Bind the Shop page to WooCommerce (shop archive) like the demo:
 * woocommerce_shop_page_id, empty page content, 3-column catalog.
 * Run: php f36.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	require '/var/www/html/wp-load.php';
}

$shop = get_page_by_path( 'shop', OBJECT, 'page' );
if ( ! $shop ) {
	echo "SHOP_PAGE_MISSING\n";
	exit( 1 );
}

$old = get_option( 'woocommerce_shop_page_id' );
update_option( 'woocommerce_shop_page_id', $shop->ID );

// Clear the page content (the demo renders the raw product archive).
wp_update_post( array( 'ID' => $shop->ID, 'post_content' => '' ) );

// Demo catalog grid: 3 columns / 3 rows per page.
update_option( 'woocommerce_catalog_columns', 3 );
update_option( 'woocommerce_catalog_rows', 3 );

wc_delete_product_transients();
wp_cache_flush();

echo 'SHOP_PAGE_ID: ' . $old . ' -> ' . $shop->ID . "\n";
echo "F36_DONE\n";
