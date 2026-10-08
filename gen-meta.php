<?php
if ( ! defined( 'ABSPATH' ) ) { require '/var/www/html/wp-load.php'; }
require_once ABSPATH . 'wp-admin/includes/image.php';

$fixed = 0; $ok = 0;
$atts = $wpdb->get_results( "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'attachment' AND post_status = 'inherit'" );
foreach ( $atts as $a ) {
	$file = get_attached_file( $a->ID );
	if ( ! $file || ! file_exists( $file ) ) { continue; }
	$meta = wp_get_attachment_metadata( $a->ID );
	if ( $meta && ! empty( $meta['sizes'] ) ) { $ok++; continue; }
	$new = wp_generate_attachment_metadata( $a->ID, $file );
	if ( $new ) { wp_update_attachment_metadata( $a->ID, $new ); $fixed++; echo 'META ' . $a->ID . ' = ' . basename( $file ) . "\n"; }
	else { echo 'META_FAIL ' . $a->ID . ' ' . basename( $file ) . "\n"; }
}
echo "GENERATED $fixed, ALREADY_OK $ok\n";
echo "F35_DONE\n";
