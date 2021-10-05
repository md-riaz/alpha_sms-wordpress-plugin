window.$ = jQuery;


let otp_send_btn = $('#alpha_sms-generate-otp-btn');

$(function () {
    localStorage['login_stage'] = 'initial';
    let session_login_type = $('#session-login-type').val();
    let value;

    if (session_login_type !== '') {
        value = session_login_type;
    } else {
        value = 'username_password';
    }

    $("input[name='login_type'][value=" + value + "]").prop('checked', true);

    otp_send_btn.on('click', saveAndSendOtp);
});

function saveAndSendOtp() {

    otp_send_btn.prop('disabled', true);

    localStorage['login_stage'] = "initial";

    const mobile_phone = $('#alpha_sms-mobile_phone').val();
    const ajaxurl = $('#ajax-url').val();

    let data = {
        'action': 'alpha_sms_to_save_and_send_otp_login',
        'post_type': 'POST',
        'mobile_phone': mobile_phone,
        'name': 'Save and Send OTP'
    };

    $.post(ajaxurl, data, function (response) {
        if (response['status'] == '200'){

        }
    }, 'json')

}