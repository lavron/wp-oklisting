<?php

/**
 * Visual Composer Heading
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.6.1
 */

if (!class_exists('OKlisting_Single_Shortcode') && function_exists('vc_lean_map')) {

	class OKlisting_Single_Shortcode
	{

		/**
		 * Main constructor
		 *
		 */
		public function __construct()
		{
			add_action('admin_print_scripts', array('OKlisting_Single_Shortcode', 'admin_print_scripts'), 999);
			add_shortcode('oklisting_single', array('OKlisting_Single_Shortcode', 'output'));
			vc_lean_map('oklisting_single', array('OKlisting_Single_Shortcode', 'map'));
		}

		/**
		 * Adds scripts for custom module view
		 *
		 * @since 4.4.1
		 */
		public static function admin_print_scripts()
		{
			if ('post' != get_current_screen()->base) {
				return false;
			}
			wp_enqueue_script(
				'oklisting-js-view',
				OKLISTING_PLUGIN_URL . 'js/oklisting-js-view.js',
				array('jquery'),
				WPEX_THEME_VERSION,
				true
			);
		}

		/**
		 * Shortcode output => Get template file and display shortcode
		 *
		 * @since 3.5.0
		 */
		public static function output($atts, $content = null)
		{
			ob_start();
			include('oklisting_shortcode_el.php');

			return ob_get_clean();
		}

		/**
		 * Map shortcode to VC
		 *
		 * @since 3.5.0
		 */
		public static function map()
		{

			$acf_params          = get_acf_to_vc_fields();
			$fields_params       = $acf_params['fields'];
			$groups_param_values = $acf_params['groups'];


			return array(
				'name'        => __('OKlisting', 'total'),
				'description' => __('Listing elements', 'total'),
				'base'        => 'oklisting_single',
				'category'    => wpex_get_theme_branding(),
				'icon'        => 'vcex-heading vcex-icon fa fa-smile-o',
				'js_view'     => 'OKlisting',
				'params'      =>
				array_merge(
					array(
						// General

						array(
							'type'        => 'dropdown',
							'heading'     => __('Source', 'total'),
							'param_name'  => 'source',
							'value'       => array(
								__('Listing Title', 'total')     => 'listing_title',
								__('Listing Subtitle', 'total')  => 'listing_subtitle',
								__('Listing Content', 'total')   => 'listing_content',
								__('Listing Header', 'total')    => 'listing_header',
								__('ACF Field', 'total')         => 'acf_field',
								__('Custom Field', 'total')      => 'custom_field',
								__('Callback Function', 'total') => 'callback_function',
							),
							'admin_label' => true,
						),

						array(
							'type'        => 'dropdown',
							'heading'     => __('Field group', 'js_composer'),
							'param_name'  => 'field_group',
							'value'       => $groups_param_values,
							'save_always' => true,
							'description' => __('Select field group.', 'js_composer'),
							'dependency'  => array('element' => 'source', 'value' => 'acf_field'),
						),
					),

					//ACF
					$fields_params,

					array(
						// General


						array(
							'type'       => 'textfield',
							'heading'    => __('Custom Field ID', 'total'),
							'param_name' => 'custom_field',
							'dependency' => array('element' => 'source', 'value' => 'custom_field'),
						),
						array(
							'type'       => 'textfield',
							'heading'    => __('Callback Function', 'total'),
							'param_name' => 'callback_function',
							'dependency' => array('element' => 'source', 'value' => 'callback_function'),
						),


						array(
							'type'       => 'vcex_visibility',
							'heading'    => __('Visibility', 'total'),
							'param_name' => 'visibility',
						),
						array(
							'type'        => 'textfield',
							'heading'     => __('Extra class name', 'total'),
							'param_name'  => 'el_class',
							'description' => __('Style particular content element differently - add a class name and refer to it in custom CSS.', 'total'),
						),

						// Title and Icon


						array(
							'type'       => 'vcex_select_buttons',
							'heading'    => __('Widget headings', 'total'),
							'param_name' => 'widget_title',
							'std'        => 'custom',
							'choices'    => array( //default off
								//									'off'   => __( 'None', 'total' ),
								//									'title' => __( 'Title', 'total' ),
								'label'  => __('Titles from fields names', 'total'),
								'custom' => __('Custom title', 'total'),
							),
							//								'dependency' => array( 'element' => 'field_group', 'value' => 'all' ),
							'group'      => __('Title', 'total'),
						),


						array(
							'type'       => 'textfield',
							'heading'    => __('Custom title', 'total'),
							'param_name' => 'custom_title',
							'dependency' => array('element' => 'widget_title', 'value' => 'custom'),
							'group'      => __('Title', 'total'),
						),


						array(
							'type'        => 'dropdown',
							'heading'     => __('Icon library', 'total'),
							'param_name'  => 'icon_type',
							'description' => __('Select icon library.', 'total'),
							'value'       => array(
								__('Font Awesome', 'total') => 'fontawesome',
								__('Open Iconic', 'total')  => 'openiconic',
								__('Typicons', 'total')     => 'typicons',
								__('Entypo', 'total')       => 'entypo',
								__('Linecons', 'total')     => 'linecons',
								__('Pixel', 'total')        => 'pixelicons',
							),
							'group'       => __('Title', 'total'),
						),
						array(
							'type'       => 'iconpicker',
							'heading'    => __('Icon', 'total'),
							'param_name' => 'icon',
							'value'      => '',
							'settings'   => array(
								'emptyIcon'    => true,
								'iconsPerPage' => 200,
							),
							'dependency' => array('element' => 'icon_type', 'value' => 'fontawesome'),
							'group'      => __('Title', 'total'),
						),
						array(
							'type'       => 'iconpicker',
							'heading'    => __('Icon', 'total'),
							'param_name' => 'icon_openiconic',
							'settings'   => array(
								'emptyIcon'    => true,
								'type'         => 'openiconic',
								'iconsPerPage' => 200,
							),
							'dependency' => array('element' => 'icon_type', 'value' => 'openiconic'),
							'group'      => __('Title', 'total'),
						),
						array(
							'type'       => 'iconpicker',
							'heading'    => __('Icon', 'total'),
							'param_name' => 'icon_typicons',
							'settings'   => array(
								'emptyIcon'    => true,
								'type'         => 'typicons',
								'iconsPerPage' => 200,
							),
							'dependency' => array('element' => 'icon_type', 'value' => 'typicons'),
							'group'      => __('Title', 'total'),
						),
						array(
							'type'       => 'iconpicker',
							'heading'    => __('Icon', 'total'),
							'param_name' => 'icon_entypo',
							'settings'   => array(
								'emptyIcon'    => true,
								'type'         => 'entypo',
								'iconsPerPage' => 300,
							),
							'dependency' => array('element' => 'icon_type', 'value' => 'entypo'),
							'group'      => __('Title', 'total'),
						),
						array(
							'type'       => 'iconpicker',
							'heading'    => __('Icon', 'total'),
							'param_name' => 'icon_linecons',
							'settings'   => array(
								'emptyIcon'    => true,
								'type'         => 'linecons',
								'iconsPerPage' => 200,
							),
							'dependency' => array('element' => 'icon_type', 'value' => 'linecons'),
							'group'      => __('Title', 'total'),
						),
						//							array(
						//								'type'       => 'colorpicker',
						//								'heading'    => __( 'Color', 'total' ),
						//								'param_name' => 'icon_color',
						//								'group'      => __( 'Icon', 'total' ),
						//							),


						// Design
						array(
							'type'       => 'css_editor',
							'heading'    => __('CSS', 'total'),
							'param_name' => 'css',
							'group'      => __('Design', 'total'),
						),
						array(
							'type'        => 'vcex_ofswitch',
							'std'         => 'false',
							'heading'     => __('Add Design to Inner Span', 'total'),
							'param_name'  => 'add_css_to_inner',
							'group'       => __('Design', 'total'),
							'description' => __('Enable to add the background, padding, border, etc only around your text and icons and not the whole heading container.', 'total'),
							'dependency'  => array('element' => 'style', 'value' => 'plain'),
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => __('Background: Hover', 'total'),
							'param_name' => 'background_hover',
							'group'      => __('Design', 'total'),
							'dependency' => array('element' => 'style', 'value' => 'plain'),
						),
						array(
							'type'       => 'vcex_ofswitch',
							'heading'    => __('White Text On Hover', 'total'),
							'param_name' => 'hover_white_text',
							'std'        => 'false',
							'group'      => __('Design', 'total'),
							'dependency' => array('element' => 'style', 'value' => 'plain'),
						),
					)

				)


			);
		}
	}

	new OKlisting_Single_Shortcode;
}

