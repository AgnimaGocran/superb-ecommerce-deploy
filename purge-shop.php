<?php
if ( ! defined( 'ABSPATH' ) ) { require '/var/www/html/wp-load.php'; }
foreach ( array( 'classic-oak-chair', 'modern-wooden-chair', 'mounted-world-globe', 'plant-pots-set', 'simple-frame', 'wooden-crates', 'black-white-wax-candles', 'plant-pillow', 'plant-pot', 'retro-alarm-clock', 'retro-oak-stool', 'small-vase', 'table-frame', 'triangle-pillow', 'wax-candle-set' ) as $slug ) {
	$p = get_page_by_path( $slug, OBJECT, 'product' );
	if ( $p ) {
		wp_update_post( array( 'ID' => $p->ID, 'post_modified' => current_time( 'mysql' ), 'post_modified_gmt' => current_time( 'mysql', 1 ) ) );
		wc_delete_product_transients( $p->ID );
	}
}
$shop_id = (int) get_option( 'woocommerce_shop_page_id' );
if ( $shop_id ) {
	wp_update_post( array( 'ID' => $shop_id, 'post_modified' => current_time( 'mysql' ), 'post_modified_gmt' => current_time( 'mysql', 1 ) ) );
}
if ( function_exists( 'wc_delete_product_transients' ) ) { wc_delete_product_transients(); }
wp_cache_flush();
echo "PURGED\n";
