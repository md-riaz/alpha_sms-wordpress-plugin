/* For Woocommerce page login and registration code */

window.$ = jQuery;

let form,
   wc_reg_form,
   alert_wrapper,
   checkout_form,
   checkout_otp,
   otp_input,
   otp_input_reg,
   checkout_submit_button,
   checkout_proxy_button;

// fill variables with appropriate selectors and attach event handlers
$(function () {
   alert_wrapper = $('.woocommerce-notices-wrapper').eq(0);

   checkout_otp = $('#alpha_sms_otp_checkout');
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


   initializeCheckoutSubmitProxy();
   $(document.body).on('updated_checkout', initializeCheckoutSubmitProxy);
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

   if (!checkout_form || !checkout_form.length) {
      checkout_otp = $('#alpha_sms_otp_checkout');
      checkout_form = checkout_otp
         .parents('form.checkout.woocommerce-checkout')
         .eq(0);

      if (!checkout_form.length) {
         checkout_form = checkout_otp.closest('form');
      }
   }

   if (!checkout_form || !checkout_form.length) {
      return;
   }

   let phone = checkout_form.find('#billing_phone').val();

   if (
      !phone
   ) {
      checkout_form
         .prev(alert_wrapper)
         .html(showError('Fill in the required fields.'));
      $('html,body').animate({ scrollTop: checkout_form.offset().top }, 'slow');
      return;
   }

   if (checkout_proxy_button && checkout_proxy_button.length) {
      checkout_proxy_button.prop('disabled', true);
      setButtonLabel(checkout_proxy_button, 'Processing');
   }

   let data = {
      action: 'wc_send_otp', //calls wp_ajax_nopriv_wc_send_otp
      billing_phone: checkout_form.find('#billing_phone').val(),
      action_type: checkout_form.find('#action_type').val()
   };

   $.post(
      alpha_sms_object.ajaxurl,
      data,
      function (resp) {
         if (resp.status === 200) {
            if (checkout_proxy_button && checkout_proxy_button.length) {
               checkout_proxy_button.off('click', WC_Checkout_SendOtp).remove();
               checkout_proxy_button = null;
            }

            if (!checkout_submit_button || !checkout_submit_button.length) {
               checkout_submit_button = checkout_form
                  .find('[name="woocommerce_checkout_place_order"][type="submit"]')
                  .last();

               if (!checkout_submit_button.length) {
                  checkout_submit_button = checkout_form
                     .find('button[type="submit"], input[type="submit"]')
                     .last();
               }
            }

            if (checkout_submit_button && checkout_submit_button.length) {
               checkout_submit_button.show();
               checkout_submit_button.prop('disabled', false);
            }
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
      .done(() => {
         $('html,body').animate(
            { scrollTop: checkout_form.offset().top },
            'slow'
         );

         if (checkout_proxy_button && checkout_proxy_button.length) {
            checkout_proxy_button.prop('disabled', false);
            const defaultLabel =
               checkout_proxy_button.data('alphaSmsOriginalLabel') || 'Place Order';
            setButtonLabel(checkout_proxy_button, defaultLabel);
         }
      });
}

function getButtonLabel(button) {
   if (!button || !button.length) {
      return '';
   }

   if (button.is('input')) {
      return button.val();
   }

   return button.html();
}

function setButtonLabel(button, label) {
   if (!button || !button.length) {
      return;
   }

   const safeLabel = label !== undefined && label !== null ? label : '';

   if (button.is('input')) {
      button.val(safeLabel);
      return;
   }

   button.html(safeLabel);
}

function createCheckoutProxyButton(originalButton) {
   if (!originalButton || !originalButton.length) {
      return null;
   }

   let proxyButton;

   if (originalButton.is('input')) {
      proxyButton = $('<input type="button" />');
   } else {
      proxyButton = $('<button type="button"></button>');
   }

   const originalClassAttr = originalButton.attr('class');

   if (originalClassAttr) {
      proxyButton.attr('class', originalClassAttr);
   }

   proxyButton.addClass('alpha-sms-place-order');

   const originalAttributes = originalButton.get(0).attributes;

   for (let i = 0; i < originalAttributes.length; i += 1) {
      const attribute = originalAttributes[i];

      if (!attribute) {
         continue;
      }

      const attributeName = attribute.name;

      if (!attributeName) {
         continue;
      }

      if (
         attributeName === 'id' ||
         attributeName === 'name' ||
         attributeName === 'type' ||
         attributeName === 'class' ||
         attributeName === 'style'
      ) {
         continue;
      }

      if (attributeName === 'value' && !originalButton.is('input')) {
         continue;
      }

      proxyButton.attr(attributeName, attribute.value);
   }

   const defaultLabel = getButtonLabel(originalButton);

   proxyButton.data('alphaSmsOriginalLabel', defaultLabel);
   setButtonLabel(proxyButton, defaultLabel);

   copyComputedStyles(originalButton, proxyButton);

   return proxyButton;
}

function copyComputedStyles(originalButton, proxyButton) {
   if (
      !originalButton ||
      !originalButton.length ||
      !proxyButton ||
      !proxyButton.length
   ) {
      return;
   }

   const originalNode = originalButton.get(0);
   const proxyNode = proxyButton.get(0);

   if (!originalNode || !proxyNode || !window || !window.getComputedStyle) {
      return;
   }

   const computedStyles = window.getComputedStyle(originalNode);

   proxyNode.style.cssText = '';

   for (let i = 0; i < computedStyles.length; i += 1) {
      const propertyName = computedStyles[i];

      if (!propertyName) {
         continue;
      }

      const propertyValue = computedStyles.getPropertyValue(propertyName);

      if (!propertyValue) {
         continue;
      }

      if (propertyName === 'display' && propertyValue === 'none') {
         continue;
      }

      const priority = computedStyles.getPropertyPriority(propertyName);

      proxyNode.style.setProperty(propertyName, propertyValue, priority);
   }
}

function initializeCheckoutSubmitProxy() {
   checkout_otp = $('#alpha_sms_otp_checkout');

   if (!checkout_otp.length) {
      if (checkout_proxy_button && checkout_proxy_button.length) {
         checkout_proxy_button.off('click', WC_Checkout_SendOtp).remove();
      }

      if (checkout_submit_button && checkout_submit_button.length) {
         checkout_submit_button.show();
      }

      checkout_form = null;
      checkout_proxy_button = null;
      checkout_submit_button = null;
      return;
   }

   checkout_form = checkout_otp
      .parents('form.checkout.woocommerce-checkout')
      .eq(0);

   if (!checkout_form.length) {
      checkout_form = checkout_otp.closest('form');
   }

   if (!checkout_form.length) {
      return;
   }

   if (checkout_proxy_button && checkout_proxy_button.length) {
      checkout_proxy_button.off('click', WC_Checkout_SendOtp).remove();
   }

   checkout_form.find('.alpha-sms-place-order').remove();
   checkout_proxy_button = null;

   checkout_submit_button = checkout_form
      .find('[name="woocommerce_checkout_place_order"][type="submit"]')
      .not('.alpha-sms-place-order')
      .last();

   if (!checkout_submit_button.length) {
      checkout_submit_button = checkout_form
         .find('button[type="submit"], input[type="submit"]')
         .not('.alpha-sms-place-order')
         .last();
   }

   if (!checkout_submit_button.length) {
      return;
   }

   checkout_submit_button.show();
   checkout_submit_button.prop('disabled', false);

   checkout_proxy_button = createCheckoutProxyButton(checkout_submit_button);

   if (!checkout_proxy_button || !checkout_proxy_button.length) {
      return;
   }

   const defaultLabel =
      checkout_proxy_button.data('alphaSmsOriginalLabel') || 'Place Order';

   setButtonLabel(checkout_proxy_button, defaultLabel);
   checkout_proxy_button.prop('disabled', false);

   checkout_submit_button.after(checkout_proxy_button);
   checkout_proxy_button.on('click', WC_Checkout_SendOtp);

   checkout_submit_button.hide();
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
