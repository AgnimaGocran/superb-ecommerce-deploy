<?php
/**
 * Set the blog posts author display name to "Jane Doe" (demo author).
 * Run: php f17.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	require '/var/www/html/wp-load.php';
}

$authors = array();
foreach ( array(
	'how-i-started-my-ecommerce-shop',
	'quitting-my-corporate-job-for-my-startup',
	'the-most-important-skills-in-life',
	'top-5-interior-design-trends-for-2023',
	'how-to-create-a-cozy-environment',
	'10-interior-design-trends-for-cafes',
) as $slug ) {
	$p = get_page_by_path( $slug, OBJECT, 'post' );
	if ( $p ) {
		$authors[ (int) $p->post_author ] = true;
	}
}

foreach ( array_keys( $authors ) as $uid ) {
	if ( ! $uid ) {
		continue;
	}
	$u = get_userdata( $uid );
	echo 'USER ' . $uid . ' was "' . $u->display_name . '" -> ';
	wp_update_user( array( 'ID' => $uid, 'display_name' => 'Jane Doe' ) );
	$u = get_userdata( $uid );
	echo '"' . $u->display_name . "\"\n";
}

echo "AUTHORS_DONE\n";
