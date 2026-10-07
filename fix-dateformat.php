<?php
/**
 * Match the demo date format.
 * Run: php f21.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	require '/var/www/html/wp-load.php';
}

update_option( 'date_format', 'F j, Y' );
update_option( 'time_format', 'g:i a' );
echo 'DATE_FORMAT ' . get_option( 'date_format' ) . "\n";
echo "F21_DONE\n";
