<?php

if (!defined('ABSPATH')) {
	exit;
}

add_action('init', 'ok_listing_post_type', 0);
function ok_listing_post_type()
{

	$labels = array(
		'name'               => 'Listings',
		'singular_name'      => 'Listing',
		'menu_name'          => 'Listings',
		'all_items'          => 'All listings',
		'view_item'          => 'View listing',
		'add_new_item'       => 'Add listing',
		'add_new'            => 'Add new',
		'edit_item'          => 'Edit listing',
		'update_item'        => 'Update listing',
		'search_items'       => 'Find',
		'not_found'          => 'Not found',
		'not_found_in_trash' => 'Not found in trash',
	);
	$args   = array(
		'label'               => 'listing',
		'description'         => 'listings',
		'labels'              => $labels,
		'supports'            => array('title', 'thumbnail', 'custom-fields', 'editor', 'author', 'revisions'),
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
		'rewrite'             => array('slug' => 'directory'),
	);
	register_post_type('listing', $args);
}


add_action('init', 'listings_taxonomy_category', 0);
function listings_taxonomy_category()
{

	$labels = array(
		'name'                       => 'Category',
		'singular_name'              => 'Category',
		'menu_name'                  => 'Listing Category',
		'all_items'                  => 'All Categories',
		'parent_item'                => 'Category',
		'parent_item_colon'          => 'Category:',
		'new_item_name'              => 'New Category',
		'add_new_item'               => 'Add Category',
		'edit_item'                  => 'Edit Item',
		'update_item'                => 'Update Item',
		'separate_items_with_commas' => 'Separate items with commas',
		'search_items'               => 'Search Items',
		'add_or_remove_items'        => 'Add or remove items',
		'choose_from_most_used'      => 'Choose from the most used items',
		'not_found'                  => 'Not Found'
	);
	$args   = array(
		'labels'            => $labels,
		'hierarchical'      => true,
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'show_tagcloud'     => true,
		'label'             => 'category'
	);
	register_taxonomy('listing-category', array('listing'), $args);
}


add_action('init', 'listings_taxonomy_issues', 0);
function listings_taxonomy_issues()
{

	$labels = array(
		'name'                       => 'Issues',
		'singular_name'              => 'Issue',
		'menu_name'                  => 'Issues',
		'all_items'                  => 'All Issues',
		'parent_item'                => 'Issue',
		'parent_item_colon'          => 'Issue:',
		'new_item_name'              => 'New Issue',
		'add_new_item'               => 'Add Issue',
		'edit_item'                  => 'Edit Issue',
		'update_item'                => 'Update Issue',
		'separate_items_with_commas' => 'Separate items with commas',
		'search_items'               => 'Search Issues',
		'add_or_remove_items'        => 'Add or remove items',
		'choose_from_most_used'      => 'Choose from the most used items',
		'not_found'                  => 'Not Found'
	);
	$args   = array(
		'labels'            => $labels,
		'hierarchical'      => false,
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'show_tagcloud'     => true,
		'label'             => 'region'
	);
	register_taxonomy('issues', array('listing', 'forum', 'topic', 'post'), $args);
}


add_action('admin_head', 'ok_admin_icons');
function ok_admin_icons()
{
	echo '
	<style type="text/css">
		#menu-posts-listing .dashicons-admin-post:before {
			content: "\f484";
		}
	</style>
';
}
