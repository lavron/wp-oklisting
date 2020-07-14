<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_footer', 'ok_print_modal_window' );
function ok_print_modal_window() {

	$modal_templates = array();
	$string          = 'modal';

	$args  = array(
		'showposts' => - 1,
		'post_type' => 'templatera'
	);
	$query = new WP_Query( $args );
	while ( $query->have_posts() ) {
		$query->the_post();

		if ( strpos( strtolower( get_the_title() ), $string ) === 0 ) {
			$modal_templates[] = array( get_the_ID() => substr( get_the_title(), 6 ) );
		}

	};

	$html = '';


	foreach ( $modal_templates as $modal_template ) {


		$temp_post = get_post( key( $modal_template ) );
		ob_start();
		wpex_singular_template( $temp_post->post_content );
		$modal_content = ob_get_clean();

		$img = ($modal_template[ key( $modal_template ) ] == 'about_verification' ? 'close-x-black.svg' : 'close-x-white.svg');

		$close_button = '<img class="close-button" src="'.get_stylesheet_directory_uri().'/assets/images/'.$img.'" data-lity-close  title="Close window" alt="x">';



		$html .= '<div class="modal lity-hide" id="' . $modal_template[ key( $modal_template ) ] . '">';
		$html .= $close_button;
		$html .= $modal_content;
		$html .= '</div>';
	}

	echo $html;
}



//content of modal window "Contact with specialist"
add_action( 'wp_ajax_nopriv_ok_get_pro_contact_form', 'ok_get_pro_contact_form' );
add_action( 'wp_ajax_ok_get_pro_contact_form', 'ok_get_pro_contact_form' );
function ok_get_pro_contact_form() {

	$template_ID = ok_get_templatera_ID('pro_contact_form');
	$temp_post = get_post( $template_ID );

	ob_start();

	WPBMap::addAllMappedShortcodes();
	add_filter( 'wp_ulike_return_final_templates', 'remove_heart_from_here' );
	add_filter( 'wpex_post_id', 'ok_get_ajax_listing_ID', 10 );

	wpex_singular_template( apply_filters( 'the_content', $temp_post->post_content ));

	echo ob_get_clean();

	remove_filter( 'wp_ulike_return_final_templates', 'remove_heart_from_here' );
	remove_filter( 'wpex_post_id', 'ok_get_ajax_listing_ID', 10 );

	die();
}



function ok_get_ajax_listing_ID($id) {
	if ( isset($_POST['listing_ID']) ) {
		$id = $_POST['listing_ID'];
	}

	return $id;
}