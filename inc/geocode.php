<?php



if (!defined('ABSPATH')) {
	exit;
}

class OKlisting_Geocode
{

	const GOOGLE_MAPS_GEOCODE_API_URL = 'https://maps.googleapis.com/maps/api/geocode/json';

	private static $_instance = null;

	public static function instance()
	{
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function __construct()
	{
		add_filter('listing_geolocation_endpoint', array($this, 'add_geolocation_endpoint_query_args'), 0, 2);
		add_filter('listing_geolocation_api_key', array($this, 'get_google_maps_api_key'), 0);

		add_action('acf/save_post', array($this, 'update_location_data'), 20, 2);
		add_filter('acf/pre_save_post', array($this, 'update_location_data'), 20, 2);

		// listing submit form ID = 22
		add_action('gform_after_submission_22', array($this, 'update_location_data'), 20, 2);

		add_action('post_updated_messages', array($this, 'update_location_data'), 20, 2);

		add_action('listing_location_edited', array($this, 'change_location_data'), 20, 2);
	}

	public function update_location_data($listing, $values = false)
	{
		if (is_array($listing)) {
			$listing_id = $listing['post_id'];
			$location = $listing['40.1'];
		} else {
			$listing_id = $listing;
			$location = get_field('location', $listing_id);
		}

		if (get_field('overwrite_geo', $listing_id)) {
			return $listing_id;
		}

		if ($location) {
			$address_data = self::get_location_data($location);
			self::save_location_data($listing_id, $address_data);
		} else {
			$this::clear_location_data($listing_id);
		}

		return $listing_id;
	}


	public static function save_location_data($listing_id, $address_data)
	{

		if (get_field('overwrite_geo', $listing_id)) {
			return $listing_id;
		}

		if (!is_wp_error($address_data) && $address_data) {
			foreach ($address_data as $key => $value) {
				if ($value) {
					update_post_meta($listing_id, 'geolocation_' . $key, $value);
				}
			}
			update_post_meta($listing_id, 'geolocated', 1);
		};
	}


	public function change_location_data($listing_id, $new_location)
	{

		if (get_field('overwrite_geo', $listing_id)) {
			return;
		}

		if (apply_filters('listing_geolocation_enabled', true)) {
			$address_data = self::get_location_data($new_location);
			self::clear_location_data($listing_id);
			self::save_location_data($listing_id, $address_data);
		}
	}

	public static function has_location_data($listing_id)
	{
		return get_post_meta($listing_id, 'geolocated', true) == 1;
	}


	public static function generate_location_data($listing_id, $location)
	{
		$address_data = self::get_location_data($location);
		self::save_location_data($listing_id, $address_data);
	}

	public static function clear_location_data($listing_id)
	{
		delete_post_meta($listing_id, 'geolocated');

		$metas = get_post_custom();

		foreach ($metas as $meta_name => $meta_value) {
			if (strpos($meta_name, 'geolocation_') === 0) {
				delete_post_meta($listing_id, $meta_name);
			}
		}
	}

	public function get_google_maps_api_key($key)
	{
		return get_option('bb_google_api_key', '');
	}

	
	public function add_geolocation_endpoint_query_args($geocode_endpoint_url, $raw_address)
	{
		// Add an API key if available.
		$api_key = apply_filters('listing_geolocation_api_key', '', $raw_address);

		if ('' !== $api_key) {
			$geocode_endpoint_url = add_query_arg('key', urlencode($api_key), $geocode_endpoint_url);
		}

		$geocode_endpoint_url = add_query_arg('address', urlencode($raw_address), $geocode_endpoint_url);

		$locale = get_locale();
		if ($locale) {
			$geocode_endpoint_url = add_query_arg('language', substr($locale, 0, 2), $geocode_endpoint_url);
		}

		$region = apply_filters('listing_geolocation_region_cctld', '', $raw_address);
		if ('' !== $region) {
			$geocode_endpoint_url = add_query_arg('region', urlencode($region), $geocode_endpoint_url);
		}

		return $geocode_endpoint_url;
	}

	public static function get_location_data($raw_address)
	{
		$invalid_chars = array(" " => "+", "," => "", "?" => "", "&" => "", "=" => "", "#" => "");
		$raw_address   = trim(strtolower(str_replace(array_keys($invalid_chars), array_values($invalid_chars), $raw_address)));

		if (empty($raw_address)) {
			return false;
		}

		$transient_name              = 'jm_geocode_' . md5($raw_address);
		$geocoded_address            = get_transient($transient_name);
		$jm_geocode_over_query_limit = get_transient('jm_geocode_over_query_limit');

		// Query limit reached - don't geocode for a while
		if ($jm_geocode_over_query_limit && false === $geocoded_address) {
			return false;
		}

		$geocode_api_url = apply_filters('listing_geolocation_endpoint', self::GOOGLE_MAPS_GEOCODE_API_URL, $raw_address);
		if (false === $geocode_api_url) {
			return false;
		}

		try {
			if (false === $geocoded_address || empty($geocoded_address->results[0])) {
				$result           = wp_remote_get(
					$geocode_api_url,
					array(
						'timeout'     => 5,
						'redirection' => 1,
						'httpversion' => '1.1',
						'user-agent'  => 'WordPress/OKCLarity; ' . get_bloginfo('url'),
						'sslverify'   => false
					)
				);
				$result           = wp_remote_retrieve_body($result);
				$geocoded_address = json_decode($result);

				if ($geocoded_address->status) {
					switch ($geocoded_address->status) {
						case 'ZERO_RESULTS':
							throw new Exception(__("No results found", 'wp-listing-manager'));
							break;
						case 'OVER_QUERY_LIMIT':
							set_transient('jm_geocode_over_query_limit', 1, HOUR_IN_SECONDS);
							throw new Exception(__("Query limit reached", 'wp-listing-manager'));
							break;
						case 'OK':
							if (!empty($geocoded_address->results[0])) {
								set_transient($transient_name, $geocoded_address, DAY_IN_SECONDS * 7);
							} else {
								throw new Exception(__("Geocoding error", 'wp-listing-manager'));
							}
							break;
						default:
							throw new Exception(__("Geocoding error", 'wp-listing-manager'));
							break;
					}
				} else {
					throw new Exception(__("Geocoding error", 'wp-listing-manager'));
				}
			}
		} catch (Exception $e) {
			return new WP_Error('error', $e->getMessage());
		}

		$address                      = array();
		$address['lat']               = sanitize_text_field($geocoded_address->results[0]->geometry->location->lat);
		$address['long']              = sanitize_text_field($geocoded_address->results[0]->geometry->location->lng);
		$address['formatted_address'] = sanitize_text_field($geocoded_address->results[0]->formatted_address);
		$address['short_address'] = false;


		if (!empty($geocoded_address->results[0]->address_components)) {
			$address_data                   = $geocoded_address->results[0]->address_components;
			$address['street_number']       = false;
			$address['street']              = false;
			$address['city']                = false;
			$address['state_short']         = false;
			$address['state_long']          = false;
			$address['postcode']            = false;
			$address['country_short']       = false;
			$address['country_long']        = false;
			$address['locality']            = false;
			$address['district']            = false;



			foreach ($address_data as $data) {
				switch ($data->types[0]) {
					case 'political':
						$address['district'] = sanitize_text_field($data->long_name);
						break;
					case 'street_number':
						$address['street_number'] = sanitize_text_field($data->long_name);
						break;
					case 'route':
						$address['street'] = sanitize_text_field($data->long_name);
						break;
					case 'sublocality_level_1':
						$address['sublocality_level_1'] = sanitize_text_field($data->long_name);
						break;
					case 'locality':
						$address['locality'] = sanitize_text_field($data->long_name);
						break;
					case 'postal_town':
						$address['city'] = sanitize_text_field($data->long_name);
						break;
					case 'administrative_area_level_1':
						$address['state_short'] = sanitize_text_field($data->short_name);
						$address['state_long']  = sanitize_text_field($data->long_name);
						break;
					case 'postal_code':
						$address['postcode'] = sanitize_text_field($data->long_name);
						break;
					case 'country':
						$address['country_short'] = sanitize_text_field($data->short_name);
						$address['country_long']  = sanitize_text_field($data->long_name);
						break;

				}
			}

			$address['short_address'] = ($address['district'] ? $address['district'] : $address['locality'])
				. ', ' . $address['state_short'];
		}

		return apply_filters('listing_geolocation_get_location_data', $address, $geocoded_address);
	}
}

OKlisting_Geocode::instance();