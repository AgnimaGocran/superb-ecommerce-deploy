<?php
/**
 * Switch the site locale to en_US so front-end strings (archive headings,
 * meta, widget defaults) match the English official demo.
 * Run: php f4.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	require '/var/www/html/wp-load.php';
}

update_option( 'locale', 'en_US' );
echo 'LOCALE ' . get_locale() . "\n";
