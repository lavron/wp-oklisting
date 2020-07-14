<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


add_action( 'wp_ajax_nopriv_listing_view', 'ok_listing_view_increase' );
add_action( 'wp_ajax_listing_view', 'ok_listing_view_increase' );
function ok_listing_view_increase() {

	check_ajax_referer( 'oklisting-nonce', 'nonce' );

	if ( isset( $_POST['post_IDS'] ) ) {
		foreach ( $_POST['post_IDS'] as $post_id ) {

			$views_count_total = 1 + get_post_meta( $post_id, 'listing-views-count-total', true );
			update_post_meta( $post_id, 'listing-views-count-total', $views_count_total );

			$meta_key = $_POST['is_single'] == 1 ? 'listing-views-count-single' : 'listing-views-count-list';
			$views_count = 1 + get_post_meta( $post_id, $meta_key, true );
			update_post_meta( $post_id, $meta_key, $views_count );
		}
	}
	exit;
}
