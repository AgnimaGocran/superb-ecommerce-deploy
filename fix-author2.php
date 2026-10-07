<?php
/**
 * Direct: author display name + set the six posts' author.
 * Run: php f18.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	require '/var/www/html/wp-load.php';
}

global $wpdb;

$wpdb->query( "UPDATE {$wpdb->users} SET display_name = 'Jane Doe' WHERE ID = 12" );
clean_user_cache( 12 );

foreach ( array(
	'how-i-started-my-ecommerce-shop',
	'quitting-my-corporate-job-for-my-startup',
	'the-most-important-skills-in-life',
	'top-5-interior-design-trends-for-2023',
	'how-to-create-a-cozy-environment',
	'10-interior-design-trends-for-cafes',
) as $slug ) {
	$wpdb->query( $wpdb->prepare(
		"UPDATE {$wpdb->posts} SET post_author = 12 WHERE post_name = %s AND post_type = 'post'",
		$slug
	) );
	clean_post_cache( get_page_by_path( $slug, OBJECT, 'post' )->ID ?? 0 );
}

$u  = get_user_by( 'id', 12 );
echo 'DISPLAY_NAME: ' . $u->display_name . "\n";
$p  = get_page_by_path( 'how-i-started-my-ecommerce-shop', OBJECT, 'post' );
echo 'POST_AUTHOR: ' . $p->post_author . "\n";
echo "F18_DONE\n";
