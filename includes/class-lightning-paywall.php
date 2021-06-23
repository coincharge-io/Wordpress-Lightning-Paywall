<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Lightning_Paywall
 * @subpackage Lightning_Paywall/includes
 * @author     Coincharge <https://lightning-paywall.coincharge.io>
 */
class Lightning_Paywall
{

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @access   protected
	 * @var      Lightning_Paywall_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 */
	public function __construct()
	{
		if (defined('LIGHTNING_PAYWALL_VERSION')) {
			$this->version = LIGHTNING_PAYWALL_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'lightning-paywall';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Lightning_Paywall_Loader. Orchestrates the hooks of the plugin.
	 * - Lightning_Paywall_i18n. Defines internationalization functionality.
	 * - Lightning_Paywall_Admin. Defines all hooks for the admin area.
	 * - Lightning_Paywall_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @access   private
	 */
	private function load_dependencies()
	{

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-lightning-paywall-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-lightning-paywall-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-lightning-paywall-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-lightning-paywall-public.php';

		$this->loader = new Lightning_Paywall_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Lightning_Paywall_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @access   private
	 */
	private function set_locale()
	{

		$plugin_i18n = new Lightning_Paywall_i18n();

		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @access   private
	 */
	private function define_admin_hooks()
	{

		$plugin_admin = new Lightning_Paywall_Admin($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

		$this->loader->add_action('admin_menu', $plugin_admin, 'add_menu_pages');
		$this->loader->add_action('admin_init', $plugin_admin, 'register_settings');

		$this->loader->add_action('init', $plugin_admin, 'register_post_types');

		$this->loader->add_action('wp_ajax_lnpw_check_greenfield_api_work', $plugin_admin, 'ajax_check_greenfield_api_work');
		$this->loader->add_action('wp_ajax_lnpw_get_greenfield_invoices', $plugin_admin, 'get_greenfield_invoices');


		$this->loader->add_action('init', $plugin_admin, 'load_gutenberg');
		$this->loader->add_action('vc_before_init', $plugin_admin, 'load_vc_widgets');
		$this->loader->add_action('elementor/widgets/widgets_registered', $plugin_admin, 'load_elementor_widgets');
		$this->loader->add_action('widgets_init', $plugin_admin, 'wpdocs_register_widgets');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @access   private
	 */
	private function define_public_hooks()
	{

		$plugin_public = new Lightning_Paywall_Public($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

		$this->loader->add_shortcode('lnpw_pay_block', $plugin_public, 'render_shortcode_lnpw_pay_block');
		$this->loader->add_shortcode('lnpw_pay_video_block', $plugin_public, 'render_shortcode_lnpw_pay_view_block');
		$this->loader->add_shortcode('lnpw_pay_file_block', $plugin_public, 'render_shortcode_lnpw_pay_file_block');

		$this->loader->add_filter('the_content', $plugin_public, 'filter_the_content', 50);

		$this->loader->add_action('wp_ajax_lnpw_get_invoice_id', $plugin_public, 'ajax_get_invoice_id');
		$this->loader->add_action('wp_ajax_nopriv_lnpw_get_invoice_id', $plugin_public, 'ajax_get_invoice_id');

		$this->loader->add_action('wp_ajax_lnpw_paid_invoice', $plugin_public, 'ajax_paid_invoice');
		$this->loader->add_action('wp_ajax_nopriv_lnpw_paid_invoice', $plugin_public, 'ajax_paid_invoice');

		$this->loader->add_action('wp_ajax_lnpw_tipping', $plugin_public, 'ajax_tipping');
		$this->loader->add_action('wp_ajax_nopriv_lnpw_tipping', $plugin_public, 'ajax_tipping');

		$this->loader->add_action('wp_ajax_lnpw_convert_currencies', $plugin_public, 'ajax_convert_currencies');
		$this->loader->add_action('wp_ajax_nopriv_lnpw_convert_currencies', $plugin_public, 'ajax_convert_currencies');

		$this->loader->add_action('wp_ajax_lnpw_notify_admin', $plugin_public, 'ajax_notify_administrator');
		$this->loader->add_action('wp_ajax_nopriv_lnpw_notify_admin', $plugin_public, 'ajax_notify_administrator');

		$this->loader->add_shortcode('lnpw_start_content', $plugin_public, 'render_shortcode_lnpw_start_content');
		$this->loader->add_shortcode('lnpw_end_content', $plugin_public, 'render_shortcode_lnpw_end_content');
		$this->loader->add_shortcode('lnpw_start_video', $plugin_public, 'render_shortcode_lnpw_start_video');
		$this->loader->add_shortcode('lnpw_file', $plugin_public, 'render_shortcode_lnpw_file');
		$this->loader->add_shortcode('lnpw_tipping', $plugin_public, 'render_shortcode_tipping');
		$this->loader->add_shortcode('lnpw_tipping_basic', $plugin_public, 'render_shortcode_basic_tipping');
		$this->loader->add_shortcode('lnpw_protected_file', $plugin_public, 'render_shortcode_protected_file');
		$this->loader->add_shortcode('lnpw_end_video', $plugin_public, 'render_shortcode_lnpw_end_content');
		$this->loader->add_shortcode('lnpw_video_catalog', $plugin_public, 'render_shortcode_lnpw_video_catalog');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 */
	public function run()
	{
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name()
	{
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    Lightning_Paywall_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader()
	{
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 */
	public function get_version()
	{
		return $this->version;
	}
}
