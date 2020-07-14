<?php

if (!defined('ABSPATH')) {
	exit;
}

class OKlistingSingle
{

	public $ID, $status_level;
	private static $_instance = null;


	public function __construct($listing_ID = false)
	{
		$this->ID           = ($listing_ID ? $listing_ID : wpex_get_current_post_id());
		$this->status_level = get_post_meta($this->ID, 'listing_status', true);
	}

	public static function instance()
	{
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function get_region()
	{

		return get_post_meta($this->ID, 'geolocation_short_address', true);
	}

	public function get_phone()
	{
		return get_post_meta($this->ID, 'contact_phone', true);
	}

	public function url()
	{
		return get_the_permalink($this->ID);
	}

	public function get_thumbnail($size = false)
	{
		if (has_post_thumbnail($this->ID)) {
			$args['alt'] = $this->get_title() . ', ' . $this->get_categories() . ' from ' . $this->get_region();
			$args['attachment'] = get_post_thumbnail_id($this->ID);
			$args['width'] = $args['height'] = $size == 'small' ? 90 : 150;

			return wpex_get_post_thumbnail($args);
		} else {
			
			return '<img alt=""
			src="' . OKLISTING_PLUGIN_URL . 'img/placeholder_user.svg" 
			width="150" 
			height="150" />';
		}
	}

	public function get_phone_formatted()
	{
		return preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '($1) $2-$3', $this->get_phone());
	}

	public function get_quote()
	{
		return get_post_meta($this->ID, 'quote', true);
	}

	public function print_list_class()
	{
		echo ' ' . $this->get_list_class();
	}

	public function get_list_class()
	{

		if ($this->is_premium()) {
			return 'status-premium';
		} elseif ($this->is_pro()) {
			return 'status-pro';
		} elseif ($this->is_claimed()) {
			return 'claimed';
		} else {
			return 'unclaimed';
		}
	}


	public function print_listing_attributes()
	{
		$string = '';
		$atts   = array(
			'listing-wrapper-id' => $this->ID,
		);

		foreach ($atts as $att_name => $att_val) {
			$string .= ' data-' . $att_name . '="' . $att_val . '" ';
		}

		echo $string;
	}

	public function print_button_classes()
	{
		switch ($this->status_level) {
			case 3: //premium
				$button_class = 'gold';
				break;
			case 2: //pro
				$button_class = 'blue';
				break;
			case 1: //claimed
				$button_class = 'blue';
				break;
			default: //unclaimed
				$button_class = 'grey secondary';
		}

		echo $button_class;
	}


	public function get_title()
	{

		return get_the_title($this->ID);
	}

	public function get_title_formatted()
	{
		$header = '<h1>' . $this->get_title() . '</h1>';
		$header .= $this->get_verified_badge();

		return $header;
	}



	public function get_categories()
	{
		$categories = wp_get_post_terms($this->ID, 'listing-category', array(
			'fields'    => 'names',
			'childless' => true
		));
		return implode(', ', $categories);
	}

	public function get_subtitle()
	{
		$subtitle = $this->get_categories();

		if ($academic_credentials = get_field('academic_credentials', $this->ID)) {
			$subtitle .= ', ' . $academic_credentials;
		}

		return $subtitle;
	}

	public function get_subheader_title()
	{
		$string = '';

		$categories = wp_get_post_terms($this->ID, 'listing-category', array(
			'fields'    => 'names',
			'childless' => true
		));
		$string     .= implode(', ', $categories);

		if ($this->get_region()) {
			$string .= ' in ';
			$string .= $this->get_region();
		}

		return $string;
	}

	public function get_verified_badge()
	{
		if (!$this->is_verified()) {
			return false;
		}
		ob_start();

		require(OKLISTING_PARTIALS_PATH . 'verification-badge.php');

		return ob_get_clean();
	}

	public function print_fav_toggle()
	{
		require( OKLISTING_PARTIALS_PATH . 'favorite-toggle.php' );
	}


	public function get_social_icons()
	{
		if (!$this->is_paid()) {
			return;
		}
		ob_start();
		require(OKLISTING_PARTIALS_PATH . 'single-listing-social.php');

		return ob_get_clean();
	}


	public function get_listing_header()
	{
		ob_start();
		require(OKLISTING_PARTIALS_PATH . 'single-listing-header.php');

		return ob_get_clean();
	}


	public function get_content()
	{
		$answer = ok_nl2p(get_post_meta($this->ID, 'about', true));
		if (!$answer || $answer == '') {
			$content_post = get_post($this->ID);
			$content = $content_post->post_content;
			$content = apply_filters('the_content', $content);
			$answer = str_replace(']]>', ']]&gt;', $content);
		}
		return $answer;
	}


	public function is_verified()
	{
		return (bool) get_post_meta($this->ID, 'is_verified', true);
	}

	public function is_claimed()
	{
		return $this->status_level > 0;
	}

	public function is_paid()
	{
		return $this->status_level > 1;
	}

	public function is_pro()
	{
		return $this->status_level == 2;
	}

	public function is_premium()
	{
		return $this->status_level == 3;
	}


	public function is_favorited()
	{
		if (!is_user_logged_in()) {
			return false;
		}
		
		$favorites = get_user_meta(get_current_user_id(), 'listing_favorites', true);
		return isset($favorites[$this->ID]);
	}

	public static function maybe_schedule_cron_jobs()
	{
		//@todo: check expired listings

	}
}
