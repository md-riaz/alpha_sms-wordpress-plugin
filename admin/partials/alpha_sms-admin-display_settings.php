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

    <form method="post" name="<?php echo $this->plugin_name; ?>" action="options.php" id="<?php echo $this->plugin_name; ?>">
		<?php

		//Grab all options
		$options = get_option($this->plugin_name);

		$api_key = (isset($options['api_key']) && !empty($options['api_key'])) ? substr_replace(esc_attr($options['api_key']), str_repeat('*', 24), 12, 16) : '';
		$sender_id = (isset($options['sender_id']) && !empty($options['sender_id'])) ? esc_attr($options['sender_id']) : '';

		$order_status = (isset($options['order_status']) && !empty($options['order_status'])) ? 1 : 0;
		$login_otp = (isset($options['login_otp']) && !empty($options['login_otp'])) ? 1 : 0;
		$reg_otp = (isset($options['reg_otp']) && !empty($options['reg_otp'])) ? 1 : 0;

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

        <h3><?php esc_attr_e('SMS Events', $this->plugin_name); ?></h3>

        <ol class="switches">
            <li>
                <input
                        type="checkbox"
                        id="<?php echo $this->plugin_name; ?>-order_status"
                        name="<?php echo $this->plugin_name; ?>[order_status]"
					<?php checked($order_status, 1); ?>
                />
                <label for="<?php echo $this->plugin_name; ?>-order_status">
                    <span class="toggle_btn"></span>
                    <span><?php esc_attr_e('Notify on Order Status Change', $this->plugin_name); ?></span>
                </label>
            </li>

            <li>
                <input
                        type="checkbox"
                        id="<?php echo $this->plugin_name; ?>-login_otp"
                        name="<?php echo $this->plugin_name; ?>[login_otp]"
					<?php checked($login_otp, 1); ?>
                />
                <label for="<?php echo $this->plugin_name; ?>-login_otp">
                    <span class="toggle_btn"></span>
                    <span><?php esc_attr_e('Login with OTP', $this->plugin_name); ?></span>
                </label>
            </li>

            <li>
                <input
                        type="checkbox"
                        id="<?php echo $this->plugin_name; ?>-reg_otp"
                        name="<?php echo $this->plugin_name; ?>[reg_otp]"
					<?php checked($reg_otp, 1); ?>
                />
                <label for="<?php echo $this->plugin_name; ?>-reg_otp">
                    <span class="toggle_btn"></span>
                    <span><?php esc_attr_e('OTP for Registration', $this->plugin_name); ?></span>
                </label>
            </li>

        </ol>

		<?php submit_button(__('Save all changes', $this->plugin_name), 'primary', 'submit', true); ?>
    </form>
</div>