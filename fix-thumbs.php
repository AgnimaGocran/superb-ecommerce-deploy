<?php
/**
 * Attach the demo featured images to the six demo blog posts.
 * Downloads each demo image (raw.githubusercontent), registers it as an
 * attachment, and sets it as the post thumbnail.
 * Run: php /var/www/html/f3.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	// Standalone CLI entry point: bootstrap WordPress.
	require '/var/www/html/wp-load.php';
}

$base  = 'https://raw.githubusercontent.com/AgnimaGocran/superb-ecommerce-deploy/main/media/';
$jobs  = array(
	'how-i-started-my-ecommerce-shop'      => '2022/05/blog-img-6.png',
	'quitting-my-corporate-job-for-my-startup' => '2022/06/blog-img-5.png',
	'the-most-important-skills-in-life'    => '2022/07/blog-img-3-1.png',
	'top-5-interior-design-trends-for-2023' => '2022/06/blog-img-1.png',
	'how-to-create-a-cozy-environment'     => '2022/06/blog-img-2.png',
	'10-interior-design-trends-for-cafes'  => '2022/05/blog-img-4.png',
);

require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

foreach ( $jobs as $slug => $rel ) {
	$post = get_page_by_path( $slug, OBJECT, 'post' );
	if ( ! $post ) {
		echo "POST_MISSING $slug\n";
		continue;
	}
	if ( has_post_thumbnail( $post ) ) {
		echo "THUMB_OK $slug\n";
		continue;
	}

	$tmp = download_url( $base . $rel );
	if ( is_wp_error( $tmp ) ) {
		echo 'DL_FAIL ' . $slug . ' ' . $tmp->get_error_message() . "\n";
		continue;
	}

	$file_array = array(
		'name'     => basename( $rel ),
		'tmp_name' => $tmp,
	);
	$att_id = media_handle_sideload( $file_array, $post->ID );
	if ( is_wp_error( $att_id ) ) {
		@unlink( $tmp );
		echo 'ATT_FAIL ' . $slug . ' ' . $att_id->get_error_message() . "\n";
		continue;
	}

	set_post_thumbnail( $post->ID, $att_id );
	echo "THUMB_SET $slug att=$att_id\n";
}

echo "THUMBS_DONE\n";
