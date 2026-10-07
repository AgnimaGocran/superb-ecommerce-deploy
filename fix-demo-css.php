<?php
/**
 * Replicate the demo's Additional CSS 1:1 with the live tag ids, set the
 * Forminator forms to flat, and fix the Popular Posts count (5 like demo).
 * Run: php f29.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	require '/var/www/html/wp-load.php';
}

// Live tag ids.
$t1 = get_term_by( 'slug', 'layout-1', 'post_tag' );
$t2 = get_term_by( 'slug', 'layout-2', 'post_tag' );
if ( ! $t1 || ! $t2 ) {
	echo "TAGS_MISSING\n";
	exit( 1 );
}

$css = <<<CSS
.site-footer form#forminator-module-1042 { margin-bottom: 0px; }
.site-footer button.forminator-button.forminator-button-submit { background: #fff !important; color: #000 !important; }
form#forminator-module-1042 { display: flex; }
.site-footer form#forminator-module-1042 button.forminator-button.forminator-button-submit { margin-top: 0; margin-left: 10px; padding: 11px 20px 12px 20px !important; }
.site-footer .forminator-response-message { opacity: 1 !important; display: none !important; position: absolute; }
.site-footer .forminator-ui#forminator-module-1042.forminator-design--flat .forminator-label { color:#fff; }
#secondary .spbsm-followbuttons-output-wrapper { margin-top:0px !important; }
input.forminator-input, textarea#forminator-field-textarea-1 { background: #fff !important; border: 1px solid #e4e4e4 !important; border-radius: 3px !important; }
button.forminator-button.forminator-button-submit { background: #000 !important; border-radius: 3px !important; padding: 10px 20px !important; font-weight: 600 !important; }
.tag-L1 header.page-header.search-results-header-wrapper, .tag-L2 header.page-header.search-results-header-wrapper { display: none; }
.tag-L1 .all-blog-articles { display: block; }
.tag-L1 .add-blog-to-sidebar .all-blog-articles .blogposts-list { width: 100%; max-width: 100%; flex: 100%; }
.forminator-row.forminator-row-last { margin-bottom: 0px !important; }
section#block-20 h2 { margin-top: 0px; }
img.custom-logo { height: auto; max-width: 105px; }
.woocommerce span.onsale { display:none; }
#secondary #block-27 p, .site-footer #block-34 p { display:none; }
.home .products a.button { display: none; }
.woocommerce-checkout .form-row.place-order { display: none; }
CSS;

$css = str_replace( '.tag-L1', '.tag-' . $t1->term_id, $css );
$css = str_replace( '.tag-L2', '.tag-' . $t2->term_id, $css );

$existing = wp_get_custom_css( 'superb-ecommerce' );
// Supersede my earlier global header hide.
$existing = preg_replace( '/\/\* Demo parity: archive pages render without the page-title header \*\/\s*\.archive \.page-header \{ display: none; \}\s*/', '', $existing );

$css = "\n/* Demo Additional CSS (replicated 1:1 from superbdemo) */\n" . $css . "\n";
wp_update_custom_css_post( $existing . $css );
echo "CSS_OK\n";

// Forms -> flat (as in the demo).
foreach ( array( 372, 1042 ) as $form_id ) {
	$raw = get_post_meta( $form_id, '_forminator_form_settings', true );
	$s   = is_array( $raw ) ? $raw : ( $raw ? unserialize( $raw ) : array() );
	if ( is_array( $s ) ) {
		$s['form-design'] = 'flat';
		update_post_meta( $form_id, '_forminator_form_settings', $s );
	}
}
echo "FORMS_FLAT\n";

// Popular Posts widget: 5 posts (demo shows five) + dates.
$spbr = get_option( 'widget_spbrposts_widget' );
if ( isset( $spbr[2] ) ) {
	$spbr[2]['numberofposts'] = 5;
	$spbr[2]['displaydate']   = 1;
	update_option( 'widget_spbrposts_widget', $spbr );
}

// Re-render the static spbrposts blocks (5 sidebar / 3 footer).
function f29_render( $number ) {
	$posts = get_posts( array( 'numberposts' => $number, 'post_status' => 'publish' ) );
	$out   = '<div class="spbrposts-wrapper spbrposts-align-left spbrposts-text-align-left"><ul class="spbrposts-ul">';
	foreach ( $posts as $p ) {
		$url   = get_permalink( $p );
		$title = get_the_title( $p );
		$thumb = get_the_post_thumbnail_url( $p, array( 100, 100 ) );
		$out  .= '<li class="spbrposts-li">';
		if ( $thumb ) {
			$out .= '<a class="spbrposts-img" href="' . esc_url( $url ) . '" rel="bookmark"><img width="45" height="45" class="spbrposts-thumb" src="' . esc_url( $thumb ) . '" alt="' . esc_attr( $title ) . '"></a>';
		}
		$out .= '<h3 class="spbrposts-title"><a href="' . esc_url( $url ) . '" title="Permalink to ' . esc_attr( $title ) . '" rel="bookmark">' . esc_html( $title ) . '</a></h3>';
		$out .= '<span class="spbrposts-meta-wrapper"><time class="spbrposts-time published"><a href="' . esc_url( $url ) . '" rel="bookmark">' . get_the_date( 'F j, Y', $p ) . '</a></time></span>';
		$out .= '</li>';
	}
	$out .= '</ul></div>';
	return $out;
}

$sw     = get_option( 'sidebars_widgets' );
$blocks = get_option( 'widget_block' );
$sb     = isset( $sw['sidebar-1'] ) ? $sw['sidebar-1'] : array();
foreach ( $blocks as $k => $w ) {
	if ( empty( $w['content'] ) || ! is_string( $w['content'] ) || false === strpos( $w['content'], 'spbrposts-wrapper' ) ) {
		continue;
	}
	$blocks[ $k ]['content'] = f29_render( in_array( 'block-' . $k, $sb, true ) ? 5 : 3 );
}
update_option( 'widget_block', $blocks );
echo "SPBR_5\n";
echo "F29_DONE\n";
