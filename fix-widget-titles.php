<?php
/**
 * Replace the Russian default widget titles (baked into the widget_block
 * option during the ru_RU install) with the English demo titles.
 * Run: php f6.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	require '/var/www/html/wp-load.php';
}

$map = array(
	'Свежие записи'      => 'Recent Posts',
	'Свежие комментарии' => 'Recent Comments',
	'Архивы'             => 'Archives',
	'Рубрики'            => 'Categories',
	'Поиск'              => 'Search',
);

$changed = 0;

$blocks = get_option( 'widget_block' );
if ( is_array( $blocks ) ) {
	array_walk_recursive( $blocks, function ( &$value ) use ( $map, &$changed ) {
		if ( is_string( $value ) ) {
			$new = strtr( $value, $map );
			if ( $new !== $value ) {
				$value = $new;
				$changed++;
			}
		}
	} );
	update_option( 'widget_block', $blocks );
}

// Legacy (non-block) widgets too.
foreach ( array( 'widget_recent-posts', 'widget_recent-comments', 'widget_archives', 'widget_categories', 'widget_search' ) as $opt ) {
	$w = get_option( $opt );
	if ( is_array( $w ) ) {
		array_walk_recursive( $w, function ( &$value ) use ( $map, &$changed ) {
			if ( is_string( $value ) ) {
				$new = strtr( $value, $map );
				if ( $new !== $value ) {
					$value = $new;
					$changed++;
				}
			}
		} );
		update_option( $opt, $w );
	}
}

echo 'CHANGED ' . $changed . "\n";
