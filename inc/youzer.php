<?php 


//remove "Pro" tab if user don't have listing
add_filter('yz_profile_hidden_tabs', 'ok_hide_some_tabs');
function ok_hide_some_tabs($hidden_tabs)
{
	$pro_features_tab  = 'pro-listing';
	$fav_listings_tab  = 'favorite-listings';
	$displayed_user_ID = bp_displayed_user_id();
	$current_user_ID   = get_current_user_id();

	if (!get_users_listing_ID($displayed_user_ID) && $current_user_ID != $displayed_user_ID) {
		$hidden_tabs[] = $pro_features_tab;
	}

	if ($displayed_user_ID != $current_user_ID) {
		$hidden_tabs[] = $fav_listings_tab;
	}

	return $hidden_tabs;
}


add_shortcode('pro-features', 'ok_show_pro_tab_content');
function ok_show_pro_tab_content()
{
	$displayed_user_id = bp_displayed_user_id();
	$current_user_ID   = get_current_user_id();

	ob_start();

	$listing_ID = get_users_listing_ID($displayed_user_id);

	if ($listing_ID && get_post_status($listing_ID) == 'publish') {
		// user have listing!

		get_listing_list_block($listing_ID);

		if ($displayed_user_id == $current_user_ID) {

			//stats
			$listing_stats_templatera = get_post(ok_get_templatera_ID('listing_stats'));
			new OKlistingSingle($listing_ID);
			wpex_singular_template($listing_stats_templatera->post_content);
		}
	} elseif ($displayed_user_id == $current_user_ID) {

		//its your profile, and you dont have listing. Show call to action
		//		echo 'its your profile, and you dont have listing';
		$became_a_pro_CTA_ID = ok_get_templatera_ID('became_a_pro_CTA');
		$pro_CTA             = get_post($became_a_pro_CTA_ID);
		wpex_singular_template($pro_CTA->post_content);
	}

	return ob_get_clean();
}




add_action('show_user_profile', 'ok_show_listing_id_meta');
add_action('edit_user_profile', 'ok_show_listing_id_meta');
function ok_show_listing_id_meta($user)
{
	?>
	<table class="form-table">
		<tr>
			<th>
				<label for="listing_ID"><?php _e('Listing ID'); ?></label>
			</th>
			<td>
				<input type="text" name="listing_ID" id="listing_ID" value="<?php echo esc_attr(get_the_author_meta('listing_ID', $user->ID)); ?>" class="regular-text" />
			</td>
		</tr>
	</table>

<?php
}


add_action('personal_options_update', 'ok_show_listing_id_meta_save');
add_action('edit_user_profile_update', 'ok_show_listing_id_meta_save');
function ok_show_listing_id_meta_save($user_id)
{

	if (!current_user_can('edit_user', $user_id)) {
		return false;
	}

	/* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
	update_user_meta($user_id, 'listing_ID', $_POST['listing_ID']);
}s