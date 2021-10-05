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
<div id="alpha_sms-otp">
    <p>
        <label for="alpha_sms-mobile_phone"><?php _e('Phone', $this->plugin_name) ?><br/>
            <input type="text" name="mobile_phone" id="alpha_sms-mobile_phone" class="input" size="25"/>
            <input type="hidden" id="session-login-type" value="<?php echo isset($_SESSION['login_type']) ? esc_html($_SESSION['login_type']) : ''; ?>">
            <input type="hidden" id="ajax-url" value="<?php echo esc_url(admin_url('admin-ajax.php')); ?>">
        </label>
    </p>

    <div class="alpha_sms-generate-otp">
        <input type="button" class="button button-primary button-large float-none " id="alpha_sms-generate-otp-btn" value="Send">
    </div>

    <div class="alpha_sms-otp-login-form radio-inputs my-3 d-flex justify-content-around">
        <label>
            <input type="radio" name="login_type" id="alpha_sms-otp-login-type-username-password" onchange="actionLoginTypeValue()" value="username_password" checked="checked"/>
		    <?php _e('Username/Password', $this->plugin_name); ?>
        </label>

        <label>
            <input type="radio" name="login_type" id="alpha_sms-otp-login-type-otp" onchange="actionLoginTypeValue()" value="otp"/>
		    <?php _e('Mobile phone/OTP', $this->plugin_name); ?>
        </label>
    </div>
</div>