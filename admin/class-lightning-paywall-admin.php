<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Lightning_Paywall
 * @subpackage Lightning_Paywall/admin
 * @author     Coincharge <https://lightning-paywall.coincharge.io>
 */
class Lightning_Paywall_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 *
	 */
	const DURATIONS = [
		'minute',
		'hour',
		'week',
		'month',
		'year',
		'onetime',
		'unlimited',
	];

	const CURRENCIES = [
		'SATS',
		'USD',
		'EUR',
	];

	const TIPPING_CURRENCIES = [
		'SATS',
		'BTC',
		'USD',
		'EUR',
	];

	const BTC_FORMAT = [
		'SATS',
		'BTC',
	];
	/**
	 * Initialize the class and set its properties.
	 *
	 * @param  string  $plugin_name  The name of this plugin.
	 * @param  string  $version  The version of this plugin.
	 *
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 */
	public function enqueue_styles()
	{

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/lightning-paywall-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 */
	public function enqueue_scripts()
	{

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/lightning-paywall-admin.js', array('jquery'), $this->version, false);

		wp_enqueue_style('wp-color-picker');

		wp_enqueue_script('iris', admin_url('js/iris.min.js'), array('jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch'), false, 1);

		if (!did_action('wp_enqueue_media')) {
			wp_enqueue_media();
		}
	}

	public function register_post_types()
	{

		register_post_type('lnpw_order', [
			'label'               => 'LP Orders',
			'public'              => true,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'show_ui'             => true,
			'show_in_nav_menus'   => false,
			'show_in_menu'        => false,
			'show_in_admin_bar'   => false,
			'show_in_rest'        => false,
			'rest_base'           => null,
			'menu_position'       => null,
			'menu_icon'           => null,
			'hierarchical'        => false,
			'supports'            => ['title', 'custom-fields'],
			'taxonomies'          => [],
			'has_archive'         => false,
			'rewrite'             => true,
			'query_var'           => true,
		]);
	}

	/**
	 * Add admin menu pages
	 */
	public function add_menu_pages()
	{

		add_menu_page('Lightning Paywall', 'Lightning Paywall', 'manage_options', 'lnpw_general_settings', '', 'dashicons-tagcloud');
		add_submenu_page('lnpw_general_settings', 'Settings', 'Settings', 'manage_options', 'lnpw_general_settings', array($this, 'render_general_settings_page'));
		add_submenu_page('lnpw_general_settings', 'Invoices', 'Invoices', 'manage_options', 'lnpw_invoices', array($this, 'render_invoices_page'));
		add_submenu_page('lnpw_general_settings', 'Tipping', 'Tipping', 'manage_options', 'lnpw_tipping-settings', array($this, 'render_tipping_page'));
	}

	/**
	 *
	 */
	public function register_settings()
	{

		register_setting('lnpw_general_settings', 'lnpw_enabled_post_types', array('type' => 'array', 'default' => array('post')));
		register_setting('lnpw_general_settings', 'lnpw_default_currency', array('type' => 'string', 'default' => 'SATS'));
		register_setting('lnpw_general_settings', 'lnpw_default_btc_format', array('type' => 'string', 'default' => 'SATS'));
		register_setting('lnpw_general_settings', 'lnpw_default_price', array('type' => 'number', 'default' => 10));
		register_setting('lnpw_general_settings', 'lnpw_default_duration', array('type' => 'integer', 'default' =>  ''));
		register_setting('lnpw_general_settings', 'lnpw_default_duration_type', array('type' => 'string', 'default' => 'unlimited'));
		register_setting('lnpw_general_settings', 'lnpw_default_payblock_text', array('type' => 'string', 'default' => 'For access to content first pay', 'sanitize_callback' => array($this, 'sanitize_payblock_area')));
		register_setting('lnpw_general_settings', 'lnpw_default_payblock_button', array('type' => 'string', 'default' => 'Pay', 'sanitize_callback' => array($this, 'sanitize_payblock_area')));
		register_setting('lnpw_general_settings', 'lnpw_default_payblock_info', array('type' => 'string', 'default' => '', 'sanitize_callback' => array($this, 'sanitize_payblock_area')));

		register_setting('lnpw_general_settings', 'lnpw_btcpay_server_url', array('type' => 'string', 'sanitize_callback' => array($this, 'sanitize_btcpay_server_url')));
		register_setting('lnpw_general_settings', 'lnpw_btcpay_auth_key_view', array('type' => 'string', 'sanitize_callback' => array($this, 'sanitize_btcpay_auth_key')));
		register_setting('lnpw_general_settings', 'lnpw_btcpay_auth_key_create', array('type' => 'string', 'sanitize_callback' => array($this, 'sanitize_btcpay_auth_key')));

		register_setting('lnpw_tipping_banner_settings', 'lnpw_tipping_dimension', array('type' => 'string', 'default' => '250x300', 'sanitize_callback' => array($this, 'sanitize_text')));
		register_setting('lnpw_tipping_banner_settings', 'lnpw_tipping_redirect', array('type' => 'string', 'default' => get_site_url()));

		register_setting('lnpw_tipping_banner_settings', 'lnpw_tipping_collect', array('type' => 'array', 'default' => array(
		'name' => array(
		'collect' => 'false',
		'mandatory' => 'false'
		),
		'email' => array(
		'collect' => 'false',
		'mandatory' => 'false'
		),
		'address' => array(
		'collect' => 'false',
		'mandatory' => 'false'
		),
		'phone' => array(
		'collect' => 'false',
		'mandatory' => 'false'
		),
		'message' => array(
		'collect' => 'false',
		'mandatory' => 'false'
		),

		), 'sanitize_callback' => array($this, 'validate_collect_info')));

		register_setting('lnpw_tipping_banner_settings', 'lnpw_tipping_fixed_amount', array(
		'type' => 'array', 'default' => array(
		'value1' => array(
		'enabled' => 'false',
		'currency' => 'SATS',
		'amount' => 1000,
		'icon' => 'fas fa-coffee'
		),
		'value2' => array(
		'enabled' => 'false',
		'currency' => 'SATS',
		'amount' => 2000,
		'icon' => 'fas fa-beer'
		),
		'value3' => array(
		'enabled' => 'false',
		'currency' => 'SATS',
		'amount' => 3000,
		'icon' => 'fas fa-cocktail'
		),
		), 'sanitize_callback' => array($this, 'validate_predefined_values')
		));
		register_setting('lnpw_tipping_banner_settings', 'lnpw_tipping_text', array(
		'type' => 'array', 'default' => array(
		'title' => 'Support my work',
		'description' => '',
		'info' => 'Enter Tipping Amount',
		'button' => 'Tipping now'
		), 'sanitize_callback' => array($this, 'validate_textarea')
		));

		register_setting('lnpw_tipping_banner_settings', 'lnpw_tipping_currency', array('type' => 'string', 'default' => 'SATS'));
		register_setting('lnpw_tipping_banner_settings', 'lnpw_tipping_color', array(
		'type' => 'array', 'default' => array(
		'button_text' => '#FFFFFF',
		'background' => '#E6E6E6',
		'button' => '#FE642E',
		'title' => '#000000',
		'description' => '#000000',
		'tipping' => '#000000',
		), 'sanitize_callback' => array($this, 'validate_colors')
		));
		register_setting('lnpw_tipping_banner_settings', 'lnpw_tipping_image', array(
		'type' => 'array', 'default' => array(
		'logo' => '',
		'background' => '',
		), 'sanitize_callback' => array($this, 'validate_images')
		));

		register_setting('lnpw_tipping_banner_settings', 'lnpw_tipping_enter_amount', array('type' => 'string', 'default' => 'false', 'sanitize_callback' => array($this, 'sanitize_mandatory')));









		register_setting('lnpw_tipping_box_settings', 'lnpw_tipping_dimension', array('type' => 'string', 'default' => '250x300', 'sanitize_callback' => array($this, 'sanitize_text')));
		register_setting('lnpw_tipping_box_settings', 'lnpw_tipping_redirect', array('type' => 'string', 'default' => get_site_url()));

		register_setting('lnpw_tipping_box_settings', 'lnpw_tipping_collect', array('type' => 'array', 'default' => array(
		'name' => array(
		'collect' => 'false',
		'mandatory' => 'false'
		),
		'email' => array(
		'collect' => 'false',
		'mandatory' => 'false'
		),
		'address' => array(
		'collect' => 'false',
		'mandatory' => 'false'
		),
		'phone' => array(
		'collect' => 'false',
		'mandatory' => 'false'
		),
		'message' => array(
		'collect' => 'false',
		'mandatory' => 'false'
		),

		), 'sanitize_callback' => array($this, 'validate_collect_info')));


		register_setting('lnpw_tipping_box_settings', 'lnpw_tipping_text', array(
		'type' => 'array', 'default' => array(
		'title' => 'Support my work',
		'description' => '',
		'info' => 'Enter Tipping Amount',
		'button' => 'Tipping now'
		), 'sanitize_callback' => array($this, 'validate_textarea')
		));

		register_setting('lnpw_tipping_box_settings', 'lnpw_tipping_currency', array('type' => 'string', 'default' => 'SATS'));
		register_setting('lnpw_tipping_box_settings', 'lnpw_tipping_color', array(
		'type' => 'array', 'default' => array(
		'button_text' => '#FFFFFF',
		'background' => '#E6E6E6',
		'button' => '#FE642E',
		'title' => '#000000',
		'description' => '#000000',
		'tipping' => '#000000',
		), 'sanitize_callback' => array($this, 'validate_colors')
		));
		register_setting('lnpw_tipping_box_settings', 'lnpw_tipping_image', array(
		'type' => 'array', 'default' => array(
		'logo' => '',
		'background' => '',
		), 'sanitize_callback' => array($this, 'validate_images')
		));

	}
	public function validate_images($values)
	{
		$default_values = array(
			'logo'		 => '',
			'background' => '',
		);

		if (!is_array($values)) {
			return $default_values;
		}

		foreach ($values as $key => $value) {

			$default_values[$key] = sanitize_text_field($value);
		}

		return $default_values;
	}
	public function validate_colors($values)
	{
		$default_values = array(
			'button_text'		=> '#FFFFFF',
			'background'		=> '#E6E6E6',
			'button'			=> '#FE642E',
			'title'				=> '#000000',
			'description'		=> '#000000',
			'tipping'			=> '#000000',
		);

		if (!is_array($values)) {
			return $default_values;
		}

		foreach ($values as $key => $value) {

			$default_values[$key] = sanitize_hex_color($value);
		}

		return $default_values;
	}
	public function validate_textarea($values)
	{
		$default_values = array(
			'title'			=> 'Support my work',
			'description'	=> '',
			'info'			=> 'Enter Tipping Amount',
			'button'		=> 'Tipping now'
		);

		if (!is_array($values)) {
			return $default_values;
		}

		foreach ($values as $key => $value) {

			$default_values[$key] = sanitize_textarea_field($value);
		}

		return $default_values;
	}
	public function validate_predefined_values($values)
	{

		$default_values	= array(
			'value1' => array(
				'enabled' 	=> 'false',
				'currency' => 'SATS',
				'amount'	=> 1000,
				'icon'		=> 'fas fa-coffee'
			),
			'value2' => array(
				'enabled' 	=> 'false',
				'currency' => 'SATS',
				'amount'	=> 2000,
				'icon'		=> 'fas fa-beer'
			),
			'value3' => array(
				'enabled' 	=> 'false',
				'currency' => 'SATS',
				'amount'	=> 3000,
				'icon'		=> 'fas fa-cocktail'
			),

		);

		if (!is_array($values)) {
			return $default_values;
		}

		foreach ($values as $key => $value) {
			$default_values[$key]['enabled'] = (isset($value['enabled']) ? 'true' : 'false');
			$default_values[$key]['currency'] = $value['currency'];
			$default_values[$key]['amount'] = $value['amount'];
			$default_values[$key]['icon'] = sanitize_text_field($value['icon']);
		}
		return $default_values;
	}

	public function validate_collect_info($values)
	{

		$default_values	= array(
			'name' => array(
				'label'		=> 'Full name',
				'collect'	=> 'false',
				'mandatory'	=> 'false'
			),
			'email' => array(
				'label'		=> 'Email',
				'collect'	=> 'false',
				'mandatory'	=> 'false'
			),
			'address' => array(
				'label'		=> 'Address',
				'collect'	=> 'false',
				'mandatory'	=> 'false'
			),
			'phone' => array(
				'label'		=> 'Phone',
				'collect'	=> 'false',
				'mandatory'	=> 'false'
			),
			'message' => array(
				'label'		=> 'Message',
				'collect'	=> 'false',
				'mandatory'	=> 'false'
			),

		);

		if (!is_array($values)) {
			return $default_values;
		}

		foreach ($values as $key => $value) {
			$default_values[$key]['collect'] = (isset($value['collect']) ? 'true' : 'false');
			$default_values[$key]['mandatory'] = (isset($value['mandatory']) ? 'true' : 'false');
		}
		return $default_values;
	}
	public function sanitize_btcpay_server_url($value)
	{

		$value = sanitize_text_field($value);

		return trim($value, '/');
	}

	public function sanitize_btcpay_auth_key($value)
	{
		$value = sanitize_text_field($value);

		return $value;
	}


	public function sanitize_payblock_area($value)
	{

		$value = sanitize_textarea_field($value);

		return $value;
	}

	public function sanitize_color($value)
	{

		$value = sanitize_hex_color($value);

		return $value;
	}

	public function sanitize_text($value)
	{

		$value = sanitize_text_field($value);

		return $value;
	}

	public function sanitize_mandatory($value)
	{

		return (isset($value) ? 'true' : 'false');
	}
	/**
	 * Helper function for extracting permission string from server
	 */

	public function startsWith($string, $startString)
	{
		$len = strlen($startString);

		return (substr($string, 0, $len) === $startString);
	}


	/**
	 * Check if API key contains required permission
	 */
	public function checkPermission($list, $permission)
	{
		foreach ($list as $perm) {
			if ($this->startsWith($perm, $permission)) {
				return true;
			}
		}
		return false;
	}
	/**
	 * @param  WP_Post  $post
	 * @param  array  $meta
	 */
	public function render_post_settings_meta_box($post, $meta)
	{

		wp_nonce_field(plugin_basename(__FILE__), 'lnpw_post_meta_box_nonce');
	}
	private function check_store_id($store_id)
	{

		if (get_option("lnpw_btcpay_store_id") !== false) {

			update_option("lnpw_btcpay_store_id", $store_id);
		} else {

			add_option("lnpw_btcpay_store_id", $store_id, null, 'no');
		}
	}


	/**
	 *	Check connection with a server
	 */
	public function ajax_check_greenfield_api_work()
	{

		if (empty($_POST['auth_key_view']) || empty($_POST['auth_key_create']) || empty($_POST['server_url'])) {
			wp_send_json_error(['message' => 'Auth Keys & Server Url required']);
		}

		$auth_key_view   = sanitize_text_field($_POST['auth_key_view']);
		$auth_key_create   = sanitize_text_field($_POST['auth_key_create']);
		$server_url = sanitize_text_field($_POST['server_url']);

		$args_view = array(
			'headers'     => array(
				'Authorization' => 'token ' . $auth_key_view,
				'Content-Type'  => 'application/json',
			),
			'method'      => 'GET',
			'timeout'     => 10
		);
		$args_create = array(
			'headers'     => array(
				'Authorization' => 'token ' . $auth_key_create,
				'Content-Type'  => 'application/json',
			),
			'method'      => 'GET',
			'timeout'     => 10
		);
		$url = "{$server_url}/api/v1/api-keys/current";

		$response_view = wp_remote_request($url, $args_view);

		$response_create = wp_remote_request($url, $args_create);

		if (is_wp_error($response_view) || is_wp_error($response_create)) {
			wp_send_json_error(['message' => 'Something went wrong. Please check your credentials.']);
		}

		$view_permission = json_decode($response_view['body'])->permissions[0];
		$create_permission = json_decode($response_create['body'])->permissions[0];

		$valid_view_permission = $this->checkPermission(json_decode($response_view['body'], true)['permissions'], 'btcpay.store.canviewinvoices');

		$valid_create_permission = $this->checkPermission(json_decode($response_create['body'], true)['permissions'], 'btcpay.store.cancreateinvoice');

		$valid_permissions = $valid_create_permission && $valid_view_permission;

		$valid_response_code = ($response_view['response']['code'] === 200) && ($response_create['response']['code'] === 200);

		$view_store_id = substr($view_permission, strrpos($view_permission, ':') + 1);
		$create_store_id = substr($create_permission, strrpos($create_permission, ':') + 1);
		$valid_store_id = $view_store_id === $create_store_id;

		if ($valid_permissions && $valid_store_id && $valid_response_code) {
			$this->check_store_id($view_store_id);
			wp_send_json_success(["store_id" => $view_store_id]);
		} else {
			wp_send_json_error(['message' => 'Something went wrong. Please check your credentials.']);
		}
	}

	/** 
	 * Fetch invoices
	 */

	public function get_greenfield_invoices()
	{

		$store_id = get_option('lnpw_btcpay_store_id');

		$args = array(
			'headers'     => array(
				'Authorization' => 'token ' . get_option('lnpw_btcpay_auth_key_view'),
				'Content-Type'  => 'application/json',
			),
			'method'      => 'GET',
			'timeout'     => 20,
		);

		if (!empty($store_id)) {

			$url = get_option('lnpw_btcpay_server_url') . '/api/v1/stores/' . $store_id . '/invoices';


			$response = wp_remote_get($url, $args);

			if (is_wp_error($response)) {
				wp_send_json_error();
			}
			$body = wp_remote_retrieve_body($response);


			$data = json_decode($body, true);

			if ($data) {
				wp_send_json_success($data);
			}
			wp_send_json_error($data);
		}
	}


	/**
	 * Render General Settings page
	 */
	public function render_general_settings_page()
	{
		include 'partials/page-general-settings.php';
	}

	/**
	 * Render Invoices page
	 */
	public function render_invoices_page()
	{
		include 'partials/page-invoices.php';
	}

	/**
	 * Render Tipping page
	 */
	public function render_tipping_page()
	{
		include 'partials/page-tipping-settings.php';
	}

	/**
	 * @throws Exception
	 */
	public function load_vc_widgets()
	{

		vc_map(array(
			'name'        => 'LP Pay-per-Post Start',
			'base'        => 'lnpw_start_content',
			'description' => 'Start area of paid content',
			'category'    => 'Content',
			'icon'		  => plugin_dir_url(__FILE__) . 'img/icon.svg',
			'params'      => array(
				array(
					'type'        => 'checkbox',
					'heading'     => 'Enable payment block',
					'param_name'  => 'pay_block',
					'value'       => 'true',
					'description' => 'Show payment block instead of content',
				),
				array(
					'type'        => 'dropdown',
					'heading'     => 'Currency',
					'param_name'  => 'currency',
					'value'       => array(
						'Default'	=> '',
						'SATS'  	=> 'SATS',
						'EUR'  		=> 'EUR',
						'USD' 		=> 'USD'
					),
					'std'		  => 'Default',
					'description' => 'Set currency',
				),
				array(
					'type'        => 'dropdown',
					'heading'     => 'BTC format',
					'param_name'  => 'btc_format',
					'dependency'  => array(
						'element' => 'currency',
						'value'	  => 'SATS'
					),
					'value'       => array(
						'Default'	=> '',
						'SATS'  	=> 'SATS',
						'BTC' 		=> 'BTC',
					),
					'std'		  => 'Default',
					'description' => 'Set BTC format',
				),
				array(
					'type'        => 'textfield',
					'heading'     => 'Price',
					'param_name'  => 'price',
					'description' => 'Set price',
				),
				array(
					'type'        => 'dropdown',
					'heading'     => 'Duration type',
					'param_name'  => 'duration_type',
					'value'       => array(
						'default'	=> '',
						'minute'  	=> 'minute',
						'hour' 		=> 'hour',
						'day'  		=> 'day',
						'week' 		=> 'week',
						'month' 	=> 'month',
						'year'  	=> 'year',
						'onetime' 	=> 'onetime',
						'unlimited' => 'unlimited',
					),
					'std'    	  => 'default',
					'description' => 'Set duration type',
				),
				array(
					'type'        => 'textfield',
					'heading'     => 'Duration',
					'param_name'  => 'duration',
					'description' => 'Set duration',
				),
			),
		));

		vc_map(array(
			'name'        => 'LP Pay-per-Post End',
			'base'        => 'lnpw_end_content',
			'description' => 'End area of paid content',
			'category'    => 'Content',
			'icon'		  => plugin_dir_url(__FILE__) . 'img/icon.svg',
			'params'      => array(),
		));

		vc_map(array(
			'name'        => 'LP Pay Widget',
			'base'        => 'lnpw_pay_block',
			'description' => 'Show Payment Widget',
			'category'    => 'Content',
			'icon'		  => plugin_dir_url(__FILE__) . 'img/icon.svg',
			'params'      => array(),
		));

		vc_map(array(
			'name'        => 'LP Pay-per-View Start',
			'base'        => 'lnpw_start_video',
			'description' => 'Start area of paid video content',
			'category'    => 'Content',
			'icon'		  => plugin_dir_url(__FILE__) . 'img/icon.svg',
			'params'      => array(
				array(
					'type'        => 'checkbox',
					'heading'     => 'Enable payment block',
					'param_name'  => 'pay_view_block',
					'value'       => 'true',
					'description' => 'Show payment block instead of video',
				),
				array(
					'type'        => 'textarea',
					'heading'     => 'Title',
					'param_name'  => 'title',
					'value'       => 'Untitled',
					'description' => 'Enter video title',
				),
				array(
					'type'        => 'textarea',
					'heading'     => 'Description',
					'param_name'  => 'description',
					'value'       => 'No description',
					'description' => 'Enter video description',
				),
				array(
					'type'        => 'attach_image',
					'heading'     => 'Preview',
					'param_name'  => 'preview',
					'description' => 'Add video preview',
				),
				array(
					'type'        => 'dropdown',
					'heading'     => 'Currency',
					'param_name'  => 'currency',
					'value'       => array(
						'Default'	=> '',
						'SATS'  	=> 'SATS',
						'EUR'  		=> 'EUR',
						'USD' 		=> 'USD'
					),
					'std'		  => 'Default',
					'description' => 'Set currency',
				),
				array(
					'type'        => 'dropdown',
					'heading'     => 'BTC format',
					'param_name'  => 'btc_format',
					'dependency'  => array(
						'element' => 'currency',
						'value'	  => 'SATS'
					),
					'value'       => array(
						'Default'	=> '',
						'SATS'  	=> 'SATS',
						'BTC' 		=> 'BTC',
					),
					'std'		  => 'Default',
					'description' => 'Set BTC format',
				),
				array(
					'type'        => 'textfield',
					'heading'     => 'Price',
					'param_name'  => 'price',
					'description' => 'Set price',
				),
				array(
					'type'        => 'dropdown',
					'heading'     => 'Duration type',
					'param_name'  => 'duration_type',
					'value'       => array(
						'default'	=> '',
						'minute'  	=> 'minute',
						'hour' 		=> 'hour',
						'day'  		=> 'day',
						'week' 		=> 'week',
						'month' 	=> 'month',
						'year'  	=> 'year',
						'onetime' 	=> 'onetime',
						'unlimited' => 'unlimited',
					),
					'std'    	  => 'default',
					'description' => 'Set duration type',
				),
				array(
					'type'        => 'textfield',
					'heading'     => 'Duration',
					'param_name'  => 'duration',
					'description' => 'Set duration',
				),
			),
		));

		vc_map(array(
			'name'        => 'LP Pay-per-View End',
			'base'        => 'lnpw_end_video',
			'description' => 'End area of paid video content',
			'category'    => 'Content',
			'icon'		  => plugin_dir_url(__FILE__) . 'img/icon.svg',
			'params'      => array(),
		));

		vc_map(array(
			'name'        => 'LP Video Catalog',
			'base'        => 'lnpw_store',
			'description' => 'Show Videos',
			'category'    => 'Content',
			'icon'		  => plugin_dir_url(__FILE__) . 'img/icon.svg',
			'params'      => array(),
		));

		vc_map(array(
			'name'        => 'LP Pay-per-File',
			'base'        => 'lnpw_file',
			'description' => 'Area of file',
			'category'    => 'Content',
			'icon'		  => plugin_dir_url(__FILE__) . 'img/icon.svg',
			'params'      => array(
				array(
					'type'        => 'checkbox',
					'heading'     => 'Enable payment block',
					'param_name'  => 'pay_file_block',
					'value'       => 'true',
					'description' => 'Show payment block instead of file',
				),
				array(
					'type'        => 'vc_link',
					'heading'     => 'File',
					'param_name'  => 'file',
					'description' => 'Add file link',
				),
				array(
					'type'        => 'textarea',
					'heading'     => 'Title',
					'param_name'  => 'title',
					'value'       => 'Untitled',
					'description' => 'Enter file title',
				),
				array(
					'type'        => 'textarea',
					'heading'     => 'Description',
					'param_name'  => 'description',
					'value'       => 'No description',
					'description' => 'Enter file description',
				),
				array(
					'type'        => 'attach_image',
					'heading'     => 'Preview',
					'param_name'  => 'preview',
					'description' => 'Add file preview',
				),
				array(
					'type'        => 'dropdown',
					'heading'     => 'Currency',
					'param_name'  => 'currency',
					'value'       => array(
						'Default'	=> '',
						'SATS'  	=> 'SATS',
						'EUR'  		=> 'EUR',
						'USD' 		=> 'USD'
					),
					'std'		  => 'Default',
					'description' => 'Set currency',
				),
				array(
					'type'        => 'dropdown',
					'heading'     => 'BTC format',
					'param_name'  => 'btc_format',
					'dependency'  => array(
						'element' => 'currency',
						'value'	  => 'SATS'
					),
					'value'       => array(
						'Default'	=> '',
						'SATS'  	=> 'SATS',
						'BTC' 		=> 'BTC',
					),
					'std'		  => 'Default',
					'description' => 'Set BTC format',
				),
				array(
					'type'        => 'textfield',
					'heading'     => 'Price',
					'param_name'  => 'price',
					'description' => 'Set price',
				),
				array(
					'type'        => 'dropdown',
					'heading'     => 'Duration type',
					'param_name'  => 'duration_type',
					'value'       => array(
						'default'	=> '',
						'minute'  	=> 'minute',
						'hour' 		=> 'hour',
						'day'  		=> 'day',
						'week' 		=> 'week',
						'month' 	=> 'month',
						'year'  	=> 'year',
						'onetime' 	=> 'onetime',
						'unlimited' => 'unlimited',
					),
					'std'    	  => 'default',
					'description' => 'Set duration type',
				),
				array(
					'type'        => 'textfield',
					'heading'     => 'Duration',
					'param_name'  => 'duration',
					'description' => 'Set duration',
				),
			),
		));
	}

	public function load_elementor_widgets()
	{
		require_once __DIR__ . '/elementor/class-start-content-widget.php';
		require_once __DIR__ . '/elementor/class-end-content-widget.php';
		require_once __DIR__ . '/elementor/class-pay-block-widget.php';
		require_once __DIR__ . '/elementor/class-start-video-widget.php';
		require_once __DIR__ . '/elementor/class-end-video-widget.php';
		require_once __DIR__  . '/elementor/class-store-widget.php';
		require_once __DIR__  . '/elementor/class-file-widget.php';

		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Elementor_LNPW_Start_Content_Widget());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Elementor_LNPW_End_Content_Widget());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Elementor_LNPW_Pay_Block_Widget());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Elementor_LNPW_Start_Video_Widget());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Elementor_LNPW_End_Video_Widget());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Elementor_LNPW_Store_Widget());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Elementor_LNPW_File_Widget());
	}

	/**
	 * @return string[]|WP_Post_Type[]
	 */
	public static function get_allowed_post_types()
	{

		$post_types   = get_post_types(['publicly_queryable' => true]);
		$post_types['page'] = 'page';

		$blocked_post_types = [
			'elementor_library',
			'e-landing-page',
			'attachment',
		];

		foreach ($blocked_post_types as $post_type) {
			if (isset($post_types[$post_type])) {
				unset($post_types[$post_type]);
			}
		}

		return $post_types;
	}
	public function render_gutenberg($atts)
	{
		$atts = shortcode_atts(array(
			'pay_block' => 'true',
			'btc_format' => '',
			'currency' => '',
			'price' => '',
			'duration_type' => '',
			'duration' => '',
		), $atts);


		return do_shortcode("[lnpw_start_content pay_block='{$atts['pay_block']}' btc_format='{$atts['btc_format']}' price='{$atts['price']}' duration_type='{$atts['duration_type']}' duration='{$atts['duration']}' currency='{$atts['currency']}']");
	}

	public function render_end_gutenberg()
	{
		return do_shortcode("[lnpw_end_content]");
	}

	public function render_video_catalog_gutenberg()
	{
		return do_shortcode("[lnpw_video_catalog]");
	}
	public function render_file_gutenberg($atts)
	{
		$atts = shortcode_atts(array(
			'pay_file_block' => 'true',
			'btc_format' => '',
			'file'	=> '',
			'title' => 'Untitled',
			'description' => 'No description',
			'preview' => '',
			'currency' => '',
			'price' => '',
			'duration_type' => '',
			'duration' => '',
		), $atts);

		return do_shortcode("[lnpw_file pay_file_block='{$atts['pay_file_block']}' btc_format='{$atts['btc_format']}' file='{$atts['file']}' title='{$atts['title']}' description='{$atts['description']}' preview={$atts['preview']} price='{$atts['price']}' duration_type='{$atts['duration_type']}' duration='{$atts['duration']}' currency='{$atts['currency']}']");
	}
	public function render_start_video_gutenberg($atts)
	{


		$atts = shortcode_atts(array(
			'pay_view_block' => 'true',
			'btc_format' => '',
			'title' => 'Untitled',
			'description' => 'No description',
			'preview' => '',
			'currency' => '',
			'price' => '',
			'duration_type' => '',
			'duration' => '',
		), $atts);

		return do_shortcode("[lnpw_start_video pay_view_block='{$atts['pay_view_block']}' btc_format='{$atts['btc_format']}' title='{$atts['title']}' description='{$atts['description']}' preview={$atts['preview']} price='{$atts['price']}' duration_type='{$atts['duration_type']}' duration='{$atts['duration']}' currency='{$atts['currency']}']");
	}
	public function load_gutenberg()
	{

		wp_register_script(
			'gutenberg-block-script',
			plugin_dir_url(__FILE__) . 'gutenberg/index.js',
			array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components'),
			$this->version
		);

		register_block_type(
			'lightning-paywall/gutenberg-start-block',
			[
				'editor_script' => 'gutenberg-block-script',
				'render_callback' => (array($this, 'render_gutenberg')),
			]
		);

		register_block_type(
			'lightning-paywall/gutenberg-end-block',
			[
				'editor_script' => 'gutenberg-block-script',
				'render_callback' => (array($this, 'render_end_gutenberg')),
			]
		);

		register_block_type(
			'lightning-paywall/gutenberg-catalog-view',
			[
				'editor_script' => 'gutenberg-block-script',
				'render_callback' => (array($this, 'render_video_catalog_gutenberg')),
			]
		);

		register_block_type(
			'lightning-paywall/gutenberg-start-video-block',
			[
				'editor_script' => 'gutenberg-block-script',
				'render_callback' => (array($this, 'render_start_video_gutenberg')),
			]
		);

		register_block_type(
			'lightning-paywall/gutenberg-end-video-block',
			[
				'editor_script' => 'gutenberg-block-script',
				'render_callback' => (array($this, 'render_end_gutenberg')),
			]
		);

		register_block_type(
			'lightning-paywall/gutenberg-file-block',
			[
				'editor_script' => 'gutenberg-block-script',
				'render_callback' => (array($this, 'render_file_gutenberg')),
			]
		);
	}
	public function wpdocs_register_widgets()
	{
		require_once __DIR__ . '/widgets/tipping_box.php';
		require_once __DIR__ . '/widgets/tipping_banner_wide.php';
		require_once __DIR__ . '/widgets/tipping_banner_high.php';
		register_widget(new Tipping_Box());
		register_widget(new Tipping_Banner_Wide());
		register_widget(new Tipping_Banner_High());
	}
}
