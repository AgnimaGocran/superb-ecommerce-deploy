<?php
if ( ! defined( 'ABSPATH' ) ) { require '/var/www/html/wp-load.php'; }
$meta = wp_get_attachment_metadata( 507 );
echo "META_507: " . json_encode( array(
	'file' => isset( $meta['file'] ) ? $meta['file'] : null,
	'sizes' => isset( $meta['sizes'] ) ? array_keys( $meta['sizes'] ) : null,
) ) . "\n";
$file = get_attached_file( 507 );
echo "FILE: [$file] exists=" . var_export( $file && file_exists( $file ), true ) . "\n";
$src = image_downsize( 507, 'woocommerce_thumbnail' );
echo "IMAGE_DOWNSIZE: " . json_encode( is_array( $src ) ? array( $src[0], $src[1], $src[2] ) : $src ) . "\n";
$src2 = wp_get_attachment_image_src( 507, 'woocommerce_thumbnail' );
echo "IMG_SRC: " . json_encode( is_array( $src2 ) ? array( $src2[0], $src2[1], $src2[2] ) : $src2 ) . "\n";
echo "IS_ACTIVE_WC_SIDEBAR: " . var_export( is_active_sidebar( 'sidebar-wc' ), true ) . "\n";
$sw = get_option( 'sidebars_widgets' );
echo "SW_WC: " . implode( ',', $sw['sidebar-wc'] ?? array() ) . "\n";
$p = get_page_by_path( 'classic-oak-chair', OBJECT, 'product' );
echo "PROD_THUMB_META: [" . get_post_meta( $p->ID, '_thumbnail_id', true ) . "]\n";
echo "P35_DONE\n";
