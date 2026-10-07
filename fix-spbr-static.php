<?php
/**
 * Replace the non-functional spbrposts slots (plugin not installed on the
 * server) with statically rendered equivalents built from the live posts,
 * mirroring the plugin's markup (spbrposts-* classes).
 * Run: php f15.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	require '/var/www/html/wp-load.php';
}

function ssf_render_spbrposts( $number ) {
	$posts = get_posts( array( 'numberposts' => $number, 'post_status' => 'publish' ) );
	if ( ! $posts ) {
		return '';
	}
	$out = '<div class="spbrposts-wrapper spbrposts-align-left spbrposts-text-align-left"><ul class="spbrposts-ul">';
	foreach ( $posts as $p ) {
		$url   = get_permalink( $p );
		$title = get_the_title( $p );
		$thumb = get_the_post_thumbnail_url( $p, array( 100, 100 ) );
		$out  .= '<li class="spbrposts-li">';
		if ( $thumb ) {
			$out .= '<a class="spbrposts-img" href="' . esc_url( $url ) . '" rel="bookmark"><img width="45" height="45" class="spbrposts-thumb" src="' . esc_url( $thumb ) . '" alt="' . esc_attr( $title ) . '"></a>';
		}
		$out .= '<h3 class="spbrposts-title"><a href="' . esc_url( $url ) . '" title="Permalink to ' . esc_attr( $title ) . '" rel="bookmark">' . esc_html( $title ) . '</a></h3>';
		$out .= '</li>';
	}
	$out .= '</ul></div>';
	return $out;
}

$sidebar_markup = ssf_render_spbrposts( 4 );
$footer_markup  = ssf_render_spbrposts( 3 );

$sw = get_option( 'sidebars_widgets' );

// Sidebar: replace the spbrposts_widget-2 slot with a widget_block.
$blocks = get_option( 'widget_block' );
$i      = 0;
while ( isset( $blocks[ $i ] ) ) {
	$i++;
}
$blocks[ $i ] = array( 'content' => $sidebar_markup );
if ( isset( $sw['sidebar-1'] ) ) {
	$sw['sidebar-1'] = array_map(
		function ( $id ) use ( $i ) {
			return ( 'spbrposts_widget-2' === $id ) ? 'block-' . $i : $id;
		},
		$sw['sidebar-1']
	);
}
update_option( 'widget_block', $blocks );
update_option( 'sidebars_widgets', $sw );

// Footer: replace the shortcode widget content.
foreach ( $blocks as $k => $w ) {
	if ( isset( $w['content'] ) && is_string( $w['content'] ) && false !== strpos( $w['content'], '[spb-latest-posts' ) ) {
		$blocks[ $k ]['content'] = $footer_markup;
		echo 'FOOTER_SC_REPLACED key=' . $k . "\n";
	}
}
update_option( 'widget_block', $blocks );

echo "SPBR_STATIC_DONE\n";
