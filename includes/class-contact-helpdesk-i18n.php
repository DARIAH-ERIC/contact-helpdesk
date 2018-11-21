<?php
/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.dariah.eu/
 * @since      1.0.0
 * @package    Contact_Helpdesk
 * @subpackage Contact_Helpdesk/includes
 * @author     Yoann <yoann.moranville@dariah.eu>
 */
class Contact_Helpdesk_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'contact-helpdesk',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
