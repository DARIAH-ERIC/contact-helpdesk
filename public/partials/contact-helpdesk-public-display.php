<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://www.dariah.eu/
 * @since      1.0.0
 *
 * @package    Contact_Helpdesk
 * @subpackage Contact_Helpdesk/public/partials
 */
?>
<?php

function print_helpdesk_form( $plugin_name, $queue_id = false ) {
    $queues = get_option( $plugin_name . "-queues" );

    $text = '<div role="form" class="contact-helpdesk" dir="ltr" lang="en-US">
        <div id="contact-helpdesk-response"></div>
        <form action="/wp-json/contact_helpdesk/v1/verify_data" method="post" class="contact-helpdesk-form" novalidate="novalidate" id="contact-helpdesk-form">
        <p><label> Your Name <span class="required">*</span><br>
            <span class="contact-helpdesk-form-control-wrap your-name"><input name="your-name" id="your-name" value=""
                                                                              size="40" class="contact-helpdesk-form-control contact-helpdesk-text contact-helpdesk-validates-as-required" aria-required="true" aria-invalid="false" type="text"></span> </label>
            <span id="your-name-error" class="contact-helpdesk-form-control-wrap contact-helpdesk-error"></span></p>
        <p><label> Your Email <span class="required">*</span><br>
            <span class="contact-helpdesk-form-control-wrap your-email"><input name="your-email" id="your-email" value=""
                                                                               size="40" class="contact-helpdesk-form-control contact-helpdesk-text contact-helpdesk-email contact-helpdesk-validates-as-required contact-helpdesk-validates-as-email" aria-required="true" aria-invalid="false" type="email"></span> </label>
            <span id="your-email-error" class="contact-helpdesk-form-control-wrap contact-helpdesk-error"></span></p>
            <p><label> Subject <span class="required">*</span><br><span class="contact-helpdesk-form-control-wrap your-subject">';
    if ( ! $queues ) {
        $text .= '<span id="your-message-subject-error" class="contact-helpdesk-form-control-wrap contact-helpdesk-error">
                        Error: you have not created any queues in the plugin settings
                    </span>';
    } else {
        if( $queue_id > 0 && array_key_exists( $queue_id, $queues ) ) {
            $text .= '<input type="hidden" name="your-subject" value="' . $queue_id . '" />';
            $text .= '<span id="your-message-subject-selected" class="contact-helpdesk-form-control-wrap">You have selected the subject \'';
            $text .= json_decode( $queues[ $queue_id ] )->value;
            $text .= '\'</span>';
        } else {
            //Sort by alphabetical order
            foreach ( $queues as $key => $value ) {
                $json_obj      = json_decode( $value );
                $title[ $key ] = $json_obj->value;
            }
            array_multisort( $title, SORT_ASC, $queues );

            $text .= '<select name="your-subject" id="your-subject" class="contact-helpdesk-form-control contact-helpdesk-select
                contact-helpdesk-validates-as-required" aria-required="true" aria-invalid="false">';
            foreach ( $queues as $key => $value ) {
                $json_obj = json_decode( $value );
                $text     .= '<option value="' . $json_obj->identifier . '"';
                if ( isset( $json_obj->isSelected ) && $json_obj->isSelected ) {
                    $text .= ' selected="selected"';
                }
                $text .= '>' . $json_obj->value . '</option>';
            }
            $text .= '</select>';
        }
    }
    $text .= '</span></label></p>
        <p><label> Title of your message <span class="required">*</span><br>
            <span class="contact-helpdesk-form-control-wrap your-title"><input name="your-title" id="your-title" value=""
                                                                               size="40" class="contact-helpdesk-form-control contact-helpdesk-text contact-helpdesk-validates-as-required" aria-required="true" aria-invalid="false" type="text"></span> </label>
            <span id="your-title-error" class="contact-helpdesk-form-control-wrap contact-helpdesk-error"></span></p>
        <p><label> Your Message <span class="required">*</span><br>
            <span class="contact-helpdesk-form-control-wrap your-message"><textarea name="your-message" id="your-message"
                                                                                    cols="40" rows="10" class="contact-helpdesk-form-control contact-helpdesk-textarea contact-helpdesk-validates-as-required" aria-required="true" aria-invalid="false"></textarea></span> </label>
            <span id="your-message-error" class="contact-helpdesk-form-control-wrap contact-helpdesk-error"></span></p>';

    if ( ! get_option( $plugin_name )['contact_helpdesk_recaptcha_site_key'] ) {
        $text .= '<span id="your-message-captcha-error" class="contact-helpdesk-form-control-wrap contact-helpdesk-error">Error: you have not set up the captcha in the plugin settings</span>';
    } else {
        $text .= '<div class="g-recaptcha" data-sitekey="' . get_option( $plugin_name )['contact_helpdesk_recaptcha_site_key'] . '" data-callback="enableContactHelpdeskBtn" data-expired-callback="recaptchaExpired"></div>';
    }
    $text .= '<p>&nbsp;</p>
        <p><input value="Send" class="contact-helpdesk-form-control contact-helpdesk-submit" type="submit" id="contact-helpdesk-send"
                  disabled="disabled" hidden="hidden"><span class="ajax-loader"></span></p>
        <div class="contact-helpdesk-response-output contact-helpdesk-display-none"></div>
        </form>
    </div>';
    echo $text;
}
