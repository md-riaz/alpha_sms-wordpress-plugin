/* For Woocommerce page login and registration code */

window.$ = jQuery;

let form, wc_reg_form, alert_wrapper, checkout_otp, checkout_form;

// fill variables with appropriate selectors and attach event handlers
$(function () {
   form = $('form.woocommerce-form-login.login').eq(0);
   wc_reg_form = $('form.woocommerce-form.woocommerce-form-register.register').eq(0);
   alert_wrapper = $('.woocommerce-notices-wrapper').eq(0);
   checkout_otp = $('#alpha_sms_otp_checkout');
   // Perform AJAX login on form submit
   if ($('#alpha_sms_otp').length) {
      form.find(':submit').on('click', WC_Login_SendOtp);
   }

   if ($('#alpha_sms_otp_reg').length) {
      wc_reg_form.find(':submit').on('click', WC_Reg_SendOtp);
   }


   if (checkout_otp.length) {
      checkout_form = $('form.checkout.woocommerce-checkout');
      $(document).on('click', '#place_order2', WC_Checkout_SendOtp);
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
function WC_Login_SendOtp(e) {
   if (e) e.preventDefault();
   alert_wrapper.html('');

   let username = form.find('#username').val();
   let password = form.find('#password').val();

   if (!username || !password) {
      alert_wrapper.html(showError('Fill in the required fields.'));
      $('html,body').animate({ scrollTop: 0 }, 'slow');
      return;
   }

   form
      .find(':submit')
      .prop('disabled', true)
      .val('Processing')
      .text('Processing');

   let data = {
      action: 'alpha_sms_to_save_and_send_otp_login', //calls wp_ajax_nopriv_alpha_sms_to_save_and_send_otp_login
      log: form.find('#username').val(),
      pwd: form.find('#password').val(),
      rememberme: form.find('#rememberme').val(),
      alpha_sms: form.find('#alpha_sms').val(),
   };

   $.post(
      alpha_sms_object.ajaxurl,
      data,
      function (resp) {
         if (resp.status === 200) {
            form.find(':submit').off('click');
            $('#alpha_sms_otp').fadeIn().prevAll().hide();
            alert_wrapper.html(showSuccess(resp.message));
            timer(
               'resend_otp',
               120,
               `<a href="javascript:WC_Login_SendOtp()">Resend OTP</a>`
            );
         } else if (resp.status === 402) {
            // no phone number found
            form.find(':submit').off('click');
            form.find(':submit').prop('disabled', false).val('Log In').trigger('click');

         } else {
            // wrong user name pass/sms api error
            alert_wrapper.html(showError(resp.message));
         }
      },
      'json'
   )
      .fail(() =>
         alert_wrapper.html(
            showError('Something went wrong. Please try again later')
         )
      )
      .done(() =>
         form
            .find(':submit')
            .prop('disabled', false)
            .val('Log In')
            .text('Log In')
      );
}

// ajax send otp for woocommerce registration
function WC_Reg_SendOtp(e) {
   if (e) e.preventDefault();
   alert_wrapper.html('');

   let phone = wc_reg_form.find('#reg_billing_phone').val();
   let email = wc_reg_form.find('#reg_email').val();
   let password = wc_reg_form.find('#reg_password').val();

   if (!phone || !email) {
      alert_wrapper.html(showError('Fill in the required fields.'));
      $('html,body').animate({ scrollTop: 0 }, 'slow');
      return;
   }

   wc_reg_form
      .find(':submit')
      .prop('disabled', true)
      .val('Processing')
      .text('Processing');

   let data = {
      action: 'wc_send_otp', //calls wp_ajax_nopriv_wc_send_otp
      billing_phone: phone,
      email: email
   };

   if (password) {
      data.password = password;
   }

   $.post(
      alpha_sms_object.ajaxurl,
      data,
      function (resp) {
         if (resp.status === 200) {
            wc_reg_form.find(':submit').off('click');
            $('#alpha_sms_otp_reg').fadeIn().prevAll().hide();
            alert_wrapper.html(showSuccess(resp.message));
            timer(
               'wc_resend_otp',
               120,
               `<a href="javascript:WC_Reg_SendOtp()">Resend OTP</a>`
            );
         } else {
            // wrong user name pass/sms api error
            alert_wrapper.html(showError(resp.message));
         }
      },
      'json'
   )
      .fail(() =>
         alert_wrapper.html(
            showError(showError('Something went wrong. Please try again later'))
         )
      )
      .done(() =>
         wc_reg_form
            .find(':submit')
            .prop('disabled', false)
            .val('Register')
            .text('Register')
      );
}

// ajax send otp if checkout account creation is enabled
function WC_Checkout_SendOtp(e) {
   if (e) e.preventDefault();
   alert_wrapper.html('');

   let firstName = checkout_form.find('#billing_first_name').val();
   let lastName = checkout_form.find('#billing_last_name').val();
   let country = checkout_form.find('#billing_country').val();
   let address = checkout_form.find('#billing_address_1').val();
   let city = checkout_form.find('#billing_city').val();
   let state = checkout_form.find('#billing_state').val();
   let phone = checkout_form.find('#billing_phone').val();
   let email = checkout_form.find('#billing_email').val();
   let password = checkout_form.find('#account_password').val();

   if (
      !firstName ||
      !lastName ||
      !country ||
      !address ||
      !city ||
      !state ||
      !phone ||
      !email ||
      !password
   ) {
      checkout_form
         .prev(alert_wrapper)
         .html(showError('Fill in the required fields.'));
      $('html,body').animate({ scrollTop: checkout_form.offset().top }, 'slow');
      return;
   }

   checkout_form
      .find('#place_order2')
      .prop('disabled', true)
      .val('Processing')
      .text('Processing');

   let data = {
      action: 'wc_send_otp', //calls wp_ajax_nopriv_wc_send_otp
      billing_phone: checkout_form.find('#billing_phone').val(),
      email: checkout_form.find('#billing_email').val(),
      action_type: checkout_form.find('#action_type').val()
   };

   $.post(
      alpha_sms_object.ajaxurl,
      data,
      function (resp) {
         if (resp.status === 200) {
            checkout_form.find('#place_order2').remove();
            checkout_form.find('#place_order').show();
            $('#alpha_sms_otp_checkout').fadeIn();
            checkout_form.prev(alert_wrapper).html(showSuccess(resp.message));
            timer(
               'wc_checkout_resend_otp',
               120,
               `<a href="javascript:WC_Checkout_SendOtp()">Resend OTP</a>`
            );
         } else {
            // wrong user name pass/sms api error
            checkout_form.prev(alert_wrapper).html(showError(resp.message));
         }
      },
      'json'
   )
      .fail(() =>
         checkout_form
            .prev(alert_wrapper)
            .html(
               showError(
                  showError('Something went wrong. Please try again later')
               )
            )
      )
      .done(() =>
         $('html,body').animate(
            { scrollTop: checkout_form.offset().top },
            'slow'
         ) && checkout_form
              .find('#place_order2')
              .prop('disabled', false)
              .val('Place Order')
              .text('Place Order')
      );
}

function timer(displayID, remaining, timeoutEl = '') {
   let m = Math.floor(remaining / 60);
   let s = remaining % 60;

   m = m < 10 ? '0' + m : m;
   s = s < 10 ? '0' + s : s;
   document.getElementById(displayID).innerHTML = m + ':' + s;
   remaining -= 1;

   if (remaining >= 0) {
      setTimeout(function () {
         timer(displayID, remaining, timeoutEl);
      }, 1000);
      return;
   }
   // Do timeout stuff here
   document.getElementById(displayID).innerHTML = timeoutEl;
}
