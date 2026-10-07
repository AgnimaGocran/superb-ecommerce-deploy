<?php
/**
 * Create the demo author "Jane Doe" and assign the six posts to her.
 * Run: php f19.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	require '/var/www/html/wp-load.php';
}

$user = get_user_by( 'login', 'janedoe' );
if ( ! $user ) {
	$uid = wp_insert_user( array(
		'user_login'   => 'janedoe',
		'user_pass'    => wp_generate_password( 20 ),
		'display_name' => 'Jane Doe',
		'first_name'   => 'Jane',
		'last_name'    => 'Doe',
		'role'         => 'author',
		'user_email'   => 'jane@example.com',
	) );
	if ( is_wp_error( $uid ) ) {
		echo 'USER_FAIL ' . $uid->get_error_message() . "\n";
		exit( 1 );
	}
	echo "USER_CREATED $uid\n";
} else {
	echo "USER_EXISTS {$user->ID}\n";
}

$uid = get_user_by( 'login', 'janedoe' )->ID;

foreach ( array(
	'how-i-started-my-ecommerce-shop',
	'quitting-my-corporate-job-for-my-startup',
	'the-most-important-skills-in-life',
	'top-5-interior-design-trends-for-2023',
	'how-to-create-a-cozy-environment',
	'10-interior-design-trends-for-cafes',
) as $slug ) {
	$p = get_page_by_path( $slug, OBJECT, 'post' );
	if ( ! $p ) {
		continue;
	}
	$upd = array( 'ID' => $p->ID, 'post_author' => $uid );
	if ( '2022-01-01' === substr( $p->post_date, 0, 10 ) || '0000' === substr( $p->post_date, 0, 4 ) ) {
		continue;
	}
	wp_update_post( $upd );
	clean_post_cache( $p->ID );
	echo "AUTHOR_SET $slug -> $uid\n";
}

echo "F19_DONE\n";
