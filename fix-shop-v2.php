<?php
if ( ! defined( 'ABSPATH' ) ) { require '/var/www/html/wp-load.php'; }
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/image.php';
function f31_att( $name ) {
  static $cache = array();
  $existing = get_posts( array( 'post_type' => 'attachment', 'title' => $name, 'numberposts' => 1, 'post_status' => 'any' ) );
  if ( $existing ) return (int) $existing[0]->ID;
  $tmp = download_url( 'https://raw.githubusercontent.com/AgnimaGocran/superb-ecommerce-deploy/main/media/2022/07/' . $name . '.png' );
  if ( is_wp_error( $tmp ) ) { echo 'DL_FAIL ' . $name . "\n"; return 0; }
  $att = media_handle_sideload( array( 'name' => $name . '.png', 'tmp_name' => $tmp ), 0 );
  if ( is_wp_error( $att ) ) { @unlink( $tmp ); echo 'ATT_FAIL ' . $name . "\n"; return 0; }
  return (int) $att;
}
$products = json_decode( '[["classic-oak-chair","wc-16"],["modern-wooden-chair","wc-12"],["mounted-world-globe","wc-11"],["plant-pots-set","wc-15"],["simple-frame","wc-18"],["wooden-wall-crates","wc-img-1"],["black-white-wax-candles","wc-23"],["plant-pillow","wc-19"],["plant-pot","wc-24"],["retro-alarm-clock","wc-30"],["retro-oak-stool","wc-27"],["small-vase","wc-26"],["table-frame","wc-20"],["triangle-pillow","wc-15-1"],["wax-candle-set","wc-21"]]', true );
$cats = json_decode( '[["furniture","wc-12"],["decoration","wc-img-1"],["frames-posters","wc-18"],["pillows","wc-17"]]', true );
foreach ( $products as $p ) {
  $post = get_page_by_path( $p[0], OBJECT, 'product' );
  if ( ! $post ) { echo 'MISSING ' . $p[0] . "\n"; continue; }
  $id = f31_att( $p[1] );
  if ( $id ) { set_post_thumbnail( $post->ID, $id ); echo 'OK ' . $p[0] . "\n"; }
}
foreach ( $cats as $c ) {
  $term = get_term_by( 'slug', $c[0], 'product_cat' );
  if ( ! $term ) { echo 'NOCAT ' . $c[0] . "\n"; continue; }
  $id = f31_att( $c[1] );
  if ( $id ) update_term_meta( $term->term_id, 'thumbnail_id', $id );
  echo 'CAT ' . $c[0] . "\n";
}
echo 'F31V2_DONE\n';