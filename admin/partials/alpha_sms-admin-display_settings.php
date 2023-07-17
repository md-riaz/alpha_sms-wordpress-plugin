<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://alpha.net.bd
 * @since      1.0.0
 *
 * @package    Alpha_sms
 * @subpackage Alpha_sms/admin/partials
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

$has_woocommerce = is_plugin_active('woocommerce/woocommerce.php');
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
    <h2><span class="dashicons dashicons-admin-tools"></span> Alpha SMS
        <?php esc_attr_e('Options', $this->plugin_name); ?></h2>
    <p>Here you can set all the options for using the API</p>

    <!--   show admin notice when settings are saved-->
    <?php settings_errors(); ?>

    <form method="post" name="<?php echo esc_attr($this->plugin_name); ?>" action="options.php" id="<?php echo esc_attr($this->plugin_name); ?>">
        <?php
        $order_alerts =
            [
                "DEFAULT_ORDER_STATUS_PENDING_SMS" => __(
                    "[store_name] - Payment required for Order #[order_id]\nYour order #[order_id] at [store_name] is currently pending payment. Please complete payment as soon as possible.",
                    $this->plugin_name
                ),
                "DEFAULT_ORDER_STATUS_PROCESSING_SMS" => __(
                    "[store_name] - Order #[order_id] is being processed\nYour order #[order_id] at [store_name] is currently being processed.",
                    $this->plugin_name
                ),
                "DEFAULT_ORDER_STATUS_ON_HOLD_SMS" => __(
                    "[store_name] - Order #[order_id] is on hold\nYour order #[order_id] at [store_name] is currently on hold. Our customer service team will be reaching out to you shortly.",
                    $this->plugin_name
                ),
                "DEFAULT_ORDER_STATUS_COMPLETED_SMS" => __(
                    "[store_name] - Order #[order_id] has been completed\nYour order #[order_id] at [store_name] has been completed and is on its way to you.",
                    $this->plugin_name
                ),
                "DEFAULT_ORDER_STATUS_CANCELLED_SMS" => __(
                    "[store_name] - Order #[order_id] has been cancelled\nYour order #[order_id] at [store_name] has been cancelled. Please contact our customer service team for any questions or concerns.",
                    $this->plugin_name
                ),
                "DEFAULT_ORDER_STATUS_REFUNDED_SMS" => __(
                    "[store_name] - Order #[order_id] has been refunded\nYour order #[order_id] at [store_name] has been refunded. Please contact our customer service team for any questions or concerns.",
                    $this->plugin_name
                ),
                "DEFAULT_ORDER_STATUS_FAILED_SMS" => __(
                    "[store_name] - Order #[order_id] has failed\nYour order #[order_id] at [store_name] has failed. Please contact our customer service team for any questions or concerns.",
                    $this->plugin_name
                ),
                "DEFAULT_ADMIN_STATUS_SMS" => __(
                    "[store_name] - A new order #[order_id] for value [order_currency] [order_amount] has just been placed. Please check your admin dashboard for complete details.",
                    $this->plugin_name
                )

            ];

        //Grab all options
        $options = get_option($this->plugin_name);

        $api_key = (isset($options['api_key']) && !empty($options['api_key'])) ? $options['api_key'] : '';

        if (strlen($api_key) === 40) {
            $api_key = substr_replace(esc_attr($options['api_key']), str_repeat('*', 24), 12, 16);
        }

        $sender_id = (isset($options['sender_id']) && !empty($options['sender_id'])) ? esc_attr($options['sender_id']) : '';

        $wp_reg = (isset($options['wp_reg']) && !empty($options['wp_reg'])) ? 1 : 0;
        $wp_login = (isset($options['wp_login']) && !empty($options['wp_login'])) ? 1 : 0;
        $wc_reg = (isset($options['wc_reg']) && !empty($options['wc_reg'])) ? 1 : 0;
        $wc_login = (isset($options['wc_login']) && !empty($options['wc_login'])) ? 1 : 0;
        $otp_checkout = (isset($options['otp_checkout']) && !empty($options['otp_checkout'])) ? 1 : 0;
        $admin_phones = (isset($options['admin_phones']) && !empty($options['admin_phones'])) ? esc_attr($options['admin_phones']) : '';


        $order_status_pending             = (isset($options['order_status_pending']) && !empty($options['order_status_pending'])) ? 1 : 0;
        $order_status_pending_sms         = (isset($options['ORDER_STATUS_PENDING_SMS']) && !empty($options['ORDER_STATUS_PENDING_SMS'])) ? $options['ORDER_STATUS_PENDING_SMS'] : $order_alerts['DEFAULT_ORDER_STATUS_PENDING_SMS'];
        $order_status_processing          = (isset($options['order_status_processing']) && !empty($options['order_status_processing'])) ? 1 : 0;
        $order_status_processing_sms      = (isset($options['ORDER_STATUS_PROCESSING_SMS']) && !empty($options['ORDER_STATUS_PROCESSING_SMS'])) ? $options['ORDER_STATUS_PROCESSING_SMS'] : $order_alerts['DEFAULT_ORDER_STATUS_PROCESSING_SMS'];
        $order_status_on_hold             = (isset($options['order_status_on_hold']) && !empty($options['order_status_on_hold'])) ? 1 : 0;
        $order_status_on_hold_sms         = (isset($options['ORDER_STATUS_ON_HOLD_SMS']) && !empty($options['ORDER_STATUS_ON_HOLD_SMS'])) ? $options['ORDER_STATUS_ON_HOLD_SMS'] : $order_alerts['DEFAULT_ORDER_STATUS_ON_HOLD_SMS'];
        $order_status_completed           = (isset($options['order_status_completed']) && !empty($options['order_status_completed'])) ? 1 : 0;
        $order_status_completed_sms       = (isset($options['ORDER_STATUS_COMPLETED_SMS']) && !empty($options['ORDER_STATUS_COMPLETED_SMS'])) ? $options['ORDER_STATUS_COMPLETED_SMS'] : $order_alerts['DEFAULT_ORDER_STATUS_COMPLETED_SMS'];
        $order_status_cancelled           = (isset($options['order_status_cancelled']) && !empty($options['order_status_cancelled'])) ? 1 : 0;
        $order_status_cancelled_sms       = (isset($options['ORDER_STATUS_CANCELLED_SMS']) && !empty($options['ORDER_STATUS_CANCELLED_SMS'])) ? $options['ORDER_STATUS_CANCELLED_SMS'] : $order_alerts['DEFAULT_ORDER_STATUS_CANCELLED_SMS'];
        $order_status_refunded            = (isset($options['order_status_refunded']) && !empty($options['order_status_refunded'])) ? 1 : 0;
        $order_status_refunded_sms        = (isset($options['ORDER_STATUS_REFUNDED_SMS']) && !empty($options['ORDER_STATUS_REFUNDED_SMS'])) ? $options['ORDER_STATUS_REFUNDED_SMS'] : $order_alerts['DEFAULT_ORDER_STATUS_REFUNDED_SMS'];
        $order_status_failed              = (isset($options['order_status_failed']) && !empty($options['order_status_failed'])) ? 1 : 0;
        $order_status_failed_sms          = (isset($options['ORDER_STATUS_FAILED_SMS']) && !empty($options['ORDER_STATUS_FAILED_SMS'])) ? $options['ORDER_STATUS_FAILED_SMS'] : $order_alerts['DEFAULT_ORDER_STATUS_FAILED_SMS'];
        $order_status_admin               = (isset($options['order_status_admin']) && !empty($options['order_status_admin'])) ? 1 : 0;
        $admin_status_sms                 = (isset($options['ADMIN_STATUS_SMS']) && !empty($options['ADMIN_STATUS_SMS'])) ? $options['ADMIN_STATUS_SMS'] : $order_alerts['DEFAULT_ADMIN_STATUS_SMS'];


        if (!empty($api_key)) {

            require_once ALPHA_SMS_PATH . 'includes/sms.class.php';

            $smsPortal = new AlphaSMS($options['api_key']);

            $response = $smsPortal->getBalance();

            if ($response && $response->error === 0) {
                $balance = $response->data->balance;
            } elseif ($response && $response->error === 405) {
                $balance = 'Authentication Failed. Please enter a valid API Key.';
            } else {
                $balance = 'Unknown Error, failed to fetch balance.';
            }
        } else {
            $balance = "empty";
        }

        settings_fields($this->plugin_name);
        do_settings_sections($this->plugin_name);
        ?>

        <!-- API Key -->
        <table class="form-table" aria-label="admin settings form">
            <tr>
                <th scope="row">
                    <label for="<?php echo esc_attr($this->plugin_name . '-api_key'); ?>">
                        <?php esc_attr_e('API Key', $this->plugin_name); ?>
                    </label>
                </th>
                <td>
                    <input id="<?php echo esc_attr($this->plugin_name . '-api_key'); ?>" name="<?php echo esc_attr($this->plugin_name . '[api_key]'); ?>" type="text" size="55" placeholder="Enter API Key" value="<?php if (!empty($api_key)) {
                                                                                                                                                                                                                            echo esc_attr($api_key);
                                                                                                                                                                                                                        } ?>" />
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="<?php echo esc_attr($this->plugin_name . '-sender_id'); ?>">
                        <?php esc_attr_e('Sender ID (Optional)', $this->plugin_name); ?>
                    </label>
                </th>
                <td>
                    <input id="<?php echo esc_attr($this->plugin_name . '-sender_id'); ?>" name="<?php echo esc_attr($this->plugin_name . '[sender_id]'); ?>" type="text" size="55" value="<?php esc_attr_e($sender_id, $this->plugin_name); ?>" />
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="<?php echo esc_attr($this->plugin_name . '-balance'); ?>"></label>
                </th>
                <td>
                    <span id="<?php echo esc_attr($this->plugin_name . '-balance'); ?>">
                        <?php if ($balance === 'empty') : ?>
                            <strong>Don't have an account? <a href='https://alpha.net.bd/SMS/SignUp/'>Register Now</a> (Free
                                SMS Credit after Sign-up).</strong>
                        <?php elseif (is_numeric($balance)) : ?>
                            <strong>Balance:</strong> BDT
                            <?php echo esc_html(number_format((float)$balance, 2, '.', ',')) ?>
                        <?php else : ?>
                            <strong class="text-danger"><?php echo esc_html($balance); ?></strong>
                        <?php endif; ?>
                    </span>
                </td>
            </tr>
        </table>

        <hr>

        <h3><?php esc_attr_e('WordPress', $this->plugin_name); ?></h3>
        <ol class="switches">
            <li>
                <input type="checkbox" id="<?php echo esc_attr($this->plugin_name . '-wp_reg'); ?>" name="<?php echo esc_attr($this->plugin_name . '[wp_reg]'); ?>" <?php checked($wp_reg, 1); ?> />
                <label for="<?php echo esc_attr($this->plugin_name . '-wp_reg'); ?>">
                    <span class="toggle_btn"></span>
                    <span><?php esc_attr_e('Two Factor OTP Verification For WordPress Register Form', $this->plugin_name); ?></span>
                </label>
            </li>

            <li>
                <input type="checkbox" id="<?php echo esc_attr($this->plugin_name . '-wp_login'); ?>" name="<?php echo esc_attr($this->plugin_name . '[wp_login]'); ?>" <?php checked($wp_login, 1); ?> />
                <label for="<?php echo esc_attr($this->plugin_name . '-wp_login'); ?>">
                    <span class="toggle_btn"></span>
                    <span><?php esc_attr_e('Two Factor OTP Verification For WordPress Login Form', $this->plugin_name); ?></span>
                </label>
            </li>

        </ol>


        <?php
        if ($has_woocommerce) { ?>
            <h3><?php esc_attr_e('Woocommerce', $this->plugin_name); ?></h3>

            <ol class="switches">
                <li>
                    <input type="checkbox" id="<?php echo esc_attr($this->plugin_name . '-wc_reg'); ?>" name="<?php echo esc_attr($this->plugin_name . '[wc_reg]'); ?>" <?php checked($wc_reg, 1); ?> />
                    <label for="<?php echo esc_attr($this->plugin_name . '-wc_reg'); ?>">
                        <span class="toggle_btn"></span>
                        <span><?php esc_attr_e('Two Factor OTP Verification For Woocommerce Register Form', $this->plugin_name); ?></span>
                    </label>
                </li>

                <li>
                    <input type="checkbox" id="<?php echo esc_attr($this->plugin_name . '-wc_login'); ?>" name="<?php echo esc_attr($this->plugin_name . '[wc_login]'); ?>" <?php checked($wc_login, 1); ?> />
                    <label for="<?php echo esc_attr($this->plugin_name . '-wc_login'); ?>">
                        <span class="toggle_btn"></span>
                        <span><?php esc_attr_e('Two Factor OTP Verification For Woocommerce Login Form', $this->plugin_name); ?></span>
                    </label>
                </li>

                <li>
                    <input type="checkbox" id="<?php echo esc_attr($this->plugin_name . '-otp_checkout'); ?>" name="<?php echo esc_attr($this->plugin_name . '[otp_checkout]'); ?>" <?php checked($otp_checkout, 1); ?> />
                    <label for="<?php echo esc_attr($this->plugin_name . '-otp_checkout'); ?>">
                        <span class="toggle_btn"></span>
                        <span><?php esc_attr_e('OTP Verification For Guest Customer Checkout', $this->plugin_name); ?></span>
                    </label>
                </li>

                <li>
                    <input class="alpha-collapse" type="checkbox" id="<?php echo esc_attr($this->plugin_name . '-order_status_admin'); ?>" name="<?php echo esc_attr($this->plugin_name . '[order_status_admin]'); ?>" <?php checked(
                                                                                                                                                                                                                                $order_status_admin,
                                                                                                                                                                                                                                1
                                                                                                                                                                                                                            ); ?> />
                    <label for="<?php echo esc_attr($this->plugin_name . '-order_status_admin'); ?>">
                        <span class="toggle_btn"></span>
                        <span><?php esc_attr_e('Notify Admin on New Order', $this->plugin_name); ?></span>
                    </label>
                    <div class="alpha-collapsable" id="order_status_admin">
                        <fieldset class="notify_template">
                            <legend>
                                <h4 class="mb-2">
                                    <label for="<?php echo esc_attr($this->plugin_name . '-admin_phones'); ?>">
                                        <?php esc_attr_e(
                                            'Admin Phone Numbers (comma separated)',
                                            $this->plugin_name
                                        ); ?>
                                    </label>
                                </h4>
                                <input id="<?php echo esc_attr($this->plugin_name . '-admin_phones'); ?>" name="<?php echo esc_attr($this->plugin_name . '[admin_phones]'); ?>" type="text" size="82" class="mb-2" value="<?php echo esc_attr($admin_phones); ?>" />
                                <span class="my-2 d-block sms_tokens"><span>[store_name]</span> |
                                    <span>[billing_first_name]</span> |
                                    <span>[order_id]</span> |
                                    <span>[order_status]</span> |  <span>[order_date_created]</span> </span>
                                    <span>[order_currency]</span> | <span>[order_amount]</span>
                                </span>
                            </legend>
                            <textarea id="<<?php echo esc_attr($this->plugin_name . '-admin_status_sms'); ?>" name="<?php echo esc_attr($this->plugin_name . '[ADMIN_STATUS_SMS]'); ?>" rows="3" cols="85"><?php echo esc_html__($admin_status_sms); ?></textarea>
                        </fieldset>

                    </div>
                </li>


                <!-- working start -->


                <h3>Notify Customer</h3>


                <li>
                    <input class="alpha-collapse" type="checkbox" id="<?php echo esc_attr($this->plugin_name . '-order_status_pending'); ?>" name="<?php echo esc_attr($this->plugin_name . '[order_status_pending]'); ?>" <?php checked($order_status_pending, 1); ?> />
                    <label for="<?php echo esc_attr($this->plugin_name . '-order_status_pending'); ?>">
                        <span class="toggle_btn"></span>
                        <span><?php esc_attr_e('On Order Pending', $this->plugin_name); ?></span>
                    </label>
                    <div class="alpha-collapsable" id="order_status_pending">

                        <fieldset class="notify_template">
                            <legend>
                                <span class="sms_tokens my-2 d-block"><span>[store_name]</span> |
                                    <span>[billing_first_name]</span> |
                                    <span>[order_id]</span> |
                                    <span>[order_status]</span> |  <span>[order_date_created]</span>
                                    <span>[order_currency]</span> | <span>[order_amount]</span>
                                </span>
                            </legend>

                            <textarea id="<?php echo esc_attr($this->plugin_name . '-order_status_pending_sms'); ?>" name="<?php echo esc_attr($this->plugin_name . '[ORDER_STATUS_PENDING_SMS]'); ?>" rows="4" cols="85"><?php echo esc_html__($order_status_pending_sms); ?></textarea>
                        </fieldset>

                    </div>
                </li>


                <li>
                    <input class="alpha-collapse" type="checkbox" id="<?php echo esc_attr($this->plugin_name . '-order_status_processing'); ?>" name="<?php echo esc_attr($this->plugin_name . '[order_status_processing]'); ?>" <?php checked($order_status_processing, 1); ?> />
                    <label for="<?php echo esc_attr($this->plugin_name . '-order_status_processing'); ?>">
                        <span class="toggle_btn"></span>
                        <span><?php esc_attr_e('On Order Processing', $this->plugin_name); ?></span>
                    </label>
                    <div class="alpha-collapsable" id="order_status_processing">

                        <fieldset class="notify_template">
                            <legend>
                                <span class="sms_tokens my-2 d-block"><span>[store_name]</span> |
                                    <span>[billing_first_name]</span> |
                                    <span>[order_id]</span> |
                                    <span>[order_status]</span> |  <span>[order_date_created]</span>
                                    <span>[order_currency]</span> | <span>[order_amount]</span>
                                </span>
                            </legend>

                            <textarea id="<?php echo esc_attr($this->plugin_name . '-order_status_processing_sms'); ?>" name="<?php echo esc_attr($this->plugin_name . '[ORDER_STATUS_PROCESSING_SMS]'); ?>" rows="4" cols="85"><?php echo esc_html__($order_status_processing_sms); ?></textarea>
                        </fieldset>

                    </div>
                </li>

                <li>
                    <input class="alpha-collapse" type="checkbox" id="<?php echo esc_attr($this->plugin_name . '-order_status_on_hold'); ?>" name="<?php echo esc_attr($this->plugin_name . '[order_status_on_hold]'); ?>" <?php checked($order_status_on_hold, 1); ?> />
                    <label for="<?php echo esc_attr($this->plugin_name . '-order_status_on_hold'); ?>">
                        <span class="toggle_btn"></span>
                        <span><?php esc_attr_e('On Order On hold', $this->plugin_name); ?></span>
                    </label>
                    <div class="alpha-collapsable" id="order_status_on_hold">

                        <fieldset class="notify_template">
                            <legend>
                                <span class="sms_tokens my-2 d-block"><span>[store_name]</span> |
                                    <span>[billing_first_name]</span> |
                                    <span>[order_id]</span> |
                                    <span>[order_status]</span> |  <span>[order_date_created]</span>
                                    <span>[order_currency]</span> | <span>[order_amount]</span>
                                </span>
                            </legend>

                            <textarea id="<?php echo esc_attr($this->plugin_name . '-order_status_on_hold_sms'); ?>" name="<?php echo esc_attr($this->plugin_name . '[ORDER_STATUS_ON_HOLD_SMS]'); ?>" rows="4" cols="85"><?php echo esc_html__($order_status_on_hold_sms); ?></textarea>
                        </fieldset>

                    </div>
                </li>



                <li>
                    <input class="alpha-collapse" type="checkbox" id="<?php echo esc_attr($this->plugin_name . '-order_status_completed'); ?>" name="<?php echo esc_attr($this->plugin_name . '[order_status_completed]'); ?>" <?php checked($order_status_completed, 1); ?> />
                    <label for="<?php echo esc_attr($this->plugin_name . '-order_status_completed'); ?>">
                        <span class="toggle_btn"></span>
                        <span><?php esc_attr_e('On Order Completed', $this->plugin_name); ?></span>
                    </label>
                    <div class="alpha-collapsable" id="order_status_completed">

                        <fieldset class="notify_template">
                            <legend>
                                <span class="sms_tokens my-2 d-block"><span>[store_name]</span> |
                                    <span>[billing_first_name]</span> |
                                    <span>[order_id]</span> |
                                    <span>[order_status]</span> |  <span>[order_date_created]</span> |  <span>[order_date_completed]</span> 
                                    <span>[order_currency]</span> | <span>[order_amount]</span>
                                </span>
                            </legend>

                            <textarea id="<?php echo esc_attr($this->plugin_name . '-order_status_completed_sms'); ?>" name="<?php echo esc_attr($this->plugin_name . '[ORDER_STATUS_COMPLETED_SMS]'); ?>" rows="4" cols="85"><?php echo esc_html__($order_status_completed_sms); ?></textarea>
                        </fieldset>

                    </div>
                </li>


                <li>
                    <input class="alpha-collapse" type="checkbox" id="<?php echo esc_attr($this->plugin_name . '-order_status_cancelled'); ?>" name="<?php echo esc_attr($this->plugin_name . '[order_status_cancelled]'); ?>" <?php checked($order_status_cancelled, 1); ?> />
                    <label for="<?php echo esc_attr($this->plugin_name . '-order_status_cancelled'); ?>">
                        <span class="toggle_btn"></span>
                        <span><?php esc_attr_e('On Order Cancelled', $this->plugin_name); ?></span>
                    </label>
                    <div class="alpha-collapsable" id="order_status_cancelled">

                        <fieldset class="notify_template">
                            <legend>
                                <span class="sms_tokens my-2 d-block"><span>[store_name]</span> |
                                    <span>[billing_first_name]</span> |
                                    <span>[order_id]</span> |
                                    <span>[order_status]</span> |  <span>[order_date_created]</span>
                                    <span>[order_currency]</span> | <span>[order_amount]</span>
                                </span>
                            </legend>

                            <textarea id="<?php echo esc_attr($this->plugin_name . '-order_status_cancelled_sms'); ?>" name="<?php echo esc_attr($this->plugin_name . '[ORDER_STATUS_CANCELLED_SMS]'); ?>" rows="4" cols="85"><?php echo esc_html__($order_status_cancelled_sms); ?></textarea>
                        </fieldset>

                    </div>
                </li>


                <li>
                    <input class="alpha-collapse" type="checkbox" id="<?php echo esc_attr($this->plugin_name . '-order_status_refunded'); ?>" name="<?php echo esc_attr($this->plugin_name . '[order_status_refunded]'); ?>" <?php checked($order_status_refunded, 1); ?> />
                    <label for="<?php echo esc_attr($this->plugin_name . '-order_status_refunded'); ?>">
                        <span class="toggle_btn"></span>
                        <span><?php esc_attr_e('On Order Refunded', $this->plugin_name); ?></span>
                    </label>
                    <div class="alpha-collapsable" id="order_status_refunded">

                        <fieldset class="notify_template">
                            <legend>
                                <span class="sms_tokens my-2 d-block"><span>[store_name]</span> |
                                    <span>[billing_first_name]</span> |
                                    <span>[order_id]</span> |
                                    <span>[order_status]</span> |  <span>[order_date_created]</span>
                                    <span>[order_currency]</span> | <span>[order_amount]</span>
                                </span>
                            </legend>

                            <textarea id="<?php echo esc_attr($this->plugin_name . '-order_status_refunded_sms'); ?>" name="<?php echo esc_attr($this->plugin_name . '[ORDER_STATUS_REFUNDED_SMS]'); ?>" rows="4" cols="85"><?php echo esc_html__($order_status_refunded_sms); ?></textarea>
                        </fieldset>

                    </div>
                </li>



                <li>
                    <input class="alpha-collapse" type="checkbox" id="<?php echo esc_attr($this->plugin_name . '-order_status_failed'); ?>" name="<?php echo esc_attr($this->plugin_name . '[order_status_failed]'); ?>" <?php checked($order_status_failed, 1); ?> />
                    <label for="<?php echo esc_attr($this->plugin_name . '-order_status_failed'); ?>">
                        <span class="toggle_btn"></span>
                        <span><?php esc_attr_e('On Order Failed', $this->plugin_name); ?></span>
                    </label>
                    <div class="alpha-collapsable" id="order_status_failed">

                        <fieldset class="notify_template">
                            <legend>
                                <span class="sms_tokens my-2 d-block"><span>[store_name]</span> |
                                    <span>[billing_first_name]</span> |
                                    <span>[order_id]</span> |
                                    <span>[order_status]</span> |  <span>[order_date_created]</span>
                                    <span>[order_currency]</span> | <span>[order_amount]</span>
                                </span>
                            </legend>

                            <textarea id="<?php echo esc_attr($this->plugin_name . '-order_status_failed_sms'); ?>" name="<?php echo esc_attr($this->plugin_name . '[ORDER_STATUS_FAILED_SMS]'); ?>" rows="4" cols="85"><?php echo esc_html__($order_status_failed_sms); ?></textarea>
                        </fieldset>

                    </div>
                </li>

                <!-- working end -->

            </ol>
        <?php }
        ?>

        <?php submit_button(__('Save all changes', $this->plugin_name), 'primary', 'submit', true); ?>
    </form>
</div>