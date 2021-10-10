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
    <h2>Campaign <?php esc_attr_e('Options', $this->plugin_name); ?></h2>

    <!--   show notice when form submit -->
	<?php settings_errors(); ?>

    <form method="post" name="<?php echo $this->plugin_name; ?>" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">
        <input type="hidden" name="action" value="<?php echo $this->plugin_name ?>_campaign">

        <!-- Phone Numbers -->
        <fieldset class="mb-2">
            <p class="mb-2"><strong><?php esc_attr_e( 'Enter Phone Numbers', $this->plugin_name ); ?></strong></p>
            <legend class="screen-reader-text">
                <span><?php esc_attr_e( 'Enter Phone Numbers', $this->plugin_name ); ?></span>
            </legend>
            <textarea class="d-block" id="<?php echo $this->plugin_name; ?>-numbers"
                      name="<?php echo $this->plugin_name; ?>[numbers]" rows="2" cols="70"></textarea>
            <small>New Line Separated</small>
        </fieldset>

        <!-- Checkbox -->
        <fieldset>
            <legend class="screen-reader-text">
                <span><?php esc_attr_e( 'Include all customers', $this->plugin_name ); ?></span>
            </legend>
            <label for="<?php echo $this->plugin_name; ?>-all_users">
                <input type="checkbox" id="<?php echo $this->plugin_name; ?>-all_users" name="<?php echo $this->plugin_name; ?>[all_users]" value="1" />
                <span><?php esc_attr_e('Include all customers', $this->plugin_name ); ?></span>
            </label>
        </fieldset>

        <!-- SMS Body -->
        <fieldset>
            <p class="mb-2"><strong><?php esc_attr_e( ' Enter SMS Content', $this->plugin_name ); ?></strong></p>
            <legend class="screen-reader-text">
                <span><?php esc_attr_e( ' Enter SMS Content', $this->plugin_name ); ?></span>
            </legend>
            <textarea class="d-block" id="<?php echo $this->plugin_name; ?>-body"
                      name="<?php echo $this->plugin_name; ?>[body]" rows="8" cols="70" required></textarea>

        </fieldset>


        <?php submit_button(__('Send SMS', $this->plugin_name), 'primary', 'submit', true); ?>
    </form>
</div>