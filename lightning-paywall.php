<?php

/**
 * Plugin Name:       Lightning Paywall
 * Plugin URI:        https://lightning-paywall.coincharge.io
 * Description:       Lightning Paywall is a WordPress plugin for publishers to charge for paid content. With the help of WordPress Lightning Paywall plugin you can offer previews of your blog posts and accept bitcoin payment for a single post (pay-per-post) via Lightning Network.
 * Version:           1.1.2
 * Author:            https://coincharge.io
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       lightning-paywall
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('LIGHTNING_PAYWALL_VERSION', '1.1.2');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-lightning-paywall-activator.php
 */
function activate_lightning_paywall()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-lightning-paywall-activator.php';
	Lightning_Paywall_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-lightning-paywall-deactivator.php
 */
function deactivate_lightning_paywall()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-lightning-paywall-deactivator.php';
	Lightning_Paywall_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_lightning_paywall');
register_deactivation_hook(__FILE__, 'deactivate_lightning_paywall');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-lightning-paywall.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_lightning_paywall()
{

	$plugin = new Lightning_Paywall();
	$plugin->run();
}

run_lightning_paywall();
