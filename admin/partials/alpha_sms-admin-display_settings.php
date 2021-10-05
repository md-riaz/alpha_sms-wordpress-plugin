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

		$api_key = (isset($options['api_key']) && !empty($options['api_key'])) ? esc_attr($options['api_key']) : '';
		$sender_id = (isset($options['sender_id']) && !empty($options['sender_id'])) ? esc_attr($options['sender_id']) : '';

		$woocommerce_reg_phone = (isset($options['woocommerce_reg_phone']) && !empty($options['woocommerce_reg_phone'])) ? 1 : 0;
		$reg_allow_phone_wp = (isset($options['reg_allow_phone_wp']) && !empty($options['reg_allow_phone_wp'])) ? 1 : 0;


		settings_fields($this->plugin_name);
		do_settings_sections($this->plugin_name);

		?>

        <!-- API Key -->
        <table class="form-table">
            <tr>
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
							else echo 'Enter Your API Key'; ?>"
                    />
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="<?php echo $this->plugin_name; ?>-sender_id">
				        <?php esc_attr_e('Sender ID', $this->plugin_name); ?>
                    </label>
                </th>
                <td>
                    <input
                            id="<?php echo $this->plugin_name; ?>-sender_id"
                            name="<?php echo $this->plugin_name; ?>[sender_id]"
                            type="text" size="50"
                            value="<?php if (!empty($sender_id)) echo $sender_id;
					        else echo ''; ?>"
                    />
                </td>
            </tr>
        </table>

        <hr>

	    <?php if( is_plugin_active( 'woocommerce/woocommerce.php' )){ ?>
            <h3><?php esc_attr_e('WordPress Verification', $this->plugin_name); ?></h3>

            <!-- Checkbox -->
            <fieldset>

                <div class="mb-2">
                    <label for="<?php echo $this->plugin_name; ?>-woocommerce_reg_phone">
                        <input
                                type="checkbox"
                                id="<?php echo $this->plugin_name; ?>-woocommerce_reg_phone"
                                name="<?php echo $this->plugin_name; ?>[woocommerce_reg_phone]"
                                value="1"
						    <?php checked($woocommerce_reg_phone, 1); ?> />
                        <span><?php esc_attr_e('Allow phone number in woocommerce register / my account', $this->plugin_name); ?></span>
                    </label>
                </div>

                <div class="mb-2">
                    <label for="<?php echo $this->plugin_name; ?>-reg_allow_phone_wp">
                        <input
                                type="checkbox"
                                id="<?php echo $this->plugin_name; ?>-reg_allow_phone_wp"
                                name="<?php echo $this->plugin_name; ?>[reg_allow_phone_wp]"
                                value="1"
						    <?php checked($reg_allow_phone_wp, 1); ?> />
                        <span><?php esc_attr_e('Allow Phone in WordPress Registration Form', $this->plugin_name); ?></span>
                    </label>
                </div>


            </fieldset>

	    <?php } else { ?>
            <h4><?php esc_attr_e('Enable woocommerce for additional settings', $this->plugin_name); ?></h4>
	    <?php } ?>

		<?php submit_button(__('Save all changes', $this->plugin_name), 'primary', 'submit', true); ?>
    </form>
</div>