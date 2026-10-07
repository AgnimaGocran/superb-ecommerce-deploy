<?php
/**
 * Delete stale spbrposts block copies not assigned to any sidebar.
 * Run: php f22.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	require '/var/www/html/wp-load.php';
}

$sw     = get_option( 'sidebars_widgets' );
$blocks = get_option( 'widget_block' );

$assigned = array();
foreach ( $sw as $ids ) {
	if ( is_array( $ids ) ) {
		foreach ( $ids as $id ) {
			$assigned[ $id ] = true;
		}
	}
}

$removed = array();
foreach ( $blocks as $k => $w ) {
	if ( empty( $w['content'] ) || ! is_string( $w['content'] ) ) {
		continue;
	}
	$id = 'block-' . $k;
	if ( empty( $assigned[ $id ] ) && false !== strpos( $w['content'], 'spbrposts-wrapper' ) ) {
		unset( $blocks[ $k ] );
		$removed[] = $id;
	}
}
update_option( 'widget_block', $blocks );

echo 'REMOVED ' . implode( ', ', $removed ) . "\n";
echo "F22_DONE\n";
