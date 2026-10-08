<?php
if ( ! defined( 'ABSPATH' ) ) { require '/var/www/html/wp-load.php'; }
global $wpdb;
$p = get_page_by_path( 'classic-oak-chair', OBJECT, 'product' );
$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key = '_thumbnail_id'", $p->ID ) );
$wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->postmeta} (post_id, meta_key, meta_value) VALUES (%d, '_thumbnail_id', %s)", $p->ID, '507' ) );
clean_post_cache( $p->ID );
$v = $wpdb->get_var( $wpdb->prepare( "SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key = '_thumbnail_id'", $p->ID ) );
echo 'WPDB_READBACK: [' . $v . "]\n";
echo 'API_READBACK: [' . get_post_meta( $p->ID, '_thumbnail_id', true ) . "]\n";
echo "PW_DONE\n";