if (class_exists('WPBakeryShortCode')) {
	class WPBakeryShortCode_oklisting_single extends WPBakeryShortCode
	{
		protected function outputTitle($title)
		{
			$icon = $this->settings('icon');

			return '<h4 class="wpb_element_title"><i class="vc_general vc_element-icon' . (!empty($icon) ? ' ' . $icon : '') . '"></i><span class="vcex-heading-text">' . esc_html__('OKlisting', 'total') . '<span></span></span></span></h4>';
		}
	}
}


function ok_get_listing_acf_field($atts)
{
	$field_group = $atts['field_group'];

	$field_key = !empty($atts['field_from_' . $field_group]) ? $atts['field_from_' . $field_group] : 'field_from_group_' . $field_group;
	$html = '';

	//check: do we need all fields from group or just single field
	switch ($field_key) {
		case 'all':
			//all fields from group
			$fields = acf_get_fields($field_group);
			foreach ($fields as $field) {
				//skip checkbox "Show all issues"
				if ($field['key'] == 'field_5b73006f1bfc6' && !is_dev()) {
					continue;
				}

				$current_field = get_field_object($field['key']);
				$html          .= build_single_acf_field($current_field['key'], $atts);
			}
			break;
		case 'field_5af3248148a6b':
			//"location" field: we need to add map and microdata
			$html .= build_location_acf_field($field_key, $atts);
			break;
		default:
			//single field
			$html .= build_single_acf_field($field_key, $atts);
	}

	if ($atts['custom_title'] && $html) {
		$title = do_shortcode(
			'[vcex_heading
					text="' . $atts['custom_title'] . '"
					style="bottom-border-w-color new-title"
					icon="' . $atts['icon'] . '"
					font_size="1.3em"
					inner_bottom_border_color="#18b8ea"
					]'
		);
		$html  = $title . $html;
	}


	return $html;
}


