<?php
/**
 * Final demo-parity pass:
 *  - theme mods: two-column grid, excerpt + Continue reading
 *  - post dates (demo order) + author display name
 *  - Popular Posts widget dates + rebuilt static spbrposts blocks
 *  - header menu: demo structure (dropdowns, Layout labels unswapped)
 * Run: php f16.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	require '/var/www/html/wp-load.php';
}

// 1. Theme mods.
set_theme_mod( 'blogfeed_onecolumn', '1' );
remove_theme_mod( 'show_except_or_full' );
echo "THEME_MODS_OK\n";

// 2. Post dates (demo order) + author display name.
$dates = array(
	'how-i-started-my-ecommerce-shop'          => '2022-07-04 10:00:00',
	'quitting-my-corporate-job-for-my-startup' => '2022-07-04 09:00:00',
	'the-most-important-skills-in-life'        => '2022-07-01 10:00:00',
	'top-5-interior-design-trends-for-2023'    => '2022-06-30 10:00:00',
	'how-to-create-a-cozy-environment'         => '2022-06-01 10:00:00',
	'10-interior-design-trends-for-cafes'      => '2022-05-01 10:00:00',
);
$author_id = 0;
foreach ( $dates as $slug => $date ) {
	$post = get_page_by_path( $slug, OBJECT, 'post' );
	if ( ! $post ) {
		echo "POST_MISSING $slug\n";
		continue;
	}
	$author_id = $post->post_author;
	wp_update_post( array(
		'ID'            => $post->ID,
		'post_date'     => $date,
		'post_date_gmt' => get_gmt_from_date( $date ),
	) );
	clean_post_cache( $post->ID );
}
if ( $author_id ) {
	$user = get_userdata( $author_id );
	if ( $user && 'Jane Doe' !== $user->display_name ) {
		wp_update_user( array( 'ID' => $author_id, 'display_name' => 'Jane Doe' ) );
	}
}
echo "DATES_OK\n";

// 3. Popular Posts widget: show dates.
$spbr = get_option( 'widget_spbrposts_widget' );
if ( isset( $spbr[2] ) ) {
	$spbr[2]['displaydate'] = 1;
	update_option( 'widget_spbrposts_widget', $spbr );
}

// 4. Rebuild static spbrposts blocks (sidebar = 4 posts, footer = 3 posts).
function f16_render_spbrposts( $number ) {
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
		$out .= '<span class="spbrposts-meta-wrapper"><time class="spbrposts-time published"><a href="' . esc_url( $url ) . '" rel="bookmark">' . get_the_date( '', $p ) . '</a></time></span>';
		$out .= '</li>';
	}
	$out .= '</ul></div>';
	return $out;
}

$sw     = get_option( 'sidebars_widgets' );
$blocks = get_option( 'widget_block' );
$sidebar_blocks = isset( $sw['sidebar-1'] ) ? $sw['sidebar-1'] : array();
foreach ( $blocks as $k => $w ) {
	if ( empty( $w['content'] ) || ! is_string( $w['content'] ) || false === strpos( $w['content'], 'spbrposts-wrapper' ) ) {
		continue;
	}
	$is_sidebar = in_array( 'block-' . $k, $sidebar_blocks, true );
	$blocks[ $k ]['content'] = f16_render_spbrposts( $is_sidebar ? 4 : 3 );
}
update_option( 'widget_block', $blocks );
echo "SPBR_REBUILT\n";

// 5. Header menu: demo structure.
// Existing items (from the live markup): 568 Home, 569 Shop, 570 My account,
// 571 Cart, 572 Checkout, 573 Blog, 574 "Layout 1" (->/tag/layout-2),
// 575 "Layout 2" (->/tag/layout-1), 576 Our Team, 577 About Us, 578 Contact Us.
$menu_updates = array(
	568 => array( 'order' => 1,  'parent' => 0 ),
	569 => array( 'order' => 2,  'parent' => 0 ),
	570 => array( 'order' => 3,  'parent' => 569 ),
	571 => array( 'order' => 4,  'parent' => 569 ),
	572 => array( 'order' => 5,  'parent' => 569 ),
	573 => array( 'order' => 6,  'parent' => 0 ),
	575 => array( 'order' => 7,  'parent' => 573, 'title' => 'Blog', 'url' => '/blog/' ),
	574 => array( 'order' => 8,  'parent' => 573, 'title' => 'Layout 1', 'url' => '/tag/layout-1/' ),
	// new "Layout 2" child is created below (order 9)
	576 => array( 'order' => 10, 'parent' => 0 ),
	577 => array( 'order' => 11, 'parent' => 0 ),
	578 => array( 'order' => 12, 'parent' => 0 ),
);

foreach ( $menu_updates as $id => $u ) {
	$update = array( 'ID' => $id, 'menu_order' => $u['order'], 'post_parent' => $u['parent'] );
	if ( ! empty( $u['title'] ) ) {
		$update['post_title'] = $u['title'];
	}
	wp_update_post( $update );
	update_post_meta( $id, '_menu_item_menu_item_parent', $u['parent'] );
	if ( ! empty( $u['url'] ) ) {
		update_post_meta( $id, '_menu_item_url', $u['url'] );
	}
	clean_post_cache( $id );
}

// New "Layout 2" child under Blog (573).
$layout2 = wp_insert_post( array(
	'post_title'  => 'Layout 2',
	'post_status' => 'publish',
	'post_type'   => 'nav_menu_item',
	'menu_order'  => 9,
) );
if ( $layout2 && ! is_wp_error( $layout2 ) ) {
	update_post_meta( $layout2, '_menu_item_type', 'custom' );
	update_post_meta( $layout2, '_menu_item_object', 'custom' );
	update_post_meta( $layout2, '_menu_item_url', '/tag/layout-2/' );
	update_post_meta( $layout2, '_menu_item_menu_item_parent', 573 );
	$term = get_term_by( 'slug', 'demo-menu', 'nav_menu' );
	if ( $term ) {
		wp_set_object_terms( $layout2, (int) $term->term_id, 'nav_menu' );
	}
	echo "MENU_CHILD_CREATED $layout2\n";
} else {
	echo "MENU_CHILD_FAIL\n";
}

// Flush caches that affect menus.
wp_cache_flush();
echo "MENU_OK\n";
