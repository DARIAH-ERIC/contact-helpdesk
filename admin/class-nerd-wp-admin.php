<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @link       https://www.dariah.eu
 * @since      1.0.0
 *
 * @package    Contact_Helpdesk
 * @subpackage Contact_Helpdesk/admin
 * @author     Yoann <yoann.moranville@dariah.eu>
 */

class Contact_Helpdesk_Admin {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;
	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Nerd_Wp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Nerd_Wp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/contact-helpdesk-admin.css', array(),
			$this->version, 'all' );
	}
	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Nerd_Wp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Nerd_Wp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/nerd-wp-admin.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {
		/*
		 * Add a settings page for this plugin to the Settings menu.
		 *
		 * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
		 *
		 *        Administration Menus: http://codex.wordpress.org/Administration_Menus
		 *
		 */
		add_options_page( 'NERD Setup', 'NERD', 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page')
		);
	}
	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {
		/*
		 *  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
		 */
		$settings_link = array(
			'<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __('Settings', $this->plugin_name) . '</a>',
		);
		return array_merge(  $settings_link, $links );
	}
	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_setup_page() {
		include_once( 'partials/nerd-wp-admin-display.php' );
	}
	/**
	 *  Save the plugin options
	 *
	 *
	 * @since    1.0.0
	 */
	public function options_update() {
		register_setting( $this->plugin_name, $this->plugin_name, array($this, 'validate', 'default' => array( "url_nerd_instance" => "", "category_weight" => "0.04", "entity_weight" => "0.7" ) ) );
	}
	/**
	 * Validate all options fields
	 *
	 * @since    1.0.0
	 */
	public function validate( $input ) {
		// All checkboxes inputs
		$valid = array();
		$valid['url_nerd_instance'] = (isset($input['url_nerd_instance']) && !empty($input['url_nerd_instance'])) ? sanitize_text_field($input['url_nerd_instance']) : '';
		if ( empty($valid['url_nerd_instance']) ) {
			add_settings_error(
				'url_nerd_instance', // Setting title
				'url_nerd_instance_texterror', // Error ID
				'Please enter a valid value '.$valid['url_nerd_instance'], // Error message
				'error' // Type of message
			);
		}
		$valid['category_weight'] = (isset($input['category_weight']) && !empty($input['category_weight'])) ? sanitize_text_field($input['category_weight']) : '';
		if ( empty($valid['category_weight']) ) {
			add_settings_error(
				'category_weight',
				'category_weight_texterror',
				'Please enter a valid value '.$valid['category_weight'],
				'error'
			);
		}
		$valid['entity_weight'] = (isset($input['entity_weight']) && !empty($input['entity_weight'])) ? sanitize_text_field($input['entity_weight']) : '';
		if ( empty($valid['entity_weight']) ) {
			add_settings_error(
				'entity_weight',
				'entity_weight_texterror',
				'Please enter a valid value '.$valid['entity_weight'],
				'error'
			);
		}
		return $valid;
	}
}
