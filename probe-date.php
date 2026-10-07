<?php
/**
 * Date format introspection.
 * Run: php f24.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	require '/var/www/html/wp-load.php';
}

$p = get_page_by_path( 'how-i-started-my-ecommerce-shop', OBJECT, 'post' );

echo 'OPTION_DATE_FORMAT: [' . get_option( 'date_format' ) . "]\n";
echo 'GET_THE_DATE: [' . get_the_date( '', $p ) . "]\n";
echo 'MYSQL2DATE: [' . mysql2date( get_option( 'date_format' ), $p->post_date ) . "]\n";
echo 'POST_DATE: [' . $p->post_date . "]\n";
echo 'F24_DONE\n';
