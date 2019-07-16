<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @link       https://www.dariah.eu/
 * @since      1.0.0
 * @package    Contact_Helpdesk
 * @subpackage Contact_Helpdesk/public
 * @author     Yoann <yoann.moranville@dariah.eu>
 */
class Contact_Helpdesk_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/contact-helpdesk-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/contact-helpdesk-public.js', array( 'jquery' ), $this->version, false );
		wp_register_script( "recaptcha", 'https://www.google.com/recaptcha/api.js' );
		wp_enqueue_script( "recaptcha" );
	}

    /**
     * Render the form page (the actual Helpdesk contact form).
     * If $atts is empty, print the whole page, else print the form for a specific subject.
     *
     * @since    1.0.0
     *
     * @param $atts If the shortcode has a page identifier, we can use it to display a special helpdesk
     */
    public function display_helpdesk( $atts ) {
        include_once( 'partials/contact-helpdesk-public-display.php' );
        if( is_array( $atts ) ) {
            print_helpdesk_form( $this->plugin_name, $atts['page'] );
        } else {
            print_helpdesk_form( $this->plugin_name );
        }
    }

	public function create_rest_route() {
		register_rest_route( 'contact_helpdesk/v1', 'verify_data',
			array( 'methods' => 'POST', 'callback' => array( $this, 'callback_fct' ) ) );
	}

	public function callback_fct( WP_REST_Request $request ) {
		$recaptcha_response = $request->get_param( 'g-recaptcha-response' );
		$options = get_option( $this->plugin_name );
		$queues = get_option( $this->plugin_name . "-queues" );
		$recaptcha_secret = $options['contact_helpdesk_recaptcha_secret_key'];
		$response         = wp_remote_get( "https://www.google.com/recaptcha/api/siteverify?secret=" .
		                                   $recaptcha_secret . "&response=" . $recaptcha_response );
		$response         = json_decode( $response["body"], true );
		if ( true == $response["success"] ) {
			//queues: partners(id:46), technical(id:45): , $request->get_param( 'your-subject' ),
			//				$request->get_param( 'your-message' ), $request->get_param( 'your-name' ), $request->get_param( 'your-email' )
			if( $request->get_param( 'your-title' ) == null || $request->get_param( 'your-subject' ) == null ||
			    $request->get_param( 'your-message' ) == null || $request->get_param( 'your-name' ) == null ||
			    $request->get_param( 'your-email' ) == null ) {
				return new WP_Error( 'error', esc_html__( 'Please fill all mandatory fields', 'my-text-domain'
				), array( 'status' => 400 ) );
			}
			if( $ticketId = $this->openConnectionToOTRS( $options, $queues, $_POST['your-title'], $_POST['your-subject'], $_POST['your-message'],
				$_POST['your-name'], $_POST['your-email'] ) ) {
                $headers = array(
                    'Reply-To: DARIAH-EU Helpdesk <helpdesk@dariah.eu>',
                    'Content-Type: text/html; charset=UTF-8'
                );
                wp_mail( $_POST['your-email'], "Ticket#".$ticketId, $options['email_answer'], $headers );
				return new WP_REST_Response( array( "ticketId" => $ticketId ), 200 );
			} else {
				return new WP_Error( 'unknown_error', esc_html__( 'There was an unknown error', 'my-text-domain'
				), array( 'status' => 400 ) );
			}
		} else {
			return new WP_Error( 'rest_forbidden', esc_html__( 'Bots are not allowed to submit comments', 'my-text-domain' ), array( 'status' => 401 ) );
		}
	}

	public function openConnectionToOTRS( $options, $queues, $title, $queueId, $message, $name, $email, $dryrun = false) {
		$wsdl_file_path = plugin_dir_url( __FILE__ ) . '../wsdl/GenericTicketConnector.wsdl';
		$ticketing_user = $options['ticketing_user'];
		$ticketing_password = $options['ticketing_password'];
		$default_owner_id = $options['default_owner_id'];
		$default_responsible_id = $options['default_responsible_id'];

        try {
            $owner_id = json_decode( $queues[ $queueId ] )->responsible;
        } catch (\Exception $exception) {
            error_log("We could not set the owner of the ticket, so we use the default one: " . $default_owner_id);
            $owner_id = $default_owner_id;
        }
        if( ! $dryrun ) {
            try {
                $soap_client = new SoapClient( $wsdl_file_path );
                $create = $soap_client->TicketCreate(
                    array(
                        'UserLogin' => $ticketing_user,
                        'Password'  => $ticketing_password,
                        'Ticket'    => array(
                            'Title'         => $title,
                            'QueueID'       => $queueId,
                            'TypeID'        => 1,
                            'StateID'       => 1,
                            'PriorityID'    => 3,
                            'OwnerID'       => $owner_id,
                            'ResponsibleID' => $default_responsible_id,
                            'CustomerUser'  => $ticketing_user
                        ),
                        'Article'   => array(
                            'From'     => $name . ' <' . $email . '>',
                            'Subject'  => $title,
                            'Body'     => $message,
                            'MimeType' => 'text/plain',
                            'Charset'  => 'utf8',
                        )
                    )
                );
                if ( isset( $create->Error ) || ( isset( $create->TicketID ) && ! $create->TicketID ) ) {
                    error_log( "ERROR!!!!!!" );
                    error_log( print_r( $create, true ) );

                    return false;
                } else if( isset( $create->TicketID ) ) {
                    $modify = $soap_client->TicketUpdate(
                        array(
                            'UserLogin' => $ticketing_user,
                            'Password'  => $ticketing_password,
                            'TicketID'  => $create->TicketID,
                            'Ticket'    => array(
                                'CustomerUser' => $email,
                                'CustomerID'   => $email
                            ),
                        )
                    );
                    if ( isset( $modify->Error ) ) {
                        error_log( "ERROR!!!!!!" );
                        error_log( print_r( $modify, true ) );

                        return false;
                    }
                } else {
                    throw new \Exception( "Error when creating ticket..." );
                }
                return $create->TicketNumber; //problem
            } catch (\SoapFault $fault) {
                error_log("We could not create the ticket...");
                error_log("Title: " . $title);
                error_log("Message: " . $message);
                error_log("Name - Email: " . $name . " - " . $email);
                error_log($fault);
                wp_mail( $ticketing_user, "Error in Helpdesk - new ticket", "Title: " . $title . "\n" .
                        "Message: " . $message . "\n" . "Name - Email: " . $name . " - " . $email);
                return false;
            } catch (\Exception $exception) {
                error_log("We could not create the ticket...");
                error_log("Title: " . $title);
                error_log("Message: " . $message);
                error_log("Name - Email: " . $name . " - " . $email);
                error_log($exception);
                wp_mail( $ticketing_user, "Error in Helpdesk - new ticket", "Title: " . $title . "\n" .
                                                                            "Message: " . $message . "\n" . "Name - Email: " . $name . " - " . $email);
                return false;
            }
        }
        return true;
	}
}
