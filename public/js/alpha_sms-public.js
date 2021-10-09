/* For Woocommerce page login and registration code */

window.$ = jQuery;

let form, wc_reg_form, alert_wrapper, checkout_otp, checkout_form;

// fill variables with appropriate selectors and attach event handlers
$(function () {
	form = $('form.woocommerce-form-login.login');
	wc_reg_form = $('form.woocommerce-form.woocommerce-form-register.register');
	alert_wrapper = $('.woocommerce-notices-wrapper');
	checkout_otp = $('#alpha_sms-otp_checkout');
	// Perform AJAX login on form submit
	form.find(':submit').on('click', WP_Login_SendOtp);
	wc_reg_form.find(':submit').on('click', WP_Reg_SendOtp);

	if (checkout_otp.length) {
		checkout_form = $('form.checkout.woocommerce-checkout');
		setTimeout(() => {
			checkout_form.find('#place_order2').on('click', WP_Checkout_SendOtp);
		}, 500)
	}
});

// Error template
function showError(msg) {
	return `<ul class="woocommerce-error" role="alert"><li>${msg}</li></ul></div>`;
}

// Error template
function showSuccess(msg) {
	return `<ul class="woocommerce-message" role="alert" style="border-left: 3px solid #00a32a"><li>${msg}</li></ul></div>`;
}

// ajax send otp for woocommerce login
function WP_Login_SendOtp(e) {

	e.preventDefault();
	alert_wrapper.html('');
	form.find(':submit').prop('disabled', true).val('Processing').text('Processing');

	let data = {
		'action': 'alpha_sms_to_save_and_send_otp_login', //calls wp_ajax_nopriv_alpha_sms_to_save_and_send_otp_login
		'log': form.find('#username').val(),
		'pwd': form.find('#password').val(),
		'rememberme': form.find('#rememberme').val(),
		'alpha_sms': form.find('#alpha_sms').val()
	}

	$.post(alpha_sms_object.ajaxurl, data, function (resp) {
		if (resp.status === 200) {
			form.find(':submit').off('click');
			$('#alpha_sms-otp').fadeIn();
			alert_wrapper.html(showSuccess(resp.message));
			timer('resend_otp', 120, `<a href="javascript:WP_Login_SendOtp()">Resend OTP</a>`);
		} else if (resp.status === 402) {
			// no phone number found
			form.find(':submit').off('click');
			form.submit();
		} else {
			// wrong user name pass/sms api error
			alert_wrapper.html(showError(resp.message));
		}

	}, 'json').fail(
		() => alert_wrapper.html(showError(showError('Something went wrong. Please try again later')))
	).done(
		()=>  form.find(':submit').prop('disabled', false).val('Log In').text('Log In')
	);

}

// ajax send otp for woocommerce registration
function WP_Reg_SendOtp(e){
	if (e) e.preventDefault();

	alert_wrapper.html('');
	wc_reg_form.find(':submit').prop('disabled', true).val('Processing').text('Processing');

	let data = {
		'action': 'wc_send_otp', //calls wp_ajax_nopriv_wc_send_otp
		'billing_phone' : wc_reg_form.find('#reg_billing_phone').val(),
		'email' : wc_reg_form.find('#reg_email').val(),
		'password' : wc_reg_form.find('#reg_password').val(),
	}

	$.post(alpha_sms_object.ajaxurl, data, function (resp) {

		if (resp.status === 200) {
			wc_reg_form.find(':submit').off('click');
			$('#alpha_sms_otp_reg').fadeIn();
			alert_wrapper.html(showSuccess(resp.message));
			timer('wc_resend_otp', 12, `<a href="javascript:WP_Reg_SendOtp()">Resend OTP</a>`);
		} else {
			// wrong user name pass/sms api error
			alert_wrapper.html(showError(resp.message));
		}

	}, 'json').fail(
		() => alert_wrapper.html(showError(showError('Something went wrong. Please try again later')))
	).done(
		()=>  wc_reg_form.find(':submit').prop('disabled', false).val('Register').text('Register')
	);
}

// ajax send otp if checkout account creation is enabled
function WP_Checkout_SendOtp(e){
	if (e) e.preventDefault();
	alert_wrapper.html('');
	console.log(checkout_form.find('#place_order2'))
	checkout_form.find('#place_order2').prop('disabled', true).val('Processing').text('Processing');

	let data = {
		'action': 'wc_send_otp', //calls wp_ajax_nopriv_wc_send_otp
		'billing_phone' : checkout_form.find('#billing_phone').val(),
		'email' : checkout_form.find('#billing_email').val()
	}

	$.post(alpha_sms_object.ajaxurl, data, function (resp) {

		if (resp.status === 200) {
			checkout_form.find('#place_order2').remove();
			checkout_form.find('#place_order').show();
			$('#alpha_sms-otp_checkout').fadeIn();
			alert_wrapper.html(showSuccess(resp.message));
			timer('wc_checkout_resend_otp', 12, `<a href="javascript:WP_Checkout_SendOtp()">Resend OTP</a>`);
		} else {
			// wrong user name pass/sms api error
			alert_wrapper.html(showError(resp.message));
		}

	}, 'json').fail(
		() => alert_wrapper.html(showError(showError('Something went wrong. Please try again later')))
	).done(
		()=>  checkout_form.find(':submit').prop('disabled', false).val('Place order').text('Place order')
	);
}


function timer(displayID, remaining, timeoutEl = '') {
	let m = Math.floor(remaining / 60);
	let s = remaining % 60;

	m = m < 10 ? '0' + m : m;
	s = s < 10 ? '0' + s : s;
	document.getElementById(displayID).innerHTML = m + ':' + s;
	remaining -= 1;

	if(remaining >= 0) {
		setTimeout(function() {
			timer(displayID, remaining, timeoutEl);
		}, 1000);
		return;
	}
	// Do timeout stuff here
	document.getElementById(displayID).innerHTML = timeoutEl
}