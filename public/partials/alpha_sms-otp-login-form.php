<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://alpha.net.bd
 * @since      1.0.0
 *
 * @package    Alpha_sms
 * @subpackage Alpha_sms/public/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div id="alpha_sms-otp" class="mb-3" style="display:none;">
    <div id="otp_alert"></div>
    <p class="mb-3">
        <label for="alpha_sms-mobile_phone"><?php _e('Phone', $this->plugin_name) ?></label>
            <input type="text" name="mobile_phone" id="alpha_sms-mobile_phone" class="input mb-0" size="25"/>
            <input type="hidden" id="session-login-type"
                   value="<?php echo isset($_SESSION['login_type']) ? esc_html($_SESSION['login_type']) : ''; ?>">
            <input type="hidden" id="ajax-url" value="<?php echo esc_url(admin_url('admin-ajax.php')); ?>">

    </p>

    <div class="alpha_sms-generate-otp">
        <label for="otp_code" style="display:none;">Enter your otp code below</label>
            <input type="number" class="input" id="otp_code" name="otp_code" style="display:none;"/>
            <input type="button" class="button button-primary button-large float-none " id="alpha_sms-generate-otp-btn"
                   value="Send OTP">
    </div>
</div>


<div class="alpha_sms-otp-login-form radio-inputs mb-3 d-flex">
    <label>
        <input type="radio" name="login_type" id="alpha_sms-otp-login-type-username-password"
               onchange="actionLoginTypeValue()" value="username_password" checked="checked"/>
		<?php _e('Username/Password', $this->plugin_name); ?>
    </label>

    <label>
        <input type="radio" name="login_type" id="alpha_sms-otp-login-type-otp" onchange="actionLoginTypeValue()"
               value="otp"/>
		<?php _e('Mobile phone/OTP', $this->plugin_name); ?>
    </label>
</div>