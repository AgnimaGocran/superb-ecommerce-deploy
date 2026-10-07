<?php
/**
 * Introspect: what is stored for the spb widget, has_blocks result, direct
 * do_blocks/do_shortcode render test.
 * Run: php f10.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	require '/var/www/html/wp-load.php';
}

$blocks = get_option( 'widget_block' );
foreach ( $blocks as $k => $w ) {
	if ( is_string( $w['content'] ?? '' ) && false !== strpos( $w['content'], '[spb-latest-posts' ) ) {
		$content = $w['content'];
		echo "KEY $k\n";
		echo "STORED: " . str_replace( "\n", '\n', $content ) . "\n";
		echo "HAS_BLOCKS: " . var_export( has_blocks( $content ), true ) . "\n";
		echo "DO_BLOCKS: " . str_replace( "\n", '\n', do_blocks( $content ) ) . "\n";
		echo "DO_SHORTCODE: " . str_replace( "\n", '\n', do_shortcode( $content ) ) . "\n";
		echo "SHORTCODE_REGISTERED: " . var_export( shortcode_exists( 'spb-latest-posts' ), true ) . "\n";
		echo "PLUGIN_ACTIVE: " . var_export( is_plugin_active( 'superb-recent-posts-with-thumbnail-images/superb-recent-posts-with-images.php' ), true ) . "\n";
	}
}

// Also: the banner file check.
echo "BANNER_FILE: " . var_export( file_exists( $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/2022/07/banner.png' ), true ) . "\n";
echo "F10_DONE\n";
