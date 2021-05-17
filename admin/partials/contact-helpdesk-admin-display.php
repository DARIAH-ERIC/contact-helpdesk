<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.dariah.eu/
 * @since      1.0.0
 *
 * @package    Contact_Helpdesk
 * @subpackage Contact_Helpdesk/admin/partials
 */
?>

<div class="wrap">
    <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
    <h2 class="nav-tab-wrapper">Contact Helpdesk Options</h2>
    <p>You need to <a href="https://www.google.com/recaptcha/admin" target="_blank">register you domain</a> and get
        keys to make this plugin work.</p>

    <form method="post" name="contact_helpdesk_recaptcha_options" action="options.php">
		<?php
		//Grab all options
		$options = get_option( $this->plugin_name );
		$contact_helpdesk_recaptcha_site_key = isset( $options['contact_helpdesk_recaptcha_site_key'] ) ? $options['contact_helpdesk_recaptcha_site_key'] : "";
		$contact_helpdesk_recaptcha_secret_key = isset( $options['contact_helpdesk_recaptcha_secret_key'] ) ? $options['contact_helpdesk_recaptcha_secret_key'] : "";
		$ticketing_user = isset( $options['ticketing_user'] ) ? $options['ticketing_user'] : "";
		$ticketing_password = isset( $options['ticketing_password'] ) ? $options['ticketing_password'] : "";
		$default_owner_id = isset( $options['default_owner_id'] ) ? $options['default_owner_id'] : "";
		$default_responsible_id = isset( $options['default_responsible_id'] ) ? $options['default_responsible_id'] : "";
		$email_answer = isset( $options['email_answer'] ) ? $options['email_answer'] : "";

		settings_fields( $this->plugin_name );
		do_settings_sections( $this->plugin_name );
		?>

        <table class="form-table">
            <tbody>
            <!-- Contact Helpdesk reCaptcha Site Key -->
            <tr>
                <th scope="row">
                    <label for="<?php echo $this->plugin_name;?>-contact-helpdesk-recaptcha-site-key"><?php _e('reCaptcha site key', $this->plugin_name);?></label>
                </th>
                <td>
                    <input name="<?php echo $this->plugin_name;?>[contact_helpdesk_recaptcha_site_key]" id="<?php
                    echo $this->plugin_name;?>-contact-helpdesk-recaptcha-site-key" value="<?php echo
                    $contact_helpdesk_recaptcha_site_key;?>" class="regular-text" type="text">
                </td>
            </tr>
            <!-- Contact Helpdesk reCaptcha Secret Key -->
            <tr>
                <th scope="row">
                    <label for="<?php echo $this->plugin_name;?>-contact-helpdesk-recaptcha-secret-key"><?php _e('reCaptcha secret key', $this->plugin_name);?></label>
                </th>
                <td>
                    <input name="<?php echo $this->plugin_name;?>[contact_helpdesk_recaptcha_secret_key]" id="<?php
                    echo $this->plugin_name;?>-contact-helpdesk-recaptcha-secret-key" value="<?php echo
                    $contact_helpdesk_recaptcha_secret_key;?>" class="regular-text" type="password">
                </td>
            </tr>
            <!-- Contact Helpdesk Ticketing User -->
            <tr>
                <th scope="row">
                    <label for="<?php echo $this->plugin_name;?>-ticketing-user"><?php _e('Ticketing User',
                            $this->plugin_name);?></label>
                </th>
                <td>
                    <input name="<?php echo $this->plugin_name;?>[ticketing_user]" id="<?php
                    echo $this->plugin_name;?>-ticketing-user" value="<?php echo
                    $ticketing_user;?>" class="regular-text" type="text">
                </td>
            </tr>
            <!-- Contact Helpdesk Ticketing Password -->
            <tr>
                <th scope="row">
                    <label for="<?php echo $this->plugin_name;?>-ticketing-password"><?php _e('Ticketing User Password',
                            $this->plugin_name);?></label>
                </th>
                <td>
                    <input name="<?php echo $this->plugin_name;?>[ticketing_password]" id="<?php
		            echo $this->plugin_name;?>-ticketing-password" value="<?php echo
		            $ticketing_password;?>" class="regular-text" type="password">
                </td>
            </tr>
            <!-- Contact Helpdesk Default OTRS Ticketing Owner ID -->
            <tr>
                <th scope="row">
                    <label for="<?php echo $this->plugin_name;?>-default-owner-id"><?php _e('Default OTRS Ticket Owner ID',
                            $this->plugin_name);?></label>
                </th>
                <td>
                    <input name="<?php echo $this->plugin_name;?>[default_owner_id]" id="<?php
		            echo $this->plugin_name;?>-default-owner-id" value="<?php echo
		            $default_owner_id;?>" class="regular-text" type="text">
                </td>
            </tr>
            <!-- Contact Helpdesk Default OTRS Ticketing Responsible ID -->
            <tr>
                <th scope="row">
                    <label for="<?php echo $this->plugin_name;?>-default-responsible-id"><?php _e('Default OTRS Ticket Responsible ID',
                            $this->plugin_name);?></label>
                </th>
                <td>
                    <input name="<?php echo $this->plugin_name;?>[default_responsible_id]" id="<?php
		            echo $this->plugin_name;?>-default-responsible-id" value="<?php echo
		            $default_responsible_id;?>" class="regular-text" type="text">
                </td>
            </tr>
            <!-- Contact Helpdesk Answer by mail to user -->
            <tr>
                <th scope="row">
                    <label for="<?php echo $this->plugin_name;?>-email-answer"><?php _e('Email answer to user (in HTML)',
                            $this->plugin_name);?></label>
                </th>
                <td>
                    <textarea name="<?php echo $this->plugin_name;?>[email_answer]" id="<?php
                    echo $this->plugin_name;?>-email-answer" class="regular-text"><?php echo
                        $email_answer;?></textarea>
                </td>
            </tr>
            </tbody>
        </table>

		<?php submit_button( __( 'Save all changes', $this->plugin_name ), 'primary','submit', TRUE ); ?>
    </form>

    <h2 class="nav-tab-wrapper">Queue Options</h2>
    <p>Here you need to enter your queue IDs and labels.</p>
    <form method="post" name="contact_helpdesk_recaptcha_options" action="options.php">
		<?php
		$queues = get_option( $this->plugin_name . "-queues" );

		settings_fields( $this->plugin_name . "-queues" );
		do_settings_sections( $this->plugin_name );
		?>

        <table class="form-table">
            <tbody>
            <!-- Queues -->
			<?php if( isset( $queues ) && $queues != false ) {
				foreach( $queues as $queue_key => $queue_json ) {
                    $queue_obj = json_decode( $queue_json ); ?>
                    <tr>
                        <th scope="row">
                            <?php _e( 'Queue', $this->plugin_name ); ?>
                        </th>
                        <td>
                            <label for="<?php echo $this->plugin_name . "-queues[" . $queue_key . "]"; ?>"><?php echo $queue_key; ?></label>
                        </td>
                        <td>
                            <input name="<?php echo $this->plugin_name . "-queues[" . $queue_key . "-value]"; ?>"
                                   id="<?php echo $this->plugin_name . "-queues[" . $queue_key . "-value]"; ?>"
                                   value="<?php echo $queue_obj->value; ?>" class="regular-text" type="text">
                        </td>
                        <td>
                            <input name="<?php echo $this->plugin_name . "-queues[" . $queue_key . "-responsible]"; ?>"
                                   id="<?php echo $this->plugin_name . "-queues[" . $queue_key . "-responsible]"; ?>"
                                   value="<?php echo $queue_obj->responsible; ?>" class="regular-text" type="text">
                        </td>
                        <td>
                            <input name="<?php echo $this->plugin_name . "-queues[" . $queue_key . "-isSelected]"; ?>"
                                   id="<?php echo $this->plugin_name . "-queues[" . $queue_key . "-isSelected]"; ?>"
                                   value="true" type="checkbox" <?php if ( isset( $queue_obj->isSelected ) &&
                                                                           $queue_obj->isSelected == true ) {
                                echo "checked='checked'";
                            } ?>>
                        </td>
                    </tr>
                <?php }
			} ?>
            <tr id="new-queue-btn">
                <th><button type="button" onclick="add_new_queue();">Add new queue</button></th>
            </tr>
            <tr class="hidden" id="extra-queue">
                <th scope="row">
                    <label for="<?php echo $this->plugin_name;?>-queues-new-queue-key"><?php _e('New queue',
							$this->plugin_name);?></label>
                </th>
                <td>
                    <input name="<?php echo $this->plugin_name;?>-queues[new-queue-key]" id="<?php
					echo $this->plugin_name . "-queues-new-queue-key";?>" value="" class="regular-text" type="text"
                           placeholder="New queue key">
                </td>
                <td>
                    <input name="<?php echo $this->plugin_name;?>-queues[new-queue-value]" id="<?php
					echo $this->plugin_name . "-queues-new-queue-value";?>" value="" class="regular-text" type="text"
                           placeholder="New queue value">
                </td>
                <td>
                    <input name="<?php echo $this->plugin_name;?>-queues[new-queue-responsible]" id="<?php
		            echo $this->plugin_name . "-queues-new-queue-responsible";?>" value="" class="regular-text"
                           type="text" placeholder="New queue responsible person ID">
                </td>
            </tr>
            </tbody>
        </table>

		<?php submit_button( __( 'Save queue changes', $this->plugin_name ), 'primary','submit', TRUE ); ?>
    </form>

</div>