function build_location_acf_field($field_key, $atts)
{

	$post_ID = wpex_get_current_post_id();
	if (get_post_meta($post_ID, 'geolocated', true) != 1) {
		return;
	}
	$lines  = array(
		//line1
		array(
			'streetAddress' => array(
				'geolocation_street_number',
				'geolocation_street',
				'additional_address_details'
			),
		),
		//line2
		array(
			'addressLocality' => array('geolocation_locality', 'geolocation_state_short'),
			'postalCode'      => array('geolocation_postcode'),
		)
	);
	$result = array();

	$html = '';
	//add map
	$html .= build_location_acf_field_map();

	$html .= '<p itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">';

	//location title
	if ($field_value = get_post_meta($post_ID, 'location_title', true)) {
		$html .= '<strong>' . $field_value . '</strong><br>';
	}

	foreach ($lines as $line_id => $location_pieces) {
		$location_pieces_array = array();
		foreach ($location_pieces as $itemprop => $location_piece) {
			$location_pieces_array[$itemprop] = '<span itemprop="' . $itemprop . '">';

			$field_values = array();

			foreach ($location_piece as $location_piece_field) {
				if ($field_value = get_post_meta($post_ID, $location_piece_field, true)) {
					$field_values[] = $field_value;
				}
			}
			$location_pieces_array[$itemprop] .= implode(' ', array_filter($field_values));
			$location_pieces_array[$itemprop] .= '</span>';

			//if all fields in group was empty
			if (empty($field_values)) {
				unset($location_pieces_array[$itemprop]);
			}
		}
		$result[$line_id] = implode(', ', $location_pieces_array);
	}
	if (empty($result)) {
		return;
	}

	$html .= implode('<br>', $result);

	$html .= '</p>';


	//additional location
	$location_additional       = get_post_meta($post_ID, 'location_additional', true);
	$location_title_additional = get_post_meta($post_ID, 'location_title_additional', true);
	if ($location_additional && $location_title_additional) {
		$html .= '<p><strong>' . $location_title_additional . '</strong>';
		$html .= '<br><span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">' . $location_additional . '</span></p>';
	}


	//wrap it all
	$field = get_field_object($field_key);
	$html  = ok_wrap_display_field($field, $atts, $html);


	return $html;
}

