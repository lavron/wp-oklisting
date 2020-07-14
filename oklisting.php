<?php

/**
 * Plugin Name: OKListing
 * Description: Directory listings for okclarity.
 * Version: 1.5.0
 * Author: Viktor Lavron
 * Requires at least: 4.5.0
 * Tested up to: 5.5
 */

if (!defined('ABSPATH')) {
	exit;
}

define('OKLISTING_PLUGIN_URL', plugin_dir_url(__FILE__));
define('OKLISTING_PARTIALS_PATH', plugin_dir_path(__FILE__) . 'templates/');
define('OKLISTING_VERSION', '1.5.0');

require('inc/listing-post-type.php');

require('inc/OKlistingSingle.php');

require('inc/geocode.php');
require('inc/views-count.php');
require('inc/favorites.php');
require('inc/template-related.php');
require('inc/modals.php');

require('inc/visual-composer/oklisting.php');
require('inc/visual-composer/functions.php');

require('inc/facet.php');
require('inc/acf.php');
require('inc/youzer.php');
require('inc/bbpress.php');



add_action('wp_enqueue_scripts', 'ok_enqueue_scripts');
function ok_enqueue_scripts()
{

	wp_enqueue_style(
		'oklisting',
		OKLISTING_PLUGIN_URL . 'assets/css/oklisting.css',
		array('user-style'), //theme's css
		OKLISTING_VERSION
	);

	wp_enqueue_script(
		'oklisting',
		OKLISTING_PLUGIN_URL . 'assets/js/ok-listing.js',
		array('jquery'),
		OKLISTING_VERSION,
		true
	);

	wp_enqueue_script(
		'lity',
		OKLISTING_PLUGIN_URL . 'assets/js/lity.min.js',
		array('jquery'),
		'2.3.1',
		true
	);


	$root_taxonomies = get_terms(array(
		'taxonomy'   => 'listing-category',
		'hide_empty' => false,
		'parent'     => 0,
		'fields'     => 'ids'
	));

	wp_localize_script(
		'oklisting',
		'oklistingdata',
		array(
			'nonce'                   => wp_create_nonce('oklisting-nonce'),
			'rootListingCategories' => $root_taxonomies
		)
	);
}








function ok_get_templatera_ID($setting)
{
	return get_option( 'templatera_ID_'.$setting , false );
}



function ok_get_social_icons_fa_classes()
{
	return array(
		'Facebook'  => array(
			'icon' => 'facebook',
			'code' => '\f09a'
		),
		'Twitter'   => array(
			'icon' => 'twitter',
			'code' => '\f099'
		),
		'Linkedin'  => array(
			'icon' => 'linkedin',
			'code' => '\f0e1'
		),
		'Instagram' => array(
			'icon' => 'instagram',
			'code' => '\f16d'
		),

	);
}


function get_users_listing_ID($user_ID = false)
{

	$user_ID = ($user_ID ? $user_ID : get_current_user_id());

	if ($user_ID) {
		return get_user_meta($user_ID, 'listing_ID', true);
	}

	return false;
}




function ok_tie_listing_to_user($listing_ID, $user_ID)
{
	update_user_meta($user_ID, 'listing_ID', $listing_ID);
	wp_update_post(array(
		'ID'          => $listing_ID,
		'post_author' => $user_ID,
	));

	//set listing status to 1='claimed'
	update_post_meta($listing_ID, 'listing_status', 1, 0);

	return true;
}




function ok_nl2p($string)
{
	$paragraphs = '';

	foreach (explode("\n", $string) as $line) {
		if (trim($line)) {
			$paragraphs .= '<p>' . $line . '</p>';
		}
	}

	return $paragraphs;
}

function ok_p2nl($string)
{
	$string = str_replace("</p>\
<p>", "\\r\
\\r\
", $string);
	$string = str_replace("<br />", "\\r\
", $string);
	$string = strip_tags($string);

	return $string;
}


add_filter('login_redirect', 'ok_redirect_login_filter');
function ok_redirect_login_filter($redirect_to)
{
	if (isset($_REQUEST['redirect_to'])) {
		return $_REQUEST['redirect_to'];
	} else {
		return $redirect_to;
	}
}


add_filter('validate_username', 'ok_no_spaces_in_username', 20, 2);
function ok_no_spaces_in_username($valid, $username)
{
	if (strpos($username, ' ')) {
		return false;
	}
	return $valid;
}



add_action('pre_get_posts', 'ok_sort_pros_to_top');
function ok_sort_pros_to_top($query)
{
	if (!is_tax('issues')) {
		return;
	}

	if (!is_admin() && $query->is_main_query()) {
		$query->set('orderby', 'meta_value');
		$query->set('meta_key', 'listing_status');
		$query->set('order', 'DESC');
	}
}

