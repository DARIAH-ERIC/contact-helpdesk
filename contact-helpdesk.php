<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.dariah.eu/
 * @since             1.0.0
 * @package           Contact_Helpdesk
 *
 * @wordpress-plugin
 * Plugin Name:       Contact Helpdesk
 * Plugin URI:        https://github.com/dariah-eric/contact-helpdesk
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Yoann
 * Author URI:        https://www.dariah.eu/
 * License:           Apache License - 2.0
 * License URI:       http://www.apache.org/licenses/LICENSE-2.0
 * Text Domain:       contact-helpdesk
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
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-contact-helpdesk-activator.php
 */
function activate_contact_helpdesk() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-contact-helpdesk-activator.php';
	Contact_Helpdesk_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-contact-helpdesk-deactivator.php
 */
function deactivate_contact_helpdesk() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-contact-helpdesk-deactivator.php';
	Contact_Helpdesk_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_contact_helpdesk' );
register_deactivation_hook( __FILE__, 'deactivate_contact_helpdesk' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-contact-helpdesk.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_contact_helpdesk() {

	$plugin = new Contact_Helpdesk();
	$plugin->run();

}
run_contact_helpdesk();
