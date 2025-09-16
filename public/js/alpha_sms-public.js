/* For Woocommerce page login and registration code */

window.$ = jQuery;

let form, wc_reg_form, alert_wrapper, otp_input, otp_input_reg;

// fill variables with appropriate selectors and attach event handlers
$(function () {
   updateAlertWrapper($(document.body));

   otp_input = $('#alpha_sms_otp');
   otp_input_reg = $('#alpha_sms_otp_reg');

   // Perform AJAX login on form submit
   if (otp_input.length) {
      form = otp_input.parent('form.woocommerce-form-login.login');
      form.find(':submit').on('click', WC_Login_SendOtp);
   }

   if (otp_input_reg.length) {
      wc_reg_form = otp_input_reg.parent('form.woocommerce-form-register.register');
      wc_reg_form.find(':submit').on('click', WC_Reg_SendOtp);
   }
});

$(document).on('click', '#alpha_sms_send_otp', WC_Checkout_SendOtp);
$(document.body).on('updated_checkout', function () {
   updateAlertWrapper($('.woocommerce-checkout'));
});

function updateAlertWrapper(context) {
   alert_wrapper = $('.woocommerce-notices-wrapper').eq(0);

   if (!alert_wrapper.length) {
      alert_wrapper = $('.woocommerce-NoticeGroup').eq(0);
   }

   const hasContext = context && context.length;

   if ((!alert_wrapper || !alert_wrapper.length) && hasContext) {
      alert_wrapper = context.find('.woocommerce-notices-wrapper').eq(0);

      if (!alert_wrapper.length && context[0] !== document.body) {
         alert_wrapper = $('<div class="woocommerce-notices-wrapper"></div>');
         context.prepend(alert_wrapper);
      }
   }

   return alert_wrapper;
}

function getText(key, fallback) {
   if (typeof alpha_sms_object !== 'undefined' && alpha_sms_object[key]) {
      return alpha_sms_object[key];
   }

   return fallback;
}

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
   updateAlertWrapper(form);
   alert_wrapper.html('');

   let username = form.find('#username').val();
   let password = form.find('#password').val();

   if (!username || !password) {
      alert_wrapper.html(showError(getText('i18n_fill_required', 'Fill in the required fields.')));
      $('html,body').animate({ scrollTop: 0 }, 'slow');
      return;
   }

   const processingText = getText('i18n_processing', 'Processing');

   form
      .find(':submit')
      .prop('disabled', true)
      .val(processingText)
      .text(processingText);

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
            showError(getText('i18n_generic_error', 'Something went wrong. Please try again later'))
         )
      )
      .always(() =>
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
   updateAlertWrapper(wc_reg_form);
   alert_wrapper.html('');

   let phone = wc_reg_form.find('#reg_billing_phone').val();
   let email = wc_reg_form.find('#reg_email').val();
   let password = wc_reg_form.find('#reg_password').val();

   if (!phone || !email) {
      alert_wrapper.html(showError(getText('i18n_fill_required', 'Fill in the required fields.')));
      $('html,body').animate({ scrollTop: 0 }, 'slow');
      return;
   }

   const processingText = getText('i18n_processing', 'Processing');

   wc_reg_form
      .find(':submit')
      .prop('disabled', true)
      .val(processingText)
      .text(processingText);

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
            showError(getText('i18n_generic_error', 'Something went wrong. Please try again later'))
         )
      )
      .always(() =>
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

   const $button = $('#alpha_sms_send_otp');

   if (!$button.length) {
      return;
   }
   const $form = $button.closest('form.checkout, form.woocommerce-checkout');

   if (!$form.length) {
      return;
   }

   updateAlertWrapper($form);
   alert_wrapper.html('');

   const phoneSelector = alpha_sms_object.phone_selector || '#billing_phone';
   const phone = ($form.find(phoneSelector).val() || '').trim();

   if (!phone) {
      alert_wrapper.html(showError(getText('i18n_fill_required', 'Fill in the required fields.')));
      $('html,body').animate({ scrollTop: $form.offset().top }, 'slow');
      return;
   }

   const processingText = getText('i18n_processing', 'Processing');

   $button
      .prop('disabled', true)
      .addClass('alpha-sms-button--loading')
      .text(processingText);

   const data = {
      billing_phone: phone,
      action_type: $form.find('#action_type').val()
   };

   let requestUrl;

   if (typeof wc_checkout_params !== 'undefined' && wc_checkout_params.wc_ajax_url) {
      requestUrl = wc_checkout_params.wc_ajax_url.replace(
         '%%endpoint%%',
         'wc_send_otp'
      );
   } else {
      requestUrl = alpha_sms_object.ajaxurl;
      data.action = 'wc_send_otp';
   }

   $.post(
      requestUrl,
      data,
      function (resp) {
         if (resp.status === 200) {
            const $otpField = $form.find('#alpha_sms_otp_checkout');
            const resendText = getText('i18n_resend', 'Resend OTP');

            $otpField.stop(true, true).slideDown();
            alert_wrapper.html(showSuccess(resp.message));
            timer(
               'wc_checkout_resend_otp',
               120,
               `<a href="javascript:WC_Checkout_SendOtp()">${resendText}</a>`
            );
         } else {
            alert_wrapper.html(
               showError(
                  resp.message || getText('i18n_generic_error', 'Something went wrong. Please try again later')
               )
            );
         }
      },
      'json'
   )
      .fail(() =>
         alert_wrapper.html(
            showError(getText('i18n_generic_error', 'Something went wrong. Please try again later'))
         )
      )
      .always(() => {
         const sendText = getText('i18n_send_otp', 'Send OTP');

         $('html,body').animate({ scrollTop: $form.offset().top }, 'slow');
         $button
            .prop('disabled', false)
            .removeClass('alpha-sms-button--loading')
            .text(sendText);
      });
}

function timer(displayID, remaining, timeoutEl = '') {
   const container = document.getElementById(displayID);

   if (!container) {
      return;
   }

   let m = Math.floor(remaining / 60);
   let s = remaining % 60;

   m = m < 10 ? '0' + m : m;
   s = s < 10 ? '0' + s : s;
   container.innerHTML = m + ':' + s;
   remaining -= 1;

   if (remaining >= 0) {
      setTimeout(function () {
         timer(displayID, remaining, timeoutEl);
      }, 1000);
      return;
   }
   // Do timeout stuff here
   container.innerHTML = timeoutEl;
}
