window.$ = jQuery;

$(function () {

    let session_login_type = $('#session-login-type').val();
    let value;

    if (session_login_type !== '') {
        value = session_login_type;
    } else {
        value = 'username_password';
    }

    $("input[name='login_type'][value=" + value + "]").prop('checked', true);

    $('#alpha_sms-generate-otp-btn').on('click', saveAndSendOtp);
    actionLoginTypeValue();
});

// toggle between username/password field and phone otp field
function actionLoginTypeValue() {
    let radioValue = $("input[name='login_type']:checked").val();

    if (radioValue === 'otp') {
        $('form#loginform > p:first-child, form#loginform > .user-pass-wrap').hide();
        $('#alpha_sms-otp').show();

    } else if (radioValue === 'username_password') {
        $('form#loginform > p:first-child, form#loginform > .user-pass-wrap').show();
        $('#alpha_sms-otp').hide();

    }
}

function saveAndSendOtp() {
    $('#otp_alert').html('');
    $('#alpha_sms-generate-otp-btn').prop('disabled', true);

    const mobile_phone = $('#alpha_sms-mobile_phone').val();
    const ajaxurl = $('#ajax-url').val();

    let data = {
        'action': 'alpha_sms_to_save_and_send_otp_login',
        'post_type': 'POST',
        'mobile_phone': mobile_phone,
        'name': 'Save and Send OTP'
    };

    $.post(ajaxurl, data, function (response) {

        if (response['status'] === '200') {
            //button of Send
            $('#alpha_sms-generate-otp-btn').prop('value', 'Re-Send');

            $('#otp_alert').html("<p>"+response['message']+"</p>").css('color', '#3c763d');
            $('.alpha_sms-generate-otp label, .alpha_sms-generate-otp #otp_code').slideDown();

        }  else {
            $('#otp_alert').html("<p>"+response['message']+"</p>").css('color', '#a94442');
        }

        $('#alpha_sms-generate-otp-btn').prop('disabled', false)
    }, 'json')

}