function build_location_acf_field_map()
{
	$post_ID = wpex_get_current_post_id();

	//	if ( get_post_meta( $post_ID, 'geolocated', true ) != 1 ) {
	//		//no address
	//		return;
	//	}
	$html    = '';
	$size    = '330x180';
	$lat_lng = get_post_meta($post_ID, 'geolocation_lat', true) . ',' . get_post_meta($post_ID, 'geolocation_long', true);

	$apikey = get_option('bb_google_api_key', '');

	//	$options = get_option( 'oklisting' );
	//	$apikey = $options['maps_api'];

	$address = get_post_meta($post_ID, 'location', true);

	$style = 'style=hue:0x29abe1%7Csaturation:1%7Clightness:1';
	$html .= '<a href="https://www.google.com/maps/?q=' . $lat_lng . '" target="_blank" title="Open on Google Maps" data-lity class="listing-map">';

	$html .= '<img src="https://maps.googleapis.com/maps/api/staticmap?center=' . $lat_lng . '&zoom=13&size=' . $size . '&maptype=roadmap&markers=color:0x18b8ea%7Clabel:%7C' . $lat_lng . '&' . $style . '&key=' . $apikey . '" alt="Map">';

	$html .= '</a>';

	return $html;
}

function build_single_acf_field($field_key, $atts)
{

	$field = get_field_object($field_key, wpex_get_current_post_id());


	//bail, if current field should show for certain categories only, and its not the case
	if (!ok_is_field_in_tax($field)) {
		return;
	}

	if (isset($field['related_category']) && is_array($field['related_category']) && count($field['related_category']) > 0) {
		$term = get_term($field['related_category'], 'listing-category');
		$term = ($term->parent == 0) ? $term : get_term($term->parent, 'listing-category');
		if (!has_term($term->term_id, 'listing-category')) {
			return;
		}
	}

	$html = '';


	//bail if no values
	if (!$field['value']) {
		return;
	}

	$element_count = count($field['value']);

	if (!is_array($field['value'])) {
		//list of values
		$html .= $field['value'];
	} else {
		//adding columns to unordered list
		if ($element_count < 6) {
			$columns = 1;
		} elseif ($element_count < 12) {
			$columns = 2;
		} else {
			$columns = 3;
			$list_height = ceil($element_count / $columns) * 2;
		}


		$html .= '<ul class="check-list';
		$html .= ($columns > 1 ? ' columned-list columns-' . $columns : '') . '"';
		$html .= $list_height ? ' style="height: ' . $list_height . 'em;" ' : '';
		$html .= '>';

		foreach ($field['value'] as $field_item) {

			if (is_array($field_item) && (!$field_item['title'] || $field_item['title'] == '')) {
				continue;
			}
			$html .= '<li>';


			if (is_array($field_item)) {
				if (!$field_item['title'] || $field_item['title'] == '') {
					continue;
				}
				//repeating field
				$html .= implode(', ', array_filter($field_item));
			} elseif ($field['type'] == 'taxonomy') {
				//taxonomy term
				$tax  = get_term_by('id', $field_item->term_id, $field['taxonomy']);
				$html .= $tax->name;
			} else {
				//simple string value
				$html .= $field_item;
			}

			$html .= '</li>';
		}

		$html .= '</ul>';
	}


	if (
		$atts['field_from_' . $atts['field_group']] == 'all'
		&& $atts['widget_title'] != 'label'
	) {
		$label = '<strong>' . $field['label'] . '</strong>: ';
		$html  = $label . $html;
	}

	$html = ok_wrap_display_field($field, $atts, $html);

	return $html;
}


