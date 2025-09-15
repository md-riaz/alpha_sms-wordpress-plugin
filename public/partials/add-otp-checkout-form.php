<?php
// If this file is called directly, abort.
if (! defined('WPINC')) {
    die;
}
?>
<button type="button" class="button" id="alpha_sms_send_otp"><?php esc_html_e('Send OTP', 'alpha_sms'); ?></button>
<div id="alpha_sms_otp_checkout" style="display:none;">
  <?php
  woocommerce_form_field(
      'otp_code',
      [
          'type'     => 'text',
          'required' => true,
          'label'    => __('OTP Code', 'alpha_sms'),
          'class'    => ['form-row-wide'],
      ],
      ''
  );
  ?>
  <div id="wc_checkout_resend_otp" class="float-right"></div>
</div>
<input type='hidden' name='action_type' id='action_type' value='wc_checkout' />
