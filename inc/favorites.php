<?php


if (!defined('ABSPATH')) {
	exit;
}

add_action('wp_ajax_oklisting_toggle_fav', 'ok_toggle_fav');
add_action('wp_ajax_nopriv_oklisting_toggle_fav', 'ok_toggle_fav');
function ok_toggle_fav()
{

	$user_ID    = get_current_user_id();
	$listing_id = $_POST['listing_ID'];
	$favorites  = get_user_meta($user_ID, 'listing_favorites', true);

	if (!$listing_id) {
		echo 'no $listing_id';
		die();
	}


	$favorites_count = get_post_meta($listing_id, 'listing-favorites-count', true);
	$favorites_count = ($favorites_count > 0 ? $favorites_count : 0);

	if (!isset($favorites[$listing_id])) {
		$favorites[$listing_id] = 1;
		$answer = 'added';
		$favorites_count++;
	} else {
		unset($favorites[$listing_id]);
		$answer = 'removed';
		$favorites_count--;
	}
	update_post_meta($listing_id, 'listing-favorites-count', $favorites_count);
	update_user_meta($user_ID, 'listing_favorites', $favorites);

	echo $answer;
	die();
}


add_shortcode('listing_favorites', 'ok_show_favorite_listings');
function ok_show_favorite_listings()
{
	$favorites = get_user_meta(get_current_user_id(), 'listing_favorites', true);
	if ($favorites && count($favorites) > 0) {
		$args  = array(
			'showposts' => -1,
			'post_type' => 'listing',
			'post__in'  => array_keys($favorites)
		);
		$query = new WP_Query($args);
		while ($query->have_posts()) {
			$query->the_post();
			$oklisting = new OKlistingSingle(get_the_ID());
			require(OKLISTING_PARTIALS_PATH . 'block-listing-list.php');
		};
	}
}