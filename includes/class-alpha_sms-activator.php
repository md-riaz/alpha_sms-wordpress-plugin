<?php

/**
 * Fired during plugin activation
 *
 * @link       https://alpha.net.bd
 * @since      1.0.0
 *
 * @package    Alpha_sms
 * @subpackage Alpha_sms/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Alpha_sms
 * @subpackage Alpha_sms/includes
 * @author     Alpha Net Developer Team <support@alpha.net.bd>
 */
class Alpha_sms_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		// create otp information table in db
		global $wpdb;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$charset_collate = $wpdb->get_charset_collate();

		$table_name = $wpdb->prefix . 'alpha_sms_login_register_actions';
		if ($wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") != $table_name) {
			$create_wpsmstootp_login_register_actions = ( "CREATE TABLE IF NOT EXISTS {$table_name}(
            `id` int(11) NOT NULL auto_increment,
            `action` varchar(20),
            `user_id` int(11),
            `user_login` varchar(20),
            `user_email` varchar(30),
            `phone` varchar(20),
            `passcode` varchar(20),
            `ip` varchar(20),
            `datetime` datetime,
            PRIMARY KEY(`id`)) $charset_collate" );

			dbDelta($create_wpsmstootp_login_register_actions);
		}
	}

}
