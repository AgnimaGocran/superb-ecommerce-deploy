<?php
if ( ! defined( 'ABSPATH' ) ) { require '/var/www/html/wp-load.php'; }
$blocks = get_option( 'widget_block' );
foreach ( $blocks as $k => $w ) {
	if ( isset( $w['content'] ) && is_string( $w['content'] ) && false !== strpos( $w['content'], 'spbrposts-wrapper' ) ) {
		echo 'block-' . $k . ': li=' . substr_count( $w['content'], 'spbrposts-li' ) . ' en_dates=' . substr_count( $w['content'], 'July 4, 2022' ) . "\n";
	}
}
$sw = get_option( 'sidebars_widgets' );
echo 'SIDEBAR-1: ' . implode( ',', $sw['sidebar-1'] ) . "\n";
echo 'WC: ' . implode( ',', $sw['sidebar-wc'] ?? array() ) . "\n";
echo "F40_DONE\n";
