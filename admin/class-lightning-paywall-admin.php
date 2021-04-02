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

	/**
	 *
	 */
	const CURRENCIES = [
		'SATS',
		'BTC',
		'USD',
		'EUR',
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

		add_menu_page('Lightning Paywall', 'Lightning Paywall', 'manage_options', 'lnpw_general_settings', '', 'dashicons-tickets');
		add_submenu_page('lnpw_general_settings', 'Settings', 'Settings', 'manage_options', 'lnpw_general_settings', array($this, 'render_general_settings_page'));
		add_submenu_page('lnpw_general_settings', 'Invoices', 'Invoices', 'manage_options', 'lnpw_invoices', array($this, 'render_invoices_page'));
	}

	/**
	 *
	 */
	public function register_settings()
	{

		register_setting('lnpw_general_settings', 'lnpw_enabled_post_types', array('type' => 'array', 'default' => array('post')));
		register_setting('lnpw_general_settings', 'lnpw_currency', array('type' => 'string', 'default' => 'SATS'));
		register_setting('lnpw_general_settings', 'lnpw_default_price', array('type' => 'number', 'default' => 10));
		register_setting('lnpw_general_settings', 'lnpw_default_duration', array('type' => 'integer', 'default' =>  ''));
		register_setting('lnpw_general_settings', 'lnpw_default_duration_type', array('type' => 'string', 'default' => 'unlimited'));
		register_setting('lnpw_general_settings', 'lnpw_default_payblock_text', array('type' => 'string', 'default' => 'For access to content first pay', 'sanitize_callback' => array($this, 'sanitize_payblock_area')));
		register_setting('lnpw_general_settings', 'lnpw_default_payblock_button', array('type' => 'string', 'default' => 'Pay', 'sanitize_callback' => array($this, 'sanitize_payblock_area')));
		register_setting('lnpw_general_settings', 'lnpw_default_payblock_info', array('type' => 'string', 'default' => '', 'sanitize_callback' => array($this, 'sanitize_payblock_area')));

		register_setting('lnpw_general_settings', 'lnpw_btcpay_server_url', array('type' => 'string', 'sanitize_callback' => array($this, 'sanitize_btcpay_server_url')));
		register_setting('lnpw_general_settings', 'lnpw_btcpay_auth_key_view', array('type' => 'string', 'sanitize_callback' => array($this, 'sanitize_btcpay_auth_key')));
		register_setting('lnpw_general_settings', 'lnpw_btcpay_auth_key_create', array('type' => 'string', 'sanitize_callback' => array($this, 'sanitize_btcpay_auth_key')));
		register_setting('lnpw_general_settings', 'lnpw_btcpay_store_id', array('type' => 'string', 'sanitize_callback' => array($this, 'sanitize_btcpay_store_id')));
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

	public function sanitize_btcpay_store_id($value)
	{
		$value = sanitize_text_field($value);

		return $value;
	}

	public function sanitize_payblock_area($value)
	{

		$value = sanitize_textarea_field($value);

		return $value;
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
			wp_send_json_error(['message' => 'Something went wrong. Please check your API keys.']);
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
			update_option("lnpw_btcpay_store_id", $view_store_id);
			wp_send_json_success();
		} else {
			wp_send_json_error();
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
	 *
	 */
	public function add_meta_boxes()
	{

		if (get_option('lnpw_enabled_post_types')) {
			add_meta_box('lnpw_post_settings', 'Lightning Paywall Settings', array($this, 'render_post_settings_meta_box'), get_option('lnpw_enabled_post_types'));
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
	 * @param  WP_Post  $post
	 * @param  array  $meta
	 */
	public function render_post_settings_meta_box($post, $meta)
	{

		wp_nonce_field(plugin_basename(__FILE__), 'lnpw_post_meta_box_nonce');

		include 'partials/meta-box-post-settings.php';
	}

	/**
	 * @param  int  $post_id
	 */
	public function save_post_settings_meta_box($post_id)
	{

		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}

		if (!isset($_POST['lnpw_post_meta_box_nonce']) || !wp_verify_nonce($_POST['lnpw_post_meta_box_nonce'], plugin_basename(__FILE__))) {
			return;
		}

		if (!empty($_POST['lnpw_enabled'])) {
			update_post_meta($post_id, 'lnpw_enabled', sanitize_text_field($_POST['lnpw_enabled']));
		} else {
			delete_post_meta($post_id, 'lnpw_enabled');
		}

		if (!empty($_POST['lnpw_price'])) {
			update_post_meta($post_id, 'lnpw_price', sanitize_text_field($_POST['lnpw_price']));
		} else {
			delete_post_meta($post_id, 'lnpw_price');
		}

		if (!empty($_POST['lnpw_duration'])) {
			update_post_meta($post_id, 'lnpw_duration', sanitize_text_field($_POST['lnpw_duration']));
		} else {
			delete_post_meta($post_id, 'lnpw_duration');
		}

		if (!empty($_POST['lnpw_duration_type'])) {
			update_post_meta($post_id, 'lnpw_duration_type', sanitize_text_field($_POST['lnpw_duration_type']));
		} else {
			delete_post_meta($post_id, 'lnpw_duration_type');
		}
	}

	/**
	 * @throws Exception
	 */
	public function load_vc_widgets()
	{

		vc_map(array(
			'name'        => 'LP Start Paid Text Content',
			'base'        => 'lnpw_start_content',
			'description' => 'Start area of paid content',
			'category'    => 'Content',
			'params'      => array(
				array(
					'type'        => 'checkbox',
					'heading'     => 'Enable payment block',
					'param_name'  => 'pay_block',
					'value'       => false,
					'description' => 'Show payment block instead of content',
				),
			),
		));

		vc_map(array(
			'name'        => 'LP End Paid Text Content',
			'base'        => 'lnpw_end_content',
			'description' => 'End area of paid content',
			'category'    => 'Content',
			'params'      => array(),
		));

		vc_map(array(
			'name'        => 'LP Pay Widget',
			'base'        => 'lnpw_pay_block',
			'description' => 'Show Payment Widget',
			'category'    => 'Content',
			'params'      => array(),
		));
	}

	public function load_elementor_widgets()
	{

		require_once 'elementor/class-start-content-widget.php';
		require_once 'elementor/class-end-content-widget.php';
		require_once 'elementor/class-pay-block-widget.php';

		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Elementor_LNPW_Start_Content_Widget());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Elementor_LNPW_End_Content_Widget());
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Elementor_LNPW_Pay_Block_Widget());
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
	public function render_gutenberg()
	{
		return do_shortcode("[lnpw_start_content pay_block='true']");
	}

	public function render_end_gutenberg()
	{
		return do_shortcode("[lnpw_end_content]");
	}

	public function load_gutenberg()
	{

		wp_register_script(
			'gutenberg-block-script',
			plugin_dir_url(__FILE__) . 'gutenberg/index.js',
			array('wp-blocks', 'wp-element', 'wp-editor'),
			$this->version
		);

		register_block_type('lightning-paywall/gutenberg-start-block', [
			'editor_script' => 'gutenberg-block-script',
			'render_callback' => (array($this, 'render_gutenberg')),
		]);
		register_block_type(
			'lightning-paywall/gutenberg-end-block',
			[
				'editor_script' => 'gutenberg-block-script',
				'render_callback' => (array($this, 'render_end_gutenberg')),
			]
		);
	}
}
