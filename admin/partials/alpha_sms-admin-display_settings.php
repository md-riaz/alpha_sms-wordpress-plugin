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
if (!defined('WPINC')) die;
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
  <h2><span class="dashicons dashicons-admin-tools"></span> Alpha SMS
    <?php esc_attr_e('Options', $this->plugin_name); ?></h2>
  <p>Here you can set all the options for using the API</p>

  <!--   show admin notice when settings are saved-->
  <?php settings_errors(); ?>

  <form method="post" name="<?php echo $this->plugin_name; ?>" action="options.php"
    id="<?php echo $this->plugin_name; ?>">
    <?php

    $order_alerts =
      [
        'DEFAULT_BUYER_SMS_PENDING'        => __(
          'Hello [billing_first_name], you are just one step away from placing your order, please complete your payment, to proceed.',
          $this->plugin_name
        ),
        'DEFAULT_BUYER_SMS_ON_HOLD'        => __(
          'Hello [billing_first_name], your order #[order_id] with [store_name] has been put on hold, our team will contact you shortly with more details.',
          $this->plugin_name
        ),
        'DEFAULT_BUYER_SMS_PROCESSING'     => __(
          'Hello [billing_first_name], thank you for placing your order #[order_id] with [store_name]. Your order is processing.',
          $this->plugin_name
        ),
        'DEFAULT_BUYER_SMS_COMPLETED'      => __(
          'Hello [billing_first_name] your order #[order_id] with [store_name] has been dispatched and shall deliver to you shortly.',
          $this->plugin_name
        ),
        'DEFAULT_BUYER_SMS_CANCELLED'      => __(
          'Hello [billing_first_name], your order #[order_id] with [store_name] has been cancelled due to some un-avoidable conditions. Sorry for the inconvenience caused.',
          $this->plugin_name
        ),

        'DEFAULT_ADMIN_SMS_PENDING'        => __(
          '[store_name]: Hello, [billing_first_name] is trying to place order #[order_id] value [order_currency] [order_amount]',
          $this->plugin_name
        ),
        'DEFAULT_ADMIN_SMS_ON_HOLD'        => __(
          '[store_name]: Your order #[order_id] [order_currency] [order_amount]. is On Hold Now.',
          $this->plugin_name
        ),
        'DEFAULT_ADMIN_SMS_PROCESSING'     => __(
          '[store_name]: You have a new order #[order_id] for order value [order_amount]. Please check your admin dashboard for complete details.',
          $this->plugin_name
        ),
        'DEFAULT_ADMIN_SMS_COMPLETED'      => __(
          '[store_name]: Your order #[order_id] [order_currency] [order_amount] is completed.',
          $this->plugin_name
        ),
        'DEFAULT_ADMIN_SMS_CANCELLED'      => __(
          '[store_name]: Your order #[order_id] [order_currency] [order_amount] is Cancelled.',
          $this->plugin_name
        )
      ];

    //Grab all options
    $options = get_option($this->plugin_name);

    $api_key = (isset($options['api_key']) && !empty($options['api_key'])) ? substr_replace(esc_attr($options['api_key']), str_repeat('*', 24), 12, 16) : '';
    $sender_id = (isset($options['sender_id']) && !empty($options['sender_id'])) ? esc_attr($options['sender_id']) : '';

    $wp_reg = (isset($options['wp_reg']) && !empty($options['wp_reg'])) ? 1 : 0;
    $wp_login = (isset($options['wp_login']) && !empty($options['wp_login'])) ? 1 : 0;
    $wc_reg = (isset($options['wc_reg']) && !empty($options['wc_reg'])) ? 1 : 0;
    $wc_login = (isset($options['wc_login']) && !empty($options['wc_login'])) ? 1 : 0;
    $otp_checkout = (isset($options['otp_checkout']) && !empty($options['otp_checkout'])) ? 1 : 0;

    $order_status_buyer = (isset($options['order_status_buyer']) && !empty($options['order_status_buyer'])) ? 1 : 0;
    $buyer_pending = (isset($options['BUYER_SMS_PENDING']) && !empty($options['BUYER_SMS_PENDING'])) ? $options['BUYER_SMS_PENDING'] : $order_alerts['DEFAULT_BUYER_SMS_PENDING'];
    $buyer_on_hold = (isset($options['BUYER_SMS_ON_HOLD']) && !empty($options['BUYER_SMS_ON_HOLD'])) ? $options['BUYER_SMS_ON_HOLD'] : $order_alerts['DEFAULT_BUYER_SMS_ON_HOLD'];
    $buyer_processing = (isset($options['BUYER_SMS_PROCESSING']) && !empty($options['BUYER_SMS_PROCESSING'])) ? $options['BUYER_SMS_PROCESSING'] : $order_alerts['DEFAULT_BUYER_SMS_PROCESSING'];
    $buyer_completed = (isset($options['BUYER_SMS_COMPLETED']) && !empty($options['BUYER_SMS_COMPLETED'])) ? $options['BUYER_SMS_COMPLETED'] : $order_alerts['DEFAULT_BUYER_SMS_COMPLETED'];
    $buyer_cancelled = (isset($options['BUYER_SMS_CANCELLED']) && !empty($options['BUYER_SMS_CANCELLED'])) ? $options['BUYER_SMS_CANCELLED'] : $order_alerts['DEFAULT_BUYER_SMS_CANCELLED'];

    $order_status_admin = (isset($options['order_status_admin']) && !empty($options['order_status_admin'])) ? 1 : 0;
    $admin_pending = (isset($options['ADMIN_SMS_PENDING']) && !empty($options['ADMIN_SMS_PENDING'])) ? $options['ADMIN_SMS_PENDING'] : $order_alerts['DEFAULT_ADMIN_SMS_PENDING'];
    $admin_on_hold = (isset($options['ADMIN_SMS_ON_HOLD']) && !empty($options['ADMIN_SMS_ON_HOLD'])) ? $options['ADMIN_SMS_ON_HOLD'] : $order_alerts['DEFAULT_ADMIN_SMS_ON_HOLD'];
    $admin_processing = (isset($options['ADMIN_SMS_PROCESSING']) && !empty($options['ADMIN_SMS_PROCESSING'])) ? $options['ADMIN_SMS_PROCESSING'] : $order_alerts['DEFAULT_ADMIN_SMS_PROCESSING'];
    $admin_completed = (isset($options['ADMIN_SMS_COMPLETED']) && !empty($options['ADMIN_SMS_COMPLETED'])) ? $options['ADMIN_SMS_COMPLETED'] : $order_alerts['DEFAULT_ADMIN_SMS_COMPLETED'];
    $admin_cancelled = (isset($options['ADMIN_SMS_CANCELLED']) && !empty($options['ADMIN_SMS_CANCELLED'])) ? $options['ADMIN_SMS_CANCELLED'] : $order_alerts['DEFAULT_ADMIN_SMS_CANCELLED'];

    settings_fields($this->plugin_name);
    do_settings_sections($this->plugin_name);
    ?>

    <!-- API Key -->
    <table class="form-table" aria-label="admin settings form">
      <tr>
        <th scope="row">
          <label for="<?php echo $this->plugin_name; ?>-api_key">
            <?php esc_attr_e('API Key', $this->plugin_name); ?>
          </label>
        </th>
        <td>
          <input id="<?php echo $this->plugin_name; ?>-api_key" name="<?php echo $this->plugin_name; ?>[api_key]"
            type="text" size="55"
            value="<?php if (!empty($api_key)) echo $api_key;
                                                                                                                                                else echo 'Enter Your API Key'; ?>" />
        </td>
      </tr>

      <tr>
        <th scope="row">
          <label for="<?php echo $this->plugin_name; ?>-sender_id">
            <?php esc_attr_e('Sender ID', $this->plugin_name); ?>
          </label>
        </th>
        <td>
          <input id="<?php echo $this->plugin_name; ?>-sender_id" name="<?php echo $this->plugin_name; ?>[sender_id]"
            type="text" size="55"
            value="<?php if (!empty($sender_id)) echo $sender_id;
                                                                                                                                                    else echo ''; ?>" />
        </td>
      </tr>
    </table>

    <hr>

    <h3><?php esc_attr_e('WordPress', $this->plugin_name); ?></h3>
    <ol class="switches">
      <li>
        <input type="checkbox" id="<?php echo $this->plugin_name; ?>-wp_reg"
          name="<?php echo $this->plugin_name; ?>[wp_reg]" <?php checked($wp_reg, 1); ?> />
        <label for="<?php echo $this->plugin_name; ?>-wp_reg">
          <span class="toggle_btn"></span>
          <span><?php esc_attr_e('Two Factor OTP Verification For WordPress Register Form', $this->plugin_name); ?></span>
        </label>
      </li>

      <li>
        <input type="checkbox" id="<?php echo $this->plugin_name; ?>-wp_login"
          name="<?php echo $this->plugin_name; ?>[wp_login]" <?php checked($wp_login, 1); ?> />
        <label for="<?php echo $this->plugin_name; ?>-wp_login">
          <span class="toggle_btn"></span>
          <span><?php esc_attr_e('Two Factor OTP Verification For WordPress Login Form', $this->plugin_name); ?></span>
        </label>
      </li>

    </ol>


    <h3><?php esc_attr_e('Woocommerce', $this->plugin_name); ?></h3>
    <ol class="switches">

      <li>
        <input type="checkbox" id="<?php echo $this->plugin_name; ?>-wc_reg"
          name="<?php echo $this->plugin_name; ?>[wc_reg]" <?php checked($wc_reg, 1); ?> />
        <label for="<?php echo $this->plugin_name; ?>-wc_reg">
          <span class="toggle_btn"></span>
          <span><?php esc_attr_e('Two Factor OTP Verification For Woocommerce Register Form', $this->plugin_name); ?></span>
        </label>
      </li>

      <li>
        <input type="checkbox" id="<?php echo $this->plugin_name; ?>-wc_login"
          name="<?php echo $this->plugin_name; ?>[wc_login]" <?php checked($wc_login, 1); ?> />
        <label for="<?php echo $this->plugin_name; ?>-wc_login">
          <span class="toggle_btn"></span>
          <span><?php esc_attr_e('Two Factor OTP Verification For Woocommerce Login Form', $this->plugin_name); ?></span>
        </label>
      </li>

      <li>
        <input type="checkbox" id="<?php echo $this->plugin_name; ?>-otp_checkout"
          name="<?php echo $this->plugin_name; ?>[otp_checkout]" <?php checked($otp_checkout, 1); ?> />
        <label for="<?php echo $this->plugin_name; ?>-otp_checkout">
          <span class="toggle_btn"></span>
          <span><?php esc_attr_e('OTP Verification For Guest Customer Checkout', $this->plugin_name); ?></span>
        </label>
      </li>

      <li>
        <div class="toggle_container">
          <input type="checkbox" id="<?php echo $this->plugin_name; ?>-order_status_buyer"
            name="<?php echo $this->plugin_name; ?>[order_status_buyer]" <?php checked($order_status_buyer, 1); ?> />
          <label for="<?php echo $this->plugin_name; ?>-order_status_buyer">
            <span class="toggle_btn"></span>
          </label>
          <span class="toggle_visible"
            data-visible="order_status_buyer"><?php esc_attr_e('Notify Buyer on Order Status Change', $this->plugin_name); ?></span>
        </div>
        <div id="order_status_buyer">

          <fieldset class="notify_template">
            <legend>
              <h4 class="mb-1 mt-2"><?php esc_attr_e('Order Payment Pending', $this->plugin_name) ?></h4>
            </legend>
            <div class="my-1 sms_tokens"><span>[store_name]</span> | <span>[billing_first_name]</span> |
              <span>[order_id]</span> |
              <span>[order_status]</span> |
              <span>[order_currency]</span> | <span>[order_amount]</span>
            </div>
            <textarea id="<?php echo $this->plugin_name; ?>-buyer_pending"
              name="<?php echo $this->plugin_name; ?>[BUYER_SMS_PENDING]" rows="4" cols="85"><?php echo $buyer_pending; ?>
            </textarea>
          </fieldset>


          <fieldset class="notify_template">
            <legend>
              <h4 class="mb-1 mt-2"><?php esc_attr_e('Order is On Hold', $this->plugin_name) ?></h4>
            </legend>
            <div class="my-1 sms_tokens"><span>[store_name]</span> | <span>[billing_first_name]</span> |
              <span>[order_id]</span> |
              <span>[order_status]</span> |
              <span>[order_currency]</span> | <span>[order_amount]</span>
            </div>
            <textarea id="<?php echo $this->plugin_name; ?>-buyer_on_hold"
              name="<?php echo $this->plugin_name; ?>[BUYER_SMS_ON_HOLD]" rows="4" cols="85"><?php echo $buyer_on_hold; ?>
            </textarea>
          </fieldset>

          <fieldset class="notify_template">
            <legend>
              <h4 class="mb-1 mt-2"><?php esc_attr_e('Order is Processing', $this->plugin_name) ?></h4>
            </legend>
            <div class="my-1 sms_tokens"><span>[store_name]</span> | <span>[billing_first_name]</span> |
              <span>[order_id]</span> |
              <span>[order_status]</span> |
              <span>[order_currency]</span> | <span>[order_amount]</span>
            </div>
            <textarea id="<?php echo $this->plugin_name; ?>-buyer_processing"
              name="<?php echo $this->plugin_name; ?>[BUYER_SMS_PROCESSING]" rows="4" cols="85"><?php echo $buyer_processing; ?>
            </textarea>
          </fieldset>

          <fieldset class="notify_template">
            <legend>
              <h4 class="mb-1 mt-2"><?php esc_attr_e('Order is Completed', $this->plugin_name) ?></h4>
            </legend>
            <div class="my-1 sms_tokens"><span>[store_name]</span> | <span>[billing_first_name]</span> |
              <span>[order_id]</span> |
              <span>[order_status]</span> |
              <span>[order_currency]</span> | <span>[order_amount]</span>
            </div>
            <textarea id="<?php echo $this->plugin_name; ?>-buyer_completed"
              name="<?php echo $this->plugin_name; ?>[BUYER_SMS_COMPLETED]" rows="4" cols="85"><?php echo $buyer_completed; ?>
            </textarea>
          </fieldset>

          <fieldset class="notify_template">
            <legend>
              <h4 class="mb-1 mt-2"><?php esc_attr_e('Order is Cancelled', $this->plugin_name) ?></h4>
            </legend>
            <div class="my-1 sms_tokens"><span>[store_name]</span> | <span>[billing_first_name]</span> |
              <span>[order_id]</span> |
              <span>[order_status]</span> |
              <span>[order_currency]</span> | <span>[order_amount]</span>
            </div>
            <textarea id="<?php echo $this->plugin_name; ?>-buyer_cancelled"
              name="<?php echo $this->plugin_name; ?>[BUYER_SMS_CANCELLED]" rows="4" cols="85"><?php echo $buyer_cancelled; ?>
            </textarea>
          </fieldset>

        </div>
      </li>

      <li>
        <div class="toggle_container">
          <input type="checkbox" id="<?php echo $this->plugin_name; ?>-order_status_admin"
            name="<?php echo $this->plugin_name; ?>[order_status_admin]" <?php checked($order_status_admin, 1); ?> />
          <label for="<?php echo $this->plugin_name; ?>-order_status_admin">
            <span class="toggle_btn"></span>
          </label>
          <span class="toggle_visible"
            data-visible="order_status_admin"><?php esc_attr_e('Notify Admin on Order Status Change', $this->plugin_name); ?></span>
        </div>
        <div id="order_status_admin">

          <fieldset class="notify_template">
            <legend>
              <h4 class="mb-1 mt-2"><?php esc_attr_e('Order Payment Pending', $this->plugin_name) ?></h4>
            </legend>
            <div class="my-1 sms_tokens"><span>[store_name]</span> | <span>[billing_first_name]</span> |
              <span>[order_id]</span> |
              <span>[order_status]</span> |
              <span>[order_currency]</span> | <span>[order_amount]</span>
            </div>
            <textarea id="<?php echo $this->plugin_name; ?>-admin_pending"
              name="<?php echo $this->plugin_name; ?>[ADMIN_SMS_PENDING]" rows="4" cols="85"><?php echo $admin_pending; ?>
            </textarea>
          </fieldset>


          <fieldset class="notify_template">
            <legend>
              <h4 class="mb-1 mt-2"><?php esc_attr_e('Order is On Hold', $this->plugin_name) ?></h4>
            </legend>
            <div class="my-1 sms_tokens"><span>[store_name]</span> | <span>[billing_first_name]</span> |
              <span>[order_id]</span> |
              <span>[order_status]</span> |
              <span>[order_currency]</span> | <span>[order_amount]</span>
            </div>
            <textarea id="<?php echo $this->plugin_name; ?>-admin_on_hold"
              name="<?php echo $this->plugin_name; ?>[ADMIN_SMS_ON_HOLD]" rows="4" cols="85"><?php echo $admin_on_hold; ?>
            </textarea>
          </fieldset>

          <fieldset class="notify_template">
            <legend>
              <h4 class="mb-1 mt-2"><?php esc_attr_e('Order is Processing', $this->plugin_name) ?></h4>
            </legend>
            <div class="my-1 sms_tokens"><span>[store_name]</span> | <span>[billing_first_name]</span> |
              <span>[order_id]</span> |
              <span>[order_status]</span> |
              <span>[order_currency]</span> | <span>[order_amount]</span>
            </div>
            <textarea id="<?php echo $this->plugin_name; ?>-admin_processing"
              name="<?php echo $this->plugin_name; ?>[ADMIN_SMS_PROCESSING]" rows="4" cols="85"><?php echo $admin_processing; ?>
            </textarea>
          </fieldset>

          <fieldset class="notify_template">
            <legend>
              <h4 class="mb-1 mt-2"><?php esc_attr_e('Order is Completed', $this->plugin_name) ?></h4>
            </legend>
            <div class="my-1 sms_tokens"><span>[store_name]</span> | <span>[billing_first_name]</span> |
              <span>[order_id]</span> |
              <span>[order_status]</span> |
              <span>[order_currency]</span> | <span>[order_amount]</span>
            </div>
            <textarea id="<?php echo $this->plugin_name; ?>-admin_completed"
              name="<?php echo $this->plugin_name; ?>[ADMIN_SMS_COMPLETED]" rows="4" cols="85"><?php echo $admin_completed; ?>
            </textarea>
          </fieldset>

          <fieldset class="notify_template">
            <legend>
              <h4 class="mb-1 mt-2"><?php esc_attr_e('Order is Cancelled', $this->plugin_name) ?></h4>
            </legend>
            <div class="my-1 sms_tokens"><span>[store_name]</span> | <span>[billing_first_name]</span> |
              <span>[order_id]</span> |
              <span>[order_status]</span> |
              <span>[order_currency]</span> | <span>[order_amount]</span>
            </div>
            <textarea id="<?php echo $this->plugin_name; ?>-admin_cancelled"
              name="<?php echo $this->plugin_name; ?>[ADMIN_SMS_CANCELLED]" rows="4" cols="85"><?php echo $admin_cancelled; ?>
            </textarea>
          </fieldset>

        </div>
      </li>

    </ol>

    <?php submit_button(__('Save all changes', $this->plugin_name), 'primary', 'submit', true); ?>
  </form>
</div>