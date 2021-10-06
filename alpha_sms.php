<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://alpha.net.bd
 * @since             1.0.0
 * @package           Alpha_sms
 *
 * @wordpress-plugin
 * Plugin Name:       AlphaSMS
 * Plugin URI:        https://alpha.net.bd
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Alpha Net Developer Team
 * Author URI:        https://alpha.net.bd
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       alpha_sms
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'ALPHA_SMS_VERSION', '1.0.0' );

// plugin constants
//Set TimeZone
date_default_timezone_set('Asia/Dhaka');

define("TIMESTAMP", date("Y-m-d H:i:s"));

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-alpha_sms-activator.php
 */
function activate_alpha_sms() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-alpha_sms-activator.php';
	Alpha_sms_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-alpha_sms-deactivator.php
 */
function deactivate_alpha_sms() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-alpha_sms-deactivator.php';
	Alpha_sms_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_alpha_sms' );
register_deactivation_hook( __FILE__, 'deactivate_alpha_sms' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-alpha_sms.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_alpha_sms() {

	$plugin = new Alpha_sms();
	$plugin->run();

}
run_alpha_sms();
