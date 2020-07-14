<?php

// User's functions for filtering vc blocks on single listing


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


function ok_view_permission_show_on_unclaimed_listing() {
	$ok_listing = OKlistingSingle::instance();

	if ( $ok_listing->is_claimed() ) {
		return false;
	}

	return true;
}

function ok_view_permission_show_on_paid_listing() {
	$ok_listing = OKlistingSingle::instance();

	if ( $ok_listing->is_paid() ) {
		return true;
	}

	return false;
}

function ok_view_permission_show_on_premium_listing() {
	$ok_listing = OKlistingSingle::instance();

	if ( $ok_listing->is_premium() ) {
		return true;
	}

	return false;
}

function ok_view_permission_is_has_listing() {
	return (bool)get_users_listing_ID();
}

function ok_view_permission_is_not_has_listing() {
	return !ok_view_permission_is_has_listing() ;
}


function ok_view_permission_show_on_unpaid_listing() {
	return !ok_view_permission_show_on_paid_listing() ;
}




//Pro Listing tab data
function ok_get_listing_stat_impressions() {
	return ok_get_listing_single_stat( 'listing-views-count-list' );
}

function ok_get_listing_stat_views() {
	return ok_get_listing_single_stat( 'listing-views-count-single' );

}

function ok_get_listing_stat_referrals() {
	return ok_get_listing_single_stat( 'listing-contacted' );
}

function ok_get_listing_stat_favorited() {
	return ok_get_listing_single_stat( 'listing-favorites-count' );
}

function ok_get_listing_single_stat( $parameter ) {
	$displayed_user_id = bp_displayed_user_id();
	$listing_ID        = get_users_listing_ID( $displayed_user_id );
	$ok_listing        = new OKlistingSingle( $listing_ID );

	return get_post_meta( $ok_listing->ID, $parameter, true );
}