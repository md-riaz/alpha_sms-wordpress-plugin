<?php
// If this file is called directly, abort.
if (! defined('WPINC')) {
    die;
}
?>
<div class="alpha-sms-checkout-actions woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <button type="button" class="button alt alpha-sms-send-otp-button wp-element-button" id="alpha_sms_send_otp">
                <?php esc_html_e('Send OTP', 'alpha_sms'); ?>
        </button>
        <div id="wc_checkout_resend_otp" class="alpha-sms-resend"></div>
</div>
<div id="alpha_sms_otp_checkout" class="alpha-sms-otp-field" style="display:none;">
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
</div>
<input type='hidden' name='action_type' id='action_type' value='wc_checkout' />
