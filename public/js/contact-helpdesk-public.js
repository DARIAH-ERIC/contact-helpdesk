function enableContactHelpdeskBtn() {
    jQuery("#contact-helpdesk-send").prop('disabled', false).prop('hidden', false);
}
function recaptchaExpired() {
    jQuery("#contact-helpdesk-send").prop('disabled', true).prop('hidden', true);
}
function provideErrorMsg(inputId) {
    if(jQuery(inputId).val().length === 0) {
        jQuery(inputId + "-error").html("Please fill this field in...");
    } else {
        jQuery(inputId + "-error").html("");
    }
}

jQuery(document).ready(function($) {
    $("#contact-helpdesk-form").submit(function (e) {
        var form = $(this);
        var url = form.attr('action');

        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(),
            success: function (data) {
                var contactHelpdeskResponse = $("#contact-helpdesk-response");
                $(contactHelpdeskResponse).html("Thank you for your message, it was successfully processed and" +
                    " we will contact you shortly.<br/>Your ticket number is #" + data.ticketId + " and you will" +
                    " soon receive a mail about your ticket.");
                $(form).html("");
                $("html,body").animate({scrollTop: contactHelpdeskResponse.offset().top - 500});
            },
            error: function(data) {
                console.log(data);
                console.log(data.responseJSON.code);
                if(data.responseJSON.code === "rest_forbidden") {
                    $("#contact-helpdesk-response").html(data.responseJSON.message);
                    $(form).html("");
                } else if(data.responseJSON.code === "error") {
                    provideErrorMsg("#your-name");
                    provideErrorMsg("#your-email");
                    provideErrorMsg("#your-title");
                    provideErrorMsg("#your-message");
                } else if(data.responseJSON.code === "unknown_error") {
                    $("#contact-helpdesk-response").html(data.responseJSON.message);
                    $(form).html("");
                } else {
                    $("#contact-helpdesk-response").html("Error... (Please contact technical-support@dariah.eu)");
                    $(form).html("");
                }
                var contactHelpdeskResponse = $("#contact-helpdesk-response");
                $("html,body").animate({scrollTop: contactHelpdeskResponse.offset().top - 500});
            }
        });
        e.preventDefault();
    });

    $("#your-name").keyup(function ($event) {
        provideErrorMsg("#your-name");
    });
    $("#your-email").keyup(function ($event) {
        provideErrorMsg("#your-email");
    });
    $("#your-title").keyup(function ($event) {
        provideErrorMsg("#your-title");
    });
    $("#your-message").keyup(function ($event) {
        provideErrorMsg("#your-message");
    });
});
