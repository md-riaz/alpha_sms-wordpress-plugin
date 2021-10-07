window.$ = jQuery;

$(function () {
    // Perform AJAX login on form submit
    $('form#loginform').find(':submit').on('click', saveAndSendOtp);

});

// Error template
function showError(msg) {
    return `<div id="login_error"><strong>Error</strong>: ${msg}<br></div>`;
}

// Error template
function showSuccess(msg) {
    return `<div class="success"><strong>Success</strong>: ${msg}<br></div>`;
}

function saveAndSendOtp(e = null) {

    if (e) e.preventDefault();

    $('form#loginform').find(':submit').prop('disabled', true).val('Processing');

    let data = {
        'action': 'alpha_sms_to_save_and_send_otp_login', //calls wp_ajax_nopriv_alpha_sms_to_save_and_send_otp_login
        'log': $('form#loginform #user_login').val(),
        'pwd': $('form#loginform #user_pass').val(),
        'rememberme': $('form#loginform #rememberme').val(),
        'alpha_sms': $('form#loginform #alpha_sms').val()
    }

    $.post(alpha_sms_object.ajaxurl, data, function (resp) {

        $('form#loginform').prev('#login_error, .success, .message').remove();

        if (resp.status === 200) {
            $('form#loginform').find(':submit').off('click');
            $('#alpha_sms-otp').fadeIn();
            $(showSuccess(resp.message)).insertBefore('form#loginform');
            timer('resend_otp', 12);
        } else if (resp.status === 402) {
            // no phone number found
            $('form#loginform').find(':submit').off('click');
            $('form#loginform').submit();
        } else {
            // wrong user name pass/sms api error
            $(showError(resp.message)).insertBefore('form#loginform');
        }

    }, 'json').fail(
        () => $(showError('Something went wrong. Please try again later')).insertBefore('form#loginform')
    ).done(
        ()=>  $('form#loginform').find(':submit').prop('disabled', false).val('Log In')
    );

}


function timer(displayID, remaining) {
    let m = Math.floor(remaining / 60);
    let s = remaining % 60;

    m = m < 10 ? '0' + m : m;
    s = s < 10 ? '0' + s : s;
    document.getElementById(displayID).innerHTML = m + ':' + s;
    remaining -= 1;

    if(remaining >= 0) {
        setTimeout(function() {
            timer(displayID, remaining);
        }, 1000);
        return;
    }
    // Do timeout stuff here
    document.getElementById(displayID).innerHTML = `<a href="javascript:saveAndSendOtp()">Resend OTP</a>`
}