function ok_is_field_in_tax($field)
{
	$listing_ID = get_the_ID();
	$taxonomy = 'listing-category';

	if (!isset($field['related_category']) || !is_array($field['related_category'])) {
		return true;
	}


	$term = get_term_by('slug', $field['related_category'][0], $taxonomy);
	$term = ($term->parent == 0) ? $term : get_term($term->parent, $taxonomy);
	$listing_terms = wp_get_post_terms($listing_ID, $taxonomy);

	if (!has_term($term->term_id, $taxonomy, $listing_ID) && $listing_terms[0]->parent != $term->term_id) {
		return false;
	}

	return true;
}

function ok_get_listing_content($atts, $oklisting)
{
	$field                = array(
		'name'  => 'about-content',
		'label' => $atts['custom_title'],
	);
	$atts['widget_title'] = 'label';

	$text = ok_wrap_display_field($field, $atts, $oklisting->get_content());

	return $text;
}

function ok_wrap_display_field($field, $atts, $html)
{
	$wrapper_class = 'field-' . $field['name'];
	$wrapper_class .= ' listing-inner-block';

	$html = '<div class="' . $wrapper_class . '">' . $html . '</div>';

	if ($atts['widget_title'] == 'label') {
		$title = do_shortcode(
			'[vcex_heading
					text="' . $field['label'] . '"
					style="bottom-border-w-color"
					icon="' . $atts['icon'] . '"
					font_size="1.3em"
					inner_bottom_border_color="#18b8ea"
					]'
		);
		$html  = $title . $html;
	}

	return $html;
}


//adding list of ACF-groups to dropdown list in listing field
function get_acf_to_vc_fields()
{
	$groups = get_listing_field_groups();


	$groups_param_values = $fields_params = array();
	foreach ($groups as $group) {

		$id = isset($group['id']) ? 'id' : (isset($group['ID']) ? 'ID' : 'id');

		$groups_param_values[$group['title']] = $group[$id];

		$fields = function_exists('acf_get_fields') ? acf_get_fields($group[$id]) : apply_filters('acf/field_group/get_fields', array(), $group[$id]);


		$fields_param_value = array('All' => 'all');


		foreach ((array) $fields as $field) {
			$fields_param_value[$field['label']] = (string) $field['key'];
		}
		$fields_params[] = array(
			'type'        => 'dropdown',
			'heading'     => __('Field', 'js_composer'),
			'param_name'  => 'field_from_' . $group[$id],
			'value'       => $fields_param_value,
			'save_always' => true,
			'description' => __('Select field from group.', 'js_composer'),
			'dependency'  => array(
				'element' => 'field_group',
				'value'   => array((string) $group[$id]),
			),
		);
	}

	return array(
		'fields' => $fields_params,
		'groups' => $groups_param_values
	);
}


function get_listing_field_groups_ids()
{
	$groups = get_listing_field_groups();
	foreach ($groups as $index => $group) {
		$groups[$index] = $group['ID'];
	}

	return $groups;
}


function get_listing_field_groups()
{
	$groups = function_exists('acf_get_field_groups') ? acf_get_field_groups() : apply_filters('acf/get_field_groups', array());

	//all directory-related groups are start from digit: "1. Group name"
	foreach ($groups as $index => $group) {
		if (preg_match('/^\d/', $group['title']) !== 1) {
			unset($groups[$index]);
			continue;
		}
	}

	usort($groups, function ($item1, $item2) {
		//Adding only ones who starts with number i.e. "2. Listing: Contact"

		if ($item1['title'] == $item2['title']) {
			return 0;
		}

		return $item1['title'] < $item2['title'] ? -1 : 1;
	});

	return $groups;
}
