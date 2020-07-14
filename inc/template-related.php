<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'wpex_post_layout_class', 'ok_disable_listing_sidebar' );
function ok_disable_listing_sidebar( $layout ) {
	if ( is_singular( 'listing' ) ) {
		$layout = 'full-width';
	}

	return $layout;
}



add_filter( 'wpex_display_page_header', 'ok_disable_listing_title' );
function ok_disable_listing_title( $return ) {
	if ( is_singular( 'listing' ) ) {
		return false;
	}

	return $return;
}



add_filter( 'wpex_listing_single_blocks', 'ok_disable_listing_meta' );
function ok_disable_listing_meta( $blocks ) {

	unset( $blocks['meta'] );
	unset( $blocks['share'] );
	unset( $blocks['comments'] );
	unset( $blocks['post-series'] );
	unset( $blocks['page-links'] );

	return $blocks;

}



add_filter( 'wpex_listing_single_blocks', 'ok_add_listing_additional_data' );
function ok_add_listing_additional_data( $blocks ) {
	$blocks['additional-data'] = 'additional-data';

	return $blocks;

}



add_filter( 'wpex_listing_entry_thumbnail_args', 'ok_change_listing_thumbnail_size' );
function ok_change_listing_thumbnail_size( $args ) {
	return array(
		'width'  => '100',
		'height' => '100',
	);

}


//adds listing widget view to search results
add_filter('wpex_template_parts', 'ok_add_listing_template_parts');
function ok_add_listing_template_parts($parts)
{
	$parts['listing_list'] = 'get_listing_list_block';

	return $parts;
}