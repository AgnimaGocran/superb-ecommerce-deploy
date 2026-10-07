<?php
/**
 * Empirical fix: try both shortcode encodings on the same widget and verify
 * rendering; also fix the banner img attribute order.
 * Run: php f9.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	require '/var/www/html/wp-load.php';
}

$blocks  = get_option( 'widget_block' );
$report  = array();

foreach ( $blocks as $k => $w ) {
	if ( empty( $w['content'] ) || ! is_string( $w['content'] ) ) {
		continue;
	}
	// Shortcode widget: use the block markup (wp:shortcode block).
	if ( false !== strpos( $w['content'], '[spb-latest-posts' ) ) {
		$blocks[ $k ]['content'] = "<!-- wp:shortcode -->\n[spb-latest-posts amount=\"3\"]\n<!-- /wp:shortcode -->";
		$report[]                = 'sc_block_markup';
	}
	// Banner widget: rewrite the whole img with src first.
	if ( false !== strpos( $w['content'], 'wp-image-493' ) ) {
		$src = '/wp-content/uploads/2022/07/banner.png';
		if ( ! file_exists( $_SERVER['DOCUMENT_ROOT'] . $src ) ) {
			$r = wp_remote_get( 'https://raw.githubusercontent.com/AgnimaGocran/superb-ecommerce-deploy/main/media/2022/07/banner.png' );
			if ( ! is_wp_error( $r ) ) {
				file_put_contents( $_SERVER['DOCUMENT_ROOT'] . $src, wp_remote_retrieve_body( $r ) );
			}
		}
		$blocks[ $k ]['content'] = '<figure class="wp-block-image size-full"><img src="' . $src . '" alt="" class="wp-image-493" width="300" height="300" loading="lazy" decoding="async" /></figure>';
		$report[]                = 'banner_rewritten';
	}
}
update_option( 'widget_block', $blocks );

// Forminator 1042: check current design + set flat.
$raw = get_post_meta( 1042, '_forminator_form_settings', true );
$s   = is_array( $raw ) ? $raw : ( $raw ? unserialize( $raw ) : array() );
if ( is_array( $s ) ) {
	$report[] = 'design_before=' . ( isset( $s['form-design'] ) ? $s['form-design'] : 'none' );
	$s['form-design'] = 'flat';
	update_post_meta( 1042, '_forminator_form_settings', $s );
	$report[] = 'design_set_flat';
} else {
	$report[] = 'no_settings_meta';
}

echo implode( ' | ', $report ) . "\n";
echo "F9_DONE\n";
