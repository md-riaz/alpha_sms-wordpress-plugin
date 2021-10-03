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

    <form method="post" name="<?php echo $this->plugin_name; ?>" action="options.php">
		<?php
		//Grab all options
		$options = get_option($this->plugin_name);

		$api_key = (isset($options['api_key']) && !empty($options['api_key'])) ? esc_attr($options['api_key']) : 'Enter Your API Key';
		$order_pending_payment = (isset($options['order_pending_payment']) && !empty($options['order_pending_payment'])) ? 1 : 0;
		$order_processing = (isset($options['order_processing']) && !empty($options['order_processing'])) ? 1 : 0;
		$order_on_hold = (isset($options['order_on_hold']) && !empty($options['order_on_hold'])) ? 1 : 0;
		$order_completed = (isset($options['order_completed']) && !empty($options['order_completed'])) ? 1 : 0;
		$order_cancelled = (isset($options['order_cancelled']) && !empty($options['order_cancelled'])) ? 1 : 0;
		$order_refunded = (isset($options['order_refunded']) && !empty($options['order_refunded'])) ? 1 : 0;

		settings_fields($this->plugin_name);
		do_settings_sections($this->plugin_name);

		?>

        <!-- API Key -->
        <table class="form-table">
            <tr valign="top">
                <th scope="row">
                    <label for="<?php echo $this->plugin_name; ?>-api_key">
						<?php esc_attr_e('API Key', $this->plugin_name); ?>
                    </label>
                </th>
                <td>
                    <input
                            id="<?php echo $this->plugin_name; ?>-api_key"
                            name="<?php echo $this->plugin_name; ?>[api_key]"
                            type="text" size="50"
                            value="<?php if (!empty($api_key)) echo $api_key;
							else echo 'default'; ?>"
                    />
                </td>
            </tr>
        </table>

        <hr>

	    <?php if( is_plugin_active( 'woocommerce/woocommerce.php' )){ ?>
            <h3><?php esc_attr_e('Customer Notifications', $this->plugin_name); ?></h3>

            <!-- Checkbox -->
            <fieldset>

                <div class="form-group">
                    <label for="<?php echo $this->plugin_name; ?>-order_pending_payment">
                        <input
                                type="checkbox"
                                id="<?php echo $this->plugin_name; ?>-order_pending_payment"
                                name="<?php echo $this->plugin_name; ?>[order_pending_payment]"
                                value="1"
						    <?php checked($order_pending_payment, 1); ?> />
                        <span><?php esc_attr_e('When Order is Pending Payment', $this->plugin_name); ?></span>
                    </label>
                </div>

                <div class="form-group">
                    <label for="<?php echo $this->plugin_name; ?>-order_processing">
                        <input
                                type="checkbox"
                                id="<?php echo $this->plugin_name; ?>-order_processing"
                                name="<?php echo $this->plugin_name; ?>[order_processing]"
                                value="1"
						    <?php checked($order_processing, 1); ?> />
                        <span><?php esc_attr_e('When Order is Pending Processing', $this->plugin_name); ?></span>
                    </label>
                </div>

                <div class="form-group">
                    <label for="<?php echo $this->plugin_name; ?>-order_on_hold">
                        <input
                                type="checkbox"
                                id="<?php echo $this->plugin_name; ?>-order_on_hold"
                                name="<?php echo $this->plugin_name; ?>[order_on_hold]"
                                value="1"
						    <?php checked($order_on_hold, 1); ?> />
                        <span><?php esc_attr_e('When Order is On Hold', $this->plugin_name); ?></span>
                    </label>
                </div>

                <div class="form-group">
                    <label for="<?php echo $this->plugin_name; ?>-order_completed">
                        <input
                                type="checkbox"
                                id="<?php echo $this->plugin_name; ?>-order_completed"
                                name="<?php echo $this->plugin_name; ?>[order_completed]"
                                value="1"
						    <?php checked($order_completed, 1); ?> />
                        <span><?php esc_attr_e('When Order is Completed', $this->plugin_name); ?></span>
                    </label>
                </div>

                <div class="form-group">
                    <label for="<?php echo $this->plugin_name; ?>-order_cancelled">
                        <input
                                type="checkbox"
                                id="<?php echo $this->plugin_name; ?>-order_cancelled"
                                name="<?php echo $this->plugin_name; ?>[order_cancelled]"
                                value="1"
						    <?php checked($order_cancelled, 1); ?> />
                        <span><?php esc_attr_e('When Order is Cancelled', $this->plugin_name); ?></span>
                    </label>
                </div>

                <div class="form-group">
                    <label for="<?php echo $this->plugin_name; ?>-order_refunded">
                        <input
                                type="checkbox"
                                id="<?php echo $this->plugin_name; ?>-order_refunded"
                                name="<?php echo $this->plugin_name; ?>[order_refunded]"
                                value="1"
						    <?php checked($order_refunded, 1); ?> />
                        <span><?php esc_attr_e('When Order is Refunded', $this->plugin_name); ?></span>
                    </label>
                </div>

            </fieldset>

	    <?php } else { ?>
            <h4><?php esc_attr_e('Enable woocommerce for additional settings', $this->plugin_name); ?></h4>
	    <?php } ?>

		<?php submit_button(__('Save all changes', $this->plugin_name), 'primary', 'submit', true); ?>
    </form>
</div>