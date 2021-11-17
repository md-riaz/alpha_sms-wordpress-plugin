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
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
    <h2>
        <span class="dashicons dashicons-format-status"></span> <?php esc_attr_e('SMS Campaign', $this->plugin_name); ?>
    </h2>

    <?php
    //Grab all options
    $options = get_option($this->plugin_name);

    $balance = '';

    if (!$options || empty($options['api_key'])) {
        $balance = 'Please configure SMS API first.';
    } else {
        require_once ALPHA_SMS_PATH. 'includes/sms.class.php';

        $smsPortal = new AlphaSMS($options['api_key']);

        $response = $smsPortal->getBalance();

        if ($response && $response->error === 0) {
            $balance = $response->data->balance;
        } elseif ($response && $response->error === 405) {
            $balance = 'Please configure SMS API first.';
        } else {
            $balance = 'Unknown Error, failed to fetch balance';
        }
    }
    ?>

    <?php if (is_numeric($balance)): ?>
        <p><strong>Balance:</strong> BDT <?php echo esc_html( number_format((float)$balance, 2, '.', ',') ) ?> </p>
    <?php else: ?>
        <strong class='text-danger'><?php echo esc_html( $balance ) ?></strong>
    <?php endif; ?>

    <!--   show notice when form submit -->
    <?php settings_errors(); ?>

    <form method="post" name=" <?php echo esc_attr( $this->plugin_name ); ?>"
          action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
        <input type="hidden" name="action" value="<?php echo esc_attr( $this->plugin_name . '_campaign' ) ?>">

        <!-- Phone Numbers -->
        <fieldset class="mb-2">
            <p class="mb-2"><strong><?php esc_attr_e('Enter Phone Numbers', $this->plugin_name); ?></strong></p>
            <legend class="screen-reader-text">
                <span><?php esc_attr_e( 'Enter Phone Numbers', $this->plugin_name ); ?></span>
            </legend>
            <textarea
                    class="d-block"
                    id="<?php echo esc_attr( $this->plugin_name . '-numbers' ); ?>"
                    name="<?php echo esc_attr( $this->plugin_name . '[numbers]' ); ?>"
                    rows="2"
                    cols="70"></textarea>
            <small>New Line Separated</small>
        </fieldset>

        <!-- Checkbox -->
        <fieldset>
            <legend class="screen-reader-text">
                <span><?php esc_attr_e('Include all customers', $this->plugin_name); ?></span>
            </legend>
            <label for="<?php echo esc_attr( $this->plugin_name . '-all_users' ); ?>">
                <input type="checkbox" id="<?php echo esc_attr( $this->plugin_name . '-all_users' ); ?>"
                       name="<?php echo esc_attr( $this->plugin_name . '[all_users]' ); ?>" value="1"/>
                <span><?php esc_attr_e( 'Include all customers', $this->plugin_name ); ?></span>
            </label>
        </fieldset>

        <!-- SMS Body -->
        <fieldset>
            <p class="mb-2"><strong><?php esc_attr_e( 'Enter SMS Content', $this->plugin_name ); ?></strong></p>
            <legend class="screen-reader-text">
                <span><?php esc_attr_e( 'Enter SMS Content', $this->plugin_name ); ?></span>
            </legend>
            <textarea
                    class="d-block"
                    id="<?php echo esc_attr( $this->plugin_name . '-body' ); ?>"
                    name="<?php echo esc_attr( $this->plugin_name . '[body]' ); ?>"
                    rows="8"
                    cols="70"
                    required></textarea>
        </fieldset>


        <?php submit_button(__('Send SMS', $this->plugin_name), 'primary', 'submit', true); ?>
    </form>
</div>