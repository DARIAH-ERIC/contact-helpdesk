<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @link       https://www.dariah.eu/
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
		 * defined in Contact_Helpdesk_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Contact_Helpdesk_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/contact-helpdesk-admin.css', array(), $this->version, 'all' );

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
		 * defined in Contact_Helpdesk_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Contact_Helpdesk_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/contact-helpdesk-admin.js', array( 'jquery' ), $this->version, false );
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
		add_options_page( 'Contact Helpdesk Options', 'Contact Helpdesk Options', 'manage_options',
			$this->plugin_name, array( $this,	'display_plugin_setup_page' )
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
		include_once( 'partials/contact-helpdesk-admin-display.php' );
	}
	/**
	 *  Save the plugin options
	 *
	 * @since    1.0.0
	 */
	public function options_update() {
		register_setting( $this->plugin_name, $this->plugin_name, array($this, 'validate' ) );
		register_setting( $this->plugin_name . "-queues", $this->plugin_name . "-queues", array($this, 'validate_queues' ) );
	}
	/**
	 * Validate all options fields - no validation needed for reCaptcha fields...
	 *
	 * @since    1.0.0
	 */
	public function validate( $input ) {
		$valid = array();
		$valid['contact_helpdesk_recaptcha_site_key'] = sanitize_text_field( $input['contact_helpdesk_recaptcha_site_key'] );
		$valid['contact_helpdesk_recaptcha_secret_key'] = sanitize_text_field( $input['contact_helpdesk_recaptcha_secret_key'] );
		$valid['otrs_url'] = sanitize_text_field( $input['otrs_url'] );
		$valid['ticketing_user'] = sanitize_text_field( $input['ticketing_user'] );
		$valid['ticketing_password'] = sanitize_text_field( $input['ticketing_password'] );
		$valid['default_owner_id'] = sanitize_text_field( $input['default_owner_id'] );
		$valid['default_responsible_id'] = sanitize_text_field( $input['default_responsible_id'] );
		$valid['email_answer'] = $input['email_answer'];
		return $valid;
	}
	/**
	 * Validate only the queues options fields
	 *
	 * @since    1.0.0
	 */
	public function validate_queues( $input ) {
		$valid = array();
		$alreadyProcessed = array();
		foreach ( $input as $key => $value ) {
			//for all not new entries
			if ( $key != "new-queue-key" && $key != "new-queue-value" && $key != "new-queue-responsible" ) {
				if ( strpos( $key, "-" ) !== false ) {
					$identifier = explode( "-", $key, 2 )[0];
					if ( ! in_array( $identifier, $alreadyProcessed ) ) {
						$arrayInput           = array(
							"identifier"  => sanitize_text_field( $identifier ),
                            "value"       => sanitize_text_field( $input[ $identifier . '-value' ] ),
							"responsible" => $input[ $identifier . '-responsible' ],
							"isSelected"  => isset( $input[ $identifier . '-isSelected' ] ) ? $input[ $identifier . '-isSelected' ] : false
						);
						$valid[ $identifier ] = json_encode( $arrayInput );
						$alreadyProcessed[]   = $identifier;
					}
				}
			}
			//if we have a new entry
			if ( isset( $input['new-queue-key'] ) && isset( $input['new-queue-value'] ) && $input['new-queue-key'] != ""
			     && $input['new-queue-value'] != "" && isset( $input['new-queue-responsible'] ) && $input['new-queue-responsible'] != "" ) {
				//We only will work with the key, no need to iterate through the others
				if ( $key == "new-queue-key" ) {
					$arrayInput = array(
						"identifier"  => sanitize_text_field( $input['new-queue-key'] ),
						"value"       => sanitize_text_field( $input['new-queue-value'] ),
						"responsible" => $input['new-queue-responsible']
					);
					$valid[ sanitize_text_field( $input['new-queue-key'] ) ] = json_encode( $arrayInput );
				}
			}
		}
		return $valid;
	}
}
