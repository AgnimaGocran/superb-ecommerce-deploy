<?php
if ( ! defined( 'ABSPATH' ) ) { require '/var/www/html/wp-load.php'; }
$targets = array(
  'github' => 'https://raw.githubusercontent.com/AgnimaGocran/superb-ecommerce-deploy/main/media/2022/07/wc-16.png',
  'demo'   => 'https://superbdemo.com/themes/superb-ecommerce/wp-content/uploads/2022/07/wc-16-300x300.png',
);
foreach ( $targets as $k => $u ) {
  $r = wp_remote_get( $u );
  if ( is_wp_error( $r ) ) { echo $k . ': ERR ' . $r->get_error_message() . "\n"; continue; }
  $code = wp_remote_retrieve_response_code( $r );
  $body = wp_remote_retrieve_body( $r );
  echo $k . ': ' . $code . ' bytes=' . strlen( $body ) . ' head=' . substr( bin2hex( substr( $body, 0, 4 ) ), 0, 8 ) . "\n";
}
echo "PROBE_DONE\n";
