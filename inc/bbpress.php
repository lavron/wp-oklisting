<?php 


//disable bb_acf scripts for all pages except Submit Listing
add_action('template_redirect', 'ok_disable_bb_acf_scripts', 10);
function ok_disable_bb_acf_scripts()
{
	if (is_admin()) {
		return;
	}
	remove_action('wp_footer', 'bb_acf_aa_footer_function');

	if (is_user_logged_in() && (is_page('submit-listing') || is_page('edit-listing'))) {
		add_action('wp_footer', 'bb_acf_aa_footer_function');
	}
}



add_filter('bbp_get_reply_class', 'ok_add_user_status_class', 99);
function ok_add_user_status_class($classes, $reply_id = false)
{
	$user_ID = bbp_get_reply_author_id($reply_id);

	if ($listing_ID = get_users_listing_ID($user_ID)) {
		$oklisting = new OKlistingSingle($listing_ID);

		$classes[] = $oklisting->get_list_class();
	}

	return $classes;
}


add_filter('bbp_get_reply_author_link', 'ok_add_forum_verified_badge', 20, 2);
function ok_add_forum_verified_badge($author_link, $r)
{

	$reply_id = bbp_get_reply_id($r['post_id']);
	$user_ID  = bbp_get_reply_author_id($reply_id);

	if ($listing_ID = get_users_listing_ID($user_ID)) {

		$oklisting = new OKlistingSingle($listing_ID);
		if ($oklisting->is_paid()) {
            ob_start();
            require(OKLISTING_PARTIALS_PATH . 'verification-badge.php');
			$author_link .= ob_get_clean();
		}
	}

	return $author_link;
}