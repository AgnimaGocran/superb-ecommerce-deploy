<?php
if ( ! defined( 'ABSPATH' ) ) { require '/var/www/html/wp-load.php'; }
$p = get_page_by_path( 'classic-oak-chair', OBJECT, 'product' );
$att = get_posts( array( 'post_type' => 'attachment', 'title' => 'wc-16', 'numberposts' => 1, 'post_status' => 'any' ) );
echo 'ATT_WC16: ' . ( $att ? $att[0]->ID . ' file=[' . get_post_meta( $att[0]->ID, '_wp_attached_file', true ) . '] status=' . $att[0]->post_status : 'NONE' ) . "\n";
if ( $att ) {
	set_post_thumbnail( $p->ID, $att[0]->ID );
	echo 'IMMEDIATE_META: [' . get_post_meta( $p->ID, '_thumbnail_id', true ) . "]\n";
}
echo 'ACTIVE_PLUGINS: ' . implode( ',', get_option( 'active_plugins' ) ) . "\n";
echo "P2_DONE\n";
