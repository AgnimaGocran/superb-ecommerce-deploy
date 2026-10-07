<?php
/**
 * Post-fixes for the replicated widgets:
 *  - banner image src (direct uploads path, downloading the file if missing)
 *  - spb-latest-posts shortcode block (raw shortcode, no block wrapper)
 *  - Forminator 1042 design -> flat (as in the demo)
 * Run: php f8.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	require '/var/www/html/wp-load.php';
}

$report = array();

// 1. Banner image.
$src    = '/wp-content/uploads/2022/07/banner.png';
$abs    = $_SERVER['DOCUMENT_ROOT'] . $src;
$attach = get_posts( array( 'post_type' => 'attachment', 'title' => 'banner', 'numberposts' => 1, 'post_status' => 'any' ) );
if ( $attach ) {
	$file = get_attached_file( $attach[0]->ID );
	if ( $file && file_exists( $file ) ) {
		$src = wp_get_attachment_image_url( $attach[0]->ID, 'full' );
	}
}
if ( '' === $src || ! file_exists( $abs ) ) {
	if ( ! file_exists( dirname( $abs ) ) ) {
		mkdir( dirname( $abs ), 0755, true );
	}
	$r = wp_remote_get( 'https://raw.githubusercontent.com/AgnimaGocran/superb-ecommerce-deploy/main/media/2022/07/banner.png' );
	if ( ! is_wp_error( $r ) && 200 === wp_remote_retrieve_response_code( $r ) ) {
		file_put_contents( $abs, wp_remote_retrieve_body( $r ) );
		$report[] = 'banner_downloaded';
		$src      = $src;
	} else {
		$report[] = 'banner_download_failed';
	}
}

$blocks = get_option( 'widget_block' );
foreach ( $blocks as $k => $w ) {
	if ( empty( $w['content'] ) || ! is_string( $w['content'] ) ) {
		continue;
	}
	// 1. banner src fix.
	if ( false !== strpos( $w['content'], 'wp-image-493' ) && false !== strpos( $w['content'], 'src=""' ) ) {
		$blocks[ $k ]['content'] = str_replace( 'src=""', 'src="' . esc_url( $src ) . '"', $w['content'] );
		$report[]                = 'banner_src_fixed';
	}
	// 2. shortcode widget: store the raw shortcode.
	if ( false !== strpos( $w['content'], '[spb-latest-posts' ) ) {
		$blocks[ $k ]['content'] = '[spb-latest-posts amount="3"]';
		$report[]                = 'shortcode_fixed';
	}
}
update_option( 'widget_block', $blocks );

// 3. Forminator 1042 -> flat design.
$settings = get_post_meta( 1042, '_forminator_form_settings', true );
if ( $settings ) {
	$s = is_array( $settings ) ? $settings : unserialize( $settings );
	if ( is_array( $s ) ) {
		$s['form-design'] = 'flat';
		update_post_meta( 1042, '_forminator_form_settings', $s );
		$report[] = 'form_flat';
	}
}

echo implode( ' | ', $report ) . "\n";
echo "WIDGETS2_DONE\n";
