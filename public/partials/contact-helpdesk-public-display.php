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
$queues = get_option( $this->plugin_name . "-queues" );
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div role="form" class="contact-helpdesk" id="contact-helpdesk-f87-p84-o1" dir="ltr" lang="en-US">
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
    <p><label> Subject <span class="required">*</span><br>
        <span class="contact-helpdesk-form-control-wrap your-subject">
            <?php if( ! $queues ) { ?>
                <span id="your-message-subject-error" class="contact-helpdesk-form-control-wrap contact-helpdesk-error">
                    Error: you have not created any queues in the plugin settings
                </span>
            <?php } else { ?>
                <select name="your-subject" id="your-subject" class="contact-helpdesk-form-control contact-helpdesk-select
            contact-helpdesk-validates-as-required" aria-required="true" aria-invalid="false">
                <?php foreach ( $queues as $key => $value ) {
                    $json_obj = json_decode( $value ); ?>
                    <option value="<?php echo $key; ?>" <?php if ( isset( $json_obj->isSelected )
                                                                   && $json_obj->isSelected ) {
                        echo "selected='selected'";
                    } ?>>
                        <?php echo $json_obj->value; ?></option>
                <?php } ?>
            </select>
            <?php } ?>
        </span> </label></p>
    <p><label> Title of your message <span class="required">*</span><br>
        <span class="contact-helpdesk-form-control-wrap your-title"><input name="your-title" id="your-title" value=""
                                                                           size="40" class="contact-helpdesk-form-control contact-helpdesk-text contact-helpdesk-validates-as-required" aria-required="true" aria-invalid="false" type="text"></span> </label>
        <span id="your-title-error" class="contact-helpdesk-form-control-wrap contact-helpdesk-error"></span></p>
    <p><label> Your Message <span class="required">*</span><br>
        <span class="contact-helpdesk-form-control-wrap your-message"><textarea name="your-message" id="your-message"
                                                                                cols="40" rows="10" class="contact-helpdesk-form-control contact-helpdesk-textarea contact-helpdesk-validates-as-required" aria-required="true" aria-invalid="false"></textarea></span> </label>
        <span id="your-message-error" class="contact-helpdesk-form-control-wrap contact-helpdesk-error"></span></p>

        <?php if( ! get_option( $this->plugin_name )['contact_helpdesk_recaptcha_site_key'] ) { ?>
        <span id="your-message-captcha-error" class="contact-helpdesk-form-control-wrap contact-helpdesk-error">
            Error: you have not set up the captcha in the plugin settings
        </span>
        <?php } else { ?>
            <div class="g-recaptcha" data-sitekey="<?php echo get_option( $this->plugin_name )['contact_helpdesk_recaptcha_site_key']; ?>" data-callback="enableContactHelpdeskBtn" data-expired-callback="recaptchaExpired"></div>
        <?php } ?>
        <p>&nbsp;</p>
    <p><input value="Send" class="contact-helpdesk-form-control contact-helpdesk-submit" type="submit" id="contact-helpdesk-send"
              disabled="disabled" hidden="hidden"><span class="ajax-loader"></span></p>
    <div class="contact-helpdesk-response-output contact-helpdesk-display-none"></div></form></div>

