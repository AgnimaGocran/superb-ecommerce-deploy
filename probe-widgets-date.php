<?php
/**
 * Show the stored spbrposts widget contents (server-side truth).
 * Run: php f25.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	require '/var/www/html/wp-load.php';
}

$sw     = get_option( 'sidebars_widgets' );
$blocks = get_option( 'widget_block' );

echo "SIDEBAR-1: " . implode( ', ', $sw['sidebar-1'] ?? array() ) . "\n";
foreach ( $sw as $area => $ids ) {
	foreach ( $ids as $id ) {
		if ( 0 !== strpos( $id, 'block-' ) ) {
			continue;
		}
		$k = (int) substr( $id, 6 );
		if ( isset( $blocks[ $k ]['content'] ) && false !== strpos( $blocks[ $k ]['content'], 'spbrposts-wrapper' ) ) {
			$dates = array();
			if ( preg_match_all( '/spbrposts-time[^>]*>\s*<a[^>]*>\s*([^<]+)</', $blocks[ $k ]['content'], $m ) ) {
				$dates = $m[1];
			}
			echo "WIDGET $id (area $area): dates = " . implode( ' | ', $dates ) . "\n";
		}
	}
}

echo "F25_DONE\n";
