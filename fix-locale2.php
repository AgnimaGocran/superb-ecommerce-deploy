<?php
/**
 * Force the site locale to en_US (direct options update; some filters keep
 * get_locale() on ru_RU when only update_option('locale') is used).
 * Run: php f5.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	require '/var/www/html/wp-load.php';
}

global $wpdb;

update_option( 'locale', 'en_US' );
update_option( 'WPLANG', 'en_US' );

$wpdb->query(
	"UPDATE {$wpdb->options} SET option_value = 'en_US' WHERE option_name IN ('locale', 'WPLANG')"
);

wp_cache_flush();

echo 'OPT_LOCALE ' . get_option( 'locale' ) . "\n";
echo 'OPT_WPLANG ' . get_option( 'WPLANG' ) . "\n";
echo 'GET_LOCALE ' . get_locale() . "\n";
