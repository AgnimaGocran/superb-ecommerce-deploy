<?php
/**
 * Attach demo product images (thumbs + galleries) and category tiles.
 * Run: php f31.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	require '/var/www/html/wp-load.php';
}

require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

const GH = 'https://raw.githubusercontent.com/AgnimaGocran/superb-ecommerce-deploy/main/media/2022/07/';

function f31_att( $name ) {
	static $cache = array();
	$name = trim( $name );
	if ( isset( $cache[ $name ] ) ) return $cache[ $name ];
	$existing = get_posts( array( 'post_type' => 'attachment', 'title' => $name, 'numberposts' => 1, 'post_status' => 'any' ) );
	if ( $existing ) { $cache[ $name ] = (int) $existing[0]->ID; return $cache[ $name ]; }
	$tmp = download_url( GH . $name . '.png' );
	if ( is_wp_error( $tmp ) ) { echo 'DL_FAIL ' . $name . ' ' . $tmp->get_error_message() . "\n"; $cache[ $name ] = 0; return 0; }
	$att = media_handle_sideload( array( 'name' => $name . '.png', 'tmp_name' => $tmp ), 0 );
	if ( is_wp_error( $att ) ) { @unlink( $tmp ); echo 'ATT_FAIL ' . $name . ' ' . $att->get_error_message() . "\n"; $cache[ $name ] = 0; return 0; }
	echo 'ATT ' . $name . ' = ' . $att . "\n";
	$cache[ $name ] = (int) $att;
	return (int) $att;
}

const PRODUCTS = [{"slug":"classic-oak-chair","thumb":"wc-16","images":["wc-16"]},{"slug":"modern-wooden-chair","thumb":"wc-12","images":["wc-12"]},{"slug":"mounted-world-globe","thumb":"wc-11","images":["wc-11"]},{"slug":"plant-pots-set","thumb":"wc-15","images":["wc-15"]},{"slug":"simple-frame","thumb":"wc-18","images":["wc-18"]},{"slug":"wooden-crates","thumb":null,"images":[]},{"slug":"black-white-wax-candles","thumb":"wc-23","images":["wc-23"]},{"slug":"plant-pillow","thumb":"wc-19","images":["wc-19"]},{"slug":"plant-&amp;-pot","thumb":"wc-24","images":["wc-24"]},{"slug":"retro-alarm-clock","thumb":"wc-30","images":["wc-30"]},{"slug":"retro-oak-stool","thumb":"wc-27","images":["wc-27"]},{"slug":"small-vase","thumb":"wc-26","images":["wc-26"]},{"slug":"table-frame","thumb":"wc-20","images":["wc-20"]},{"slug":"triangle-pillow","thumb":"wc-15-1","images":["wc-15-1"]},{"slug":"wax-candle-set","thumb":"wc-21","images":["wc-21"]}];
const CATS = [{"slug":"furniture","img":"wc-12"},{"slug":"decoration","img":"wc-img-1"},{"slug":"frames-posters","img":"wc-18"},{"slug":"pillows","img":"wc-17"}];

foreach ( PRODUCTS as $p ) {
	$post = get_page_by_path( $p['slug'], OBJECT, 'product' );
	if ( ! $post ) { echo 'PRODUCT_MISSING ' . $p['slug'] . "\n"; continue; }
	$thumb_id = f31_att( $p['thumb'] );
	if ( $thumb_id ) set_post_thumbnail( $post->ID, $thumb_id );
	$gallery = array();
	foreach ( $p['images'] as $img ) {
		$id = f31_att( $img );
		if ( $id && $id != $thumb_id ) $gallery[] = $id;
	}
	if ( $gallery ) update_post_meta( $post->ID, '_product_image_gallery', implode( ',', $gallery ) );
	echo 'PRODUCT_OK ' . $p['slug'] . "\n";
}

foreach ( CATS as $c ) {
	$term = get_term_by( 'slug', $c['slug'], 'product_cat' );
	if ( ! $term ) { echo 'CAT_MISSING ' . $c['slug'] . "\n"; continue; }
	$id = f31_att( $c['img'] );
	if ( $id ) update_term_meta( $term->term_id, 'thumbnail_id', $id );
	echo 'CAT_OK ' . $c['slug'] . "\n";
}

echo "SHOP_DONE\n";