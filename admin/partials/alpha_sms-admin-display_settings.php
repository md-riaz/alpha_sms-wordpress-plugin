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

  <h2> <span class="dashicons dashicons-admin-tools"></span> Alpha SMS
    <?php esc_attr_e('Options', $this->plugin_name); ?></h2>
  <p>Here you can set all the options for using the API</p>
  <form method="post" name="<?php echo $this->plugin_name; ?>" action="options.php">
    <?php
    //Grab all options
    $options = get_option($this->plugin_name);

    $api_key = (isset($options['api_key']) && !empty($options['api_key'])) ? esc_attr($options['api_key']) : 'Enter Your API Key';
    $example_textarea = (isset($options['example_textarea']) && !empty($options['example_textarea'])) ? sanitize_textarea_field($options['example_textarea']) : 'default';
    $example_checkbox = (isset($options['example_checkbox']) && !empty($options['example_checkbox'])) ? 1 : 0;

    settings_fields($this->plugin_name);
    do_settings_sections($this->plugin_name);

    ?>

    <!-- API Key -->
    <table class="form-table">
      <tr valign="top">
        <th scope="row">
          <label
            for="<?php echo $this->plugin_name; ?>-api_key"><?php esc_attr_e('API Key', $this->plugin_name); ?></label>
        </th>
        <td>
          <input id="<?php echo $this->plugin_name; ?>-api_key" name="<?php echo $this->plugin_name; ?>[api_key]"
            type="text" size="50"
            value="<?php if (!empty($api_key)) echo $api_key;

                                                                                                                                                else echo 'default'; ?>" />
        </td>
      </tr>
    </table>

    <hr>
    <!-- Checkbox -->
    <fieldset>
      <p><?php esc_attr_e('Example Checkbox.', $this->plugin_name); ?></p>
      <legend class="screen-reader-text">
        <span><?php esc_attr_e('Example Checkbox', $this->plugin_name); ?></span>
      </legend>
      <label for="<?php echo $this->plugin_name; ?>-example_checkbox">
        <input type="checkbox" id="<?php echo $this->plugin_name; ?>-example_checkbox"
          name="<?php echo $this->plugin_name; ?>[example_checkbox]" value="1"
          <?php checked($example_checkbox, 1); ?> />
        <span><?php esc_attr_e('Example Checkbox', $this->plugin_name); ?></span>
      </label>
    </fieldset>

    <?php submit_button(__('Save all changes', $this->plugin_name), 'primary', 'submit', TRUE); ?>
  </form>
</div>