<?php
/**
 * Rewrite the EXISTING spbrposts widget blocks (39 sidebar, 36 footer)
 * with freshly rendered, current-format markup.
 * Run: php f26.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	require '/var/www/html/wp-load.php';
}

function f26_render( $number ) {
	$posts = get_posts( array( 'numberposts' => $number, 'post_status' => 'publish' ) );
	$out   = '<div class="spbrposts-wrapper spbrposts-align-left spbrposts-text-align-left"><ul class="spbrposts-ul">';
	foreach ( $posts as $p ) {
		$url   = get_permalink( $p );
		$title = get_the_title( $p );
		$thumb = get_the_post_thumbnail_url( $p, array( 100, 100 ) );
		$out  .= '<li class="spbrposts-li">';
		if ( $thumb ) {
			$out .= '<a class="spbrposts-img" href="' . esc_url( $url ) . '" rel="bookmark"><img width="45" height="45" class="spbrposts-thumb" src="' . esc_url( $thumb ) . '" alt="' . esc_attr( $title ) . '"></a>';
		}
		$out .= '<h3 class="spbrposts-title"><a href="' . esc_url( $url ) . '" title="Permalink to ' . esc_attr( $title ) . '" rel="bookmark">' . esc_html( $title ) . '</a></h3>';
		$out .= '<span class="spbrposts-meta-wrapper"><time class="spbrposts-time published"><a href="' . esc_url( $url ) . '" rel="bookmark">' . get_the_date( 'F j, Y', $p ) . '</a></time></span>';
		$out .= '</li>';
	}
	$out .= '</ul></div>';
	return $out;
}

$blocks = get_option( 'widget_block' );

$blocks[39]['content'] = f26_render( 4 );
$blocks[36]['content'] = f26_render( 3 );

update_option( 'widget_block', $blocks );

echo '39: ' . ( false !== strpos( $blocks[39]['content'], 'July 4, 2022' ) ? 'EN_OK' : 'BAD' ) . "\n";
echo "F26_DONE\n";
