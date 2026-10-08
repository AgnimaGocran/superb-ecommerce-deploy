<?php
/**
 * Attach product/category images via direct $wpdb (WP meta API writes were
 * not persisting for products in the task context).
 * Run: php f32.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	require '/var/www/html/wp-load.php';
}

global $wpdb;

$products = array(
	'classic-oak-chair'       => 'wc-16',
	'modern-wooden-chair'     => 'wc-12',
	'mounted-world-globe'     => 'wc-11',
	'plant-pots-set'          => 'wc-15',
	'simple-frame'            => 'wc-18',
	'wooden-crates'          => 'wc-img-1',
	'black-white-wax-candles' => 'wc-23',
	'plant-pillow'            => 'wc-19',
	'plant-pot'               => 'wc-24',
	'retro-alarm-clock'       => 'wc-30',
	'retro-oak-stool'         => 'wc-27',
	'small-vase'              => 'wc-26',
	'table-frame'             => 'wc-20',
	'triangle-pillow'         => 'wc-15-1',
	'wax-candle-set'          => 'wc-21',
);
$cats = array(
	'furniture'      => 'wc-12',
	'decoration'     => 'wc-img-1',
	'frames-posters' => 'wc-18',
	'pillows'        => 'wc-19',
);

// All attachments were created earlier; resolve by title.
$atts = array();
foreach ( $wpdb->get_results( "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type = 'attachment' AND post_status = 'inherit'" ) as $a ) {
	$atts[ $a->post_title ] = (int) $a->ID;
}

$set_meta = function ( $pid, $key, $value ) use ( $wpdb ) {
	$wpdb->query( $wpdb->prepare(
		"DELETE FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key = %s",
		$pid, $key
	) );
	$wpdb->query( $wpdb->prepare(
		"INSERT INTO {$wpdb->postmeta} (post_id, meta_key, meta_value) VALUES (%d, %s, %s)",
		$pid, $key, $value
	) );
	clean_post_cache( $pid );
};

foreach ( $products as $slug => $img ) {
	$p = get_page_by_path( $slug, OBJECT, 'product' );
	if ( ! $p ) { echo "MISSING $slug\n"; continue; }
	$att = isset( $atts[ $img ] ) ? $atts[ $img ] : 0;
	if ( ! $att ) { echo "NOATT $slug ($img)\n"; continue; }
	$set_meta( $p->ID, '_thumbnail_id', $att );
	echo "OK $slug att=$att\n";
}

foreach ( $cats as $slug => $img ) {
	$term = get_term_by( 'slug', $slug, 'product_cat' );
	if ( ! $term ) { echo "NOCAT $slug\n"; continue; }
	$att = isset( $atts[ $img ] ) ? $atts[ $img ] : 0;
	if ( ! $att ) { echo "NOATT cat $slug ($img)\n"; continue; }
	$wpdb->query( $wpdb->prepare(
		"DELETE FROM {$wpdb->termmeta} WHERE term_id = %d AND meta_key = 'thumbnail_id'",
		$term->term_id
	) );
	$wpdb->query( $wpdb->prepare(
		"INSERT INTO {$wpdb->termmeta} (term_id, meta_key, meta_value) VALUES (%d, 'thumbnail_id', %s)",
		$term->term_id, $att
	) );
	echo "CAT $slug att=$att\n";
}

echo "F32_DONE\n";
