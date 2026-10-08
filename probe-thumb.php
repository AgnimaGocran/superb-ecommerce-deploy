<?php
if ( ! defined( 'ABSPATH' ) ) { require '/var/www/html/wp-load.php'; }
$p = get_page_by_path( 'classic-oak-chair', OBJECT, 'product' );
echo 'PRODUCT_ID: ' . $p->ID . "\n";
echo 'AUTHOR: ' . $p->post_author . "\n";
echo 'THUMB_META: [' . get_post_meta( $p->ID, '_thumbnail_id', true ) . "]\n";
echo 'THUMB_URL: [' . get_the_post_thumbnail_url( $p->ID, 'woocommerce_thumbnail' ) . "]\n";
echo "PT_DONE\n";
