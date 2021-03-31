<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @email      lightning-paywall@coincharge.io
 * @since      1.0.0
 *
 * @package    Lightning_Paywall
 * @subpackage Lightning_Paywall/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Lightning_Paywall
 * @subpackage Lightning_Paywall/includes
 * @author     Coincharge <https://lightning-paywall.coincharge.io>
 */
class Lightning_Paywall_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'lightning-paywall',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}


}
