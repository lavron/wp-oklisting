<?php


/**
 * Visual Composer Heading
 *
 * @package Total WordPress Theme
 * @subpackage VC Templates
 * @version 4.5.5
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Helps speed up rendering in backend of VC
if ( is_admin() && ! wp_doing_ajax() ) {
	return;
}

// Required VC functions
if ( ! function_exists( 'vc_map_get_attributes' )
     || ! function_exists( 'vc_shortcode_custom_css_class' )
     || ! function_exists( 'vc_value_from_safe' )
) {
	vcex_function_needed_notice();

	return;
}

// Get and extract shortcode attributes
$atts = vcex_vc_map_get_attributes( 'oklisting_single', $atts );
extract( $atts );

//echo '<pre>'; print_r ( $atts ); echo '</pre>';


$oklisting = OKlistingSingle::instance();

switch ( $source ) {

	case 'listing_title':
		$text = $oklisting->get_title_formatted(); // Supports archives as well
		break;
	case 'listing_subtitle':
		$text = $oklisting->get_subtitle();
		break;
	case 'listing_content':
//		$text = ok_wrap_display_field( $field, $atts, $oklisting->get_content() ) ;
//		echo '<pre>'; print_r ( $atts ); echo '</pre>';
//		$text = ok_wrap_display_field( $field, $atts, $oklisting->get_content() ) ;
		$text = ok_get_listing_content( $atts, $oklisting ) ;
		break;
	case 'listing_header':
		$text = $oklisting->get_listing_header();
		break;
	case 'acf_field':
		$text = ok_get_listing_acf_field( $atts );
		break;
	case 'custom_field':
		$text = $custom_field ? get_post_meta( wpex_get_current_post_id(), $custom_field, true ) : '';
		break;
	case 'callback_function':
		$text = ( $callback_function && function_exists( $callback_function ) ) ? call_user_func( $callback_function ) : '';
		break;
	default:
		$text = trim( vc_value_from_safe( $text ) );
		$text = do_shortcode( $text );
}


// Return if no heading
if ( empty( $text ) ) {
	return;
}


// Define& sanitize vars
$output           = $icon_left = $icon_right = $link_wrap_tag = '';
$heading_attrs    = array( 'class' => '' );
$wrap_classes     = array();
$tag              = $tag ? $tag : 'div';
$add_css_to_inner = ( 'plain' == $style ) ? $add_css_to_inner : false;

// Add classes to wrapper
if ( $style ) {
	$wrap_classes[] = 'vcex-heading-' . $style;
}
if ( $css_animation && 'none' != $css_animation ) {
	$wrap_classes[] = vcex_get_css_animation( $css_animation );
}
if ( $visibility ) {
	$wrap_classes[] = $visibility;
}
if ( $css && 'true' != $add_css_to_inner ) {
	$wrap_classes[] = vc_shortcode_custom_css_class( $css );
}
if ( $el_class ) {
	$wrap_classes[] = vcex_get_extra_class( $el_class );
}
if ( 'true' == $italic ) {
	$wrap_classes[] = 'wpex-italic';
}

// Auto responsive Text
if ( 'true' == $responsive_text && $font_size ) {

	// Convert em font size to pixels
	if ( strpos( $font_size, 'em' ) !== false ) {
		$font_size = str_replace( 'em', '', $font_size );
		$font_size = $font_size * wpex_get_body_font_size();
	}

	// Convert em min-font size to pixels
	if ( strpos( $min_font_size, 'em' ) !== false ) {
		$min_font_size = str_replace( 'em', '', $min_font_size );
		$min_font_size = $min_font_size * wpex_get_body_font_size();
	}

	// Add wrap classes and data
	if ( $font_size && $min_font_size ) {
		$wrap_classes[]                      = 'wpex-responsive-txt';
		$heading_attrs['data-max-font-size'] = absint( $font_size );
		$min_font_size                       = $min_font_size ? $min_font_size : '21px'; // 21px = default heading font size
		$min_font_size                       = apply_filters( 'wpex_vcex_heading_min_font_size', $min_font_size );
		$heading_attrs['data-min-font-size'] = absint( $min_font_size );
	}

}

// Get responsive data
if ( $responsive_data = vcex_get_module_responsive_data( $atts ) ) {
	$heading_attrs['data-wpex-rcss'] = $responsive_data;
}

// Hover data
$hover_data = array();
if ( $color_hover ) {
	$hover_data['color'] = esc_attr( $color_hover );
}
if ( $background_hover ) {
	$wrap_classes[]           = 'transition-all';
	$hover_data['background'] = esc_attr( $background_hover );
}
if ( $hover_data ) {
	$heading_attrs['data-wpex-hover'] = json_encode( $hover_data );
}

if ( 'true' == $hover_white_text ) {
	$wrap_classes[] = 'wpex-hover-white-text';
}

if ( $align ) {
	$wrap_classes[] = 'align' . $align;
}

// Inner attributes
$inner_attrs = array(
	'class' => 'vcex-heading-inner clr',
);

// Inner style
$inner_attrs['style'] = vcex_inline_style( array(
	'border_color' => $inner_bottom_border_color,
) );

// Inner CSS
if ( 'true' == $add_css_to_inner ) {
	$inner_attrs['class'] .= ' ' . vc_shortcode_custom_css_class( $css );
}


// Apply filters to classes
$wrap_classes = implode( ' ', $wrap_classes );
$wrap_classes = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $wrap_classes, 'vcex_heading', $atts );

// Add classes to attributes array
$heading_attrs['class'] = $wrap_classes;

// Add inline style
$heading_attrs['style'] = vcex_inline_style( array(
	'color'               => $color,
	'font_family'         => $font_family,
	'font_size'           => $font_size,
	'letter_spacing'      => $letter_spacing,
	'font_weight'         => $font_weight,
	'text_align'          => $text_align,
	'text_transform'      => $text_transform,
	'line_height'         => $line_height,
	'border_bottom_color' => $inner_bottom_border_color_main,
	'width'               => $width,
), false );



$output .= '<' . $tag . ' ' . wpex_parse_attrs( $heading_attrs ) . '>';

$output .= '<span data-span ' . wpex_parse_attrs( $inner_attrs ) . '>';

$output .= $text;

$output .= '</span>';

$output .= '</' . $tag . '>';

echo $output;







