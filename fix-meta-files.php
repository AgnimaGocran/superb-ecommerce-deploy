<?php
/**
 * Regenerate attachment metadata until all size files exist on disk.
 * Run: php f37.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	require '/var/www/html/wp-load.php';
}

require_once ABSPATH . 'wp-admin/includes/image.php';

$report = array();
$atts   = $wpdb->get_results( "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type = 'attachment' AND post_status = 'inherit'" );

foreach ( $atts as $a ) {
	$file = get_attached_file( $a->ID );
	if ( ! $file || ! file_exists( $file ) ) {
		continue;
	}
	$meta = wp_get_attachment_metadata( $a->ID );
	$dir  = dirname( $file );

	$missing = array();
	if ( $meta && ! empty( $meta['sizes'] ) ) {
		foreach ( $meta['sizes'] as $s ) {
			if ( empty( $s['file'] ) ) { continue; }
			if ( ! file_exists( $dir . '/' . $s['file'] ) ) { $missing[] = $s['file']; }
		}
	}

	if ( $meta && empty( $missing ) ) {
		$report[] = 'OK ' . $a->ID . ' ' . basename( $file );
		continue;
	}

	$new = wp_generate_attachment_metadata( $a->ID, $file );
	if ( is_wp_error( $new ) || empty( $new ) ) {
		$report[] = 'GEN_FAIL ' . $a->ID . ' ' . basename( $file );
		continue;
	}
	wp_update_attachment_metadata( $a->ID, $new );

	$still = 0;
	foreach ( $new['sizes'] as $s ) {
		if ( ! empty( $s['file'] ) && ! file_exists( $dir . '/' . $s['file'] ) ) { $still++; }
	}
	$report[] = 'REGEN ' . $a->ID . ' ' . basename( $file ) . ' missing_after=' . $still;
}

echo implode( "\n", $report ) . "\n";
echo "F37_DONE\n";
