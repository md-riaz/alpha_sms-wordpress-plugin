<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://alpha.net.bd
 * @since      1.0.0
 *
 * @package    Alpha_sms
 * @subpackage Alpha_sms/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Alpha_sms
 * @subpackage Alpha_sms/public
 * @author     Alpha Net Developer Team <support@alpha.net.bd>
 */
class Alpha_sms_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;
	private $options;
	private $order_alerts;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 * @since    1.0.0
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->options = get_option($this->plugin_name);
		$this->order_alerts = [
			'DEFAULT_BUYER_SMS_PENDING'        => sprintf(__('Hello %s, you are just one step away from placing your order, please complete your payment, to proceed.', $this->plugin_name), '[billing_first_name]'),
			'DEFAULT_BUYER_SMS_PROCESSING'     => sprintf(__('Hello %1$s, thank you for placing your order %2$s with %3$s.', $this->plugin_name), '[billing_first_name]', '#[order_id]', '[store_name]'),
			'DEFAULT_BUYER_SMS_COMPLETED'      => sprintf(__('Hello %1$s, your order %2$s with %3$s has been dispatched and shall deliver to you shortly.', $this->plugin_name), '[billing_first_name]', '#[order_id]', '[store_name]', PHP_EOL, PHP_EOL),
			'DEFAULT_BUYER_SMS_ON_HOLD'        => sprintf(__('Hello %1$s, your order %2$s with %3$s has been put on hold, our team will contact you shortly with more details.', $this->plugin_name), '[billing_first_name]', '#[order_id]', '[store_name]'),
			'DEFAULT_BUYER_SMS_CANCELLED'      => sprintf(__('Hello %1$s, your order %2$s with %3$s has been cancelled due to some un-avoidable conditions. Sorry for the inconvenience caused.', $this->plugin_name), '[billing_first_name]', '#[order_id]', '[store_name]'),
			'DEFAULT_ADMIN_SMS_STATUS_CHANGED' => sprintf(__('%1$s: status of order %2$s has been changed to %3$s.', $this->plugin_name), '[store_name]', '#[order_id]', '[order_status]')
		];
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Alpha_sms_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Alpha_sms_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/alpha_sms-public.css', [], $this->version, 'all');

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Alpha_sms_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Alpha_sms_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/alpha_sms-public.js', ['jquery'], $this->version, false);

	}

	/**
	 * Woocommerce show phone number on register page and my account
	 */

	public function wc_phone_on_reg()
	{
		$user = wp_get_current_user();
		$value = isset($_POST['billing_phone']) ? esc_attr($_POST['billing_phone']) : $user->billing_phone;
		?>

        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="reg_billing_phone"><?php _e('Phone', 'woocommerce'); ?> <span class="required">*</span>
            </label>
            <input type="tel" minlength="11" maxlength="11" class="input-text" name="billing_phone"
                   id="reg_billing_phone" value="<?php echo $value ?>"/>
        </p>
        <div class="clear"></div>

		<?php
	}

	public function wc_registration_field_validation($errors)
	{
		if (isset($_POST['billing_phone']) && empty($_POST['billing_phone'])) {
			$errors->add('billing_phone_error', __('<strong>Error</strong>: account number is required!', 'woocommerce'));
		}

		return $errors;
	}

	public function wc_save_account_registration_field($customer_id)
	{
		if (isset($_POST['billing_phone'])) {
			update_user_meta($customer_id, 'billing_phone', sanitize_text_field($_POST['billing_phone']));
		}
	}

	public function wc_save_my_account_billing_phone($user_id)
	{
		if (isset($_POST['billing_phone'])) {
			update_user_meta($user_id, 'billing_phone', sanitize_text_field($_POST['billing_phone']));
		}
	}

	/**
	 * Phone field on WordPress Reg page
	 */
	public function wp_phone_on_register()
	{
		$billing_phone = (!empty($_POST['billing_phone'])) ? sanitize_text_field($_POST['billing_phone']) : '';

		?>
        <p>
            <label for="billing_phone"><?php _e('Phone', $this->plugin_name) ?><br/>
                <input type="text" name="billing_phone" id="billing_phone" class="input"
                       value="<?php echo esc_attr($billing_phone); ?>" size="25"/></label>
        </p>
		<?php
	}

	/**
	 * @param $errors
	 * @param $sanitized_user_login
	 * @param $user_email
	 * @return mixed
	 */
	public function wp_phone_field_validation($errors, $sanitized_user_login, $user_email)
	{
		if (empty($_POST['billing_phone']) || !is_numeric($_POST['billing_phone']) || !$this->validateNumber(sanitize_text_field($_POST['billing_phone']))) {
			$errors->add('phone_error', sprintf('<strong>%s</strong>: %s', __('ERROR', $this->plugin_name), __('You phone number is not valid.', $this->plugin_name)));
		}

		$billing_phone = sanitize_text_field($_POST['billing_phone']);

		if ($this->check_duplicate_billing_phone($billing_phone)) {
			$errors->add('duplicate_phone_error', sprintf('<strong>%s</strong>: %s', __('ERROR', $this->plugin_name), __('This mobile_phone is already registered, please choose another one.', $this->plugin_name)));
		}

		if (!empty($errors->errors)) return $errors;

		return $this->startOTPTransaction($errors, $sanitized_user_login, $user_email, $billing_phone);
	}

	/**
	 * Validate Bangladeshi phone number format
	 * @param $num
	 * @return false|int|string
	 */
	public function validateNumber($num)
	{
		if (!$num) {
			return false;
		}

		$num = ltrim(trim($num), "+88");
		$number = '88' . ltrim($num, "88");

		$ext = ["88017", "88013", "88016", "88015", "88018", "88019", "88014"];
		if (is_numeric($number) && strlen($number) === 13 && in_array(substr($number, 0, 5), $ext, true)) {
			return $number;
		}

		return false;
	}

	/**
	 * check duplicate phone number in database
	 * @param $phone_number
	 */
	public function check_duplicate_billing_phone($phone_number)
	{
		global $wpdb;
		$result = $wpdb->get_results("SELECT * from `{$wpdb->prefix}usermeta` WHERE meta_key = 'mobile_phone' AND REPLACE(meta_value, ' ', '') = '{$phone_number}'");

		if ($result) return true;

		return false;
	}

	/**
	 * @param $errors
	 * @param $sanitized_user_login
	 * @param $user_email
	 * @param $billing_phone
	 * @return mixed
	 */
	public function startOTPTransaction($errors, $sanitized_user_login, $user_email, $billing_phone)
	{
		if (!isset($_POST['register_nonce'])) return $errors;

		$_SESSION['otp_session'] = 'default_wp_registration';

		$this->sendChallenge($errors, $sanitized_user_login, $user_email, $billing_phone);

		return $errors;
	}

	/**
	 * @param $errors
	 * @param $user_login
	 * @param $user_email
	 * @param $billing_phone
	 */
	public function sendChallenge($errors, $user_login, $user_email, $billing_phone)
	{
		do_action($this->plugin_name . '_generate_otp', $errors, $user_login, $user_email, $billing_phone);
	}

	/**
	 * @param $user_id
	 */
	public function wp_user_register($user_id)
	{
		if (!empty($_POST['billing_phone'])) {
			update_user_meta($user_id, 'billing_phone', sanitize_text_field($_POST['billing_phone']));
		}
	}

	/**
	 * @param $errors
	 * @param $user_login
	 * @param $user_email
	 * @param $billing_phone
	 * @param string $password
	 */
	public function otp_challenge($errors, $user_login, $user_email, $billing_phone, $password = '')
	{

		$_SESSION['current_url'] = $this->currentPageUrl();
		$_SESSION['user_login'] = $user_login;
		$_SESSION['user_email'] = $user_email;
		$_SESSION['user_password'] = $password;
		$_SESSION['billing_phone'] = $billing_phone;

		$this->handleOTPAction($user_login, $user_email, $billing_phone);
	}

	/**
	 * @return mixed
	 */
	public function currentPageUrl()
	{
		global $wp;

		return add_query_arg($wp->query_vars, home_url($wp->request));
	}

	/**
	 * @param $user_login
	 * @param $user_email
	 * @param $billing_phone
	 */
	public function handleOTPAction($user_login, $user_email, $billing_phone)
	{

		$otp_template = 'An OTP Code has been sent to ##phone##';
		$message = str_replace("##phone##", $billing_phone, $otp_template);

		if (!headers_sent()) header('Content-Type: text/html; charset=utf-8');

		$htmlContent = "
                <div class='otp-container'>
                    <h1>ENTER OTP</h1>
                    <p>{$message}</p>
                    <form action='" . admin_url('admin-ajax.php') . "' method='post' id='otp_form'>
                    " . wp_nonce_field('wp_otp_action', $this->plugin_name) . "
                    <input name='action' value='wp_otp_action' type='hidden'>
                        <div class='userInput'/>
                            <input type='number' id='otp_code' required autofocus />
                        </div>
                        <button type='submit'>CONFIRM</button>
                    </form>
                    <script>(function($){
                        $('#site-header').remove()
                        })(jQuery);
                    </script>
                </div>
";

		echo get_header() . $htmlContent;
		exit();
	}

	/**
	 *
	 */
	public function process_otp_action()
	{
		if (empty($_POST) || !wp_verify_nonce($_POST[$this->plugin_name], 'wp_otp_action')) {
			echo 'You targeted the right function, but sorry, your nonce did not verify.';
			die();
		}

		// do your function here
		echo json_encode(['status' => '200']);
	}

	/**
	 * Get $_POST and $_GET data on any form submit
	 */
	public function handle_otp_form_action()
	{

	}

	public function woo_new_order($order_id)
	{
		if (!$order_id) {
			return;
		}

		$this->woo_order_status_change($order_id, 'pending', 'pending');
	}

	/**
	 * This method is executed after order is placed.
	 *
	 * @param $order_id
	 * @param $old_status
	 * @param $new_status
	 */
	public function woo_order_status_change($order_id, $old_status, $new_status)
	{
		if (!$order_id) {
			return;
		}

		$order = new WC_Order($order_id);

		// Get the Customer billing phone
		$billing_phone = $order->get_billing_phone();

		//we will send sms

		$buyer_msg = $this->order_alerts['DEFAULT_BUYER_SMS_' . str_replace(' ', '_', strtoupper($new_status))];

		$search = [
			'[store_name]',
			'[billing_first_name]',
			'[order_id]',
			'[order_status]'
		];

		$replace = [
			get_bloginfo(),
			$order->get_billing_first_name(),
			$order_id,
			$new_status
		];

		$buyer_msg = str_replace($search, $replace, $buyer_msg);

		$response = $this->SendSMS($billing_phone, $buyer_msg);

		if ($response->error === 0) {
			$order->add_order_note(__('SMS Send to buyer Successfully.', $this->plugin_name));
		} else {
			$order->add_order_note(__('Could not send sms to buyer', $this->plugin_name));
		}

		// send sms to all admins
		$admin_phones = $this->admin_phones();

		if (!empty($admin_phones)) {
			$numbers = implode(',', $admin_phones);

			$admin_msg = $this->order_alerts['DEFAULT_ADMIN_SMS_STATUS_CHANGED'];

			$admin_msg = str_replace($search, $replace, $admin_msg);

			$this->SendSMS($numbers, $admin_msg);
		}


	}

	/**
	 * Send SMS via sms api
	 *
	 * @param $to
	 * @param $body
	 * @return false|mixed
	 */
	public function SendSMS($to, $body)
	{
		$api_key = !empty($this->options['api_key']) ? $this->options['api_key'] : '';
		$sender_id = !empty($this->options['sender_id']) ? trim($this->options['sender_id']) : '';

		require_once plugin_dir_path(__DIR__) . 'includes/sms.class.php';

		$sms = new AlphaSMS($api_key);
		$sms->numbers = $to;
		$sms->body = $body;
		$sms->sender_id = $sender_id;

		return $sms->Send();
	}

	/**
	 * @return array
	 */
	public function admin_phones()
	{
		$admin_ids = get_users(['fields' => 'ID', 'role' => 'administrator']);
		$numbers = [];
		foreach ($admin_ids as $userid) {
			$number = $this->validateNumber(get_user_meta($userid, 'mobile_phone', true));
			if ($number) {
				$numbers[] = $number;
			}
		}

		return $numbers;
	}


	public function woocommerce_login_form_end()
	{
        return;
    }




	/**
	 * WordPress login with Phone Number methods
	 *
	 */

	public function login_enqueue_styles()
	{
		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/otp-login-form.css', [], $this->version, 'all');
		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/otp-login-form.js', ['jquery'], $this->version, false);
		wp_localize_script(
			$this->plugin_name,
			$this->plugin_name . '_object',
			['ajaxurl' => admin_url('admin-ajax.php')]
		);
	}


	/**
	 * Add OTP view in login form
	 *
	 */
	public function add_otp_in_login_form()
	{
		require_once('partials/' . $this->plugin_name . '-otp-login-form.php');
	}


	/**
	 * Verify number and send otp
	 *
	 */
	public function save_and_send_otp_login()
	{
		global $wpdb;

		// First check the nonce, if it fails the function will break
		check_ajax_referer('ajax-login-nonce', $this->plugin_name);

		//Nonce is checked, get the POST data and sign user on
		$info = [];
		$info['user_login'] = $_POST['log'];
		$info['user_password'] = $_POST['pwd'];
		$info['remember'] = $_POST['rememberme'];

		$userdata = get_user_by('login', $info['user_login']);

		if (!$userdata) {
			$userdata = get_user_by('email', $info['user_login']);
		}
		// wp_authenticate()
		$user_id = $userdata->data->ID;

		$result = wp_check_password($info['user_password'], $userdata->data->user_pass, $user_id);

		if (!$result) {
			$response = ['status' => 401, 'message' => __('Wrong username or password!')];
			echo wp_kses_post(json_encode($response));
			wp_die();
			exit;
		}

		$user_login = $userdata->data->user_login;
		$user_email = $userdata->data->user_email;
		$user_phone = get_user_meta($user_id, 'mobile_phone', true);

		if (!$user_phone) {
			$response = ['status' => 402, 'message' => __('No phone number found')];
			echo wp_kses_post(json_encode($response));
			wp_die();
			exit;
		}

		$api_key = !empty($this->options['api_key']) ? $this->options['api_key'] : '';
		$sender_id = !empty($this->options['sender_id']) ? trim($this->options['sender_id']) : '';

		$ip = $this->getClientIP();
		$action = 'Login';

		//we will send sms
		$otp_code = $this->generateOTP();

		$number = $user_phone;
		$body = $otp_code . ' is your one time password to login. It is valid for 2 minutes.';

		require_once plugin_dir_path(__DIR__) . 'includes/sms.class.php';

		$sms = new AlphaSMS($api_key);
		$sms->numbers = $number;
		$sms->body = $body;
		$sms->sender_id = $sender_id;

		$sms_response = $sms->Send();

		if ($sms_response->error === 0) {
			// save info in database for later verification
			$this->log_login_register_action($user_id, $user_login, $user_email, $user_phone, $otp_code, $ip, $action);
			$response = ['status' => 200, 'message' => 'Please enter the verification code sent to your phone.'];
			echo wp_kses_post(json_encode($response));
			exit;
		}

		$response = ['status' => '400', 'message' => $sms_response->msg];
		echo wp_kses_post(json_encode($response));
		exit;
		wp_die();
	}

	/**
	 * Get client IP Address
	 * @return mixed|string
	 */
	public function getClientIP()
	{
		$ipaddress = '';
		if (isset($_SERVER['HTTP_CLIENT_IP']))
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if (isset($_SERVER['HTTP_X_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		else if (isset($_SERVER['HTTP_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		else if (isset($_SERVER['REMOTE_ADDR']))
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		else
			$ipaddress = 'UNKNOWN';

		return $ipaddress;
	}

	/**
	 * Generate 6 digit otp code
	 * @return string
	 */
	public function generateOTP()
	{
		$otp = '';

		for ($i = 0; $i < 6; $i++) {
			$otp .= mt_rand(0, 9);
		}

		return $otp;
	}

	/**
	 * after sending otp to user, log the otp and data in db
	 *
	 * @param $user_id
	 * @param $user_login
	 * @param $user_email
	 * @param $mobile_phone
	 * @param $otp_code
	 * @param $ip
	 * @param $action
	 * @return mixed
	 */
	public function log_login_register_action($user_id, $user_login, $user_email, $mobile_phone, $otp_code, $ip, $action)
	{
		global $wpdb;

		$dateTime = new DateTime(TIMESTAMP);
		$dateTime->modify('+2 minutes');

		return $wpdb->insert(
			$wpdb->prefix . "alpha_sms_login_register_actions",
			[
				'action'     => $action,
				'user_id'    => $user_id,
				'user_login' => $user_login,
				'user_email' => $user_email,
				'phone'      => $mobile_phone,
				'passcode'   => $otp_code,
				'ip'         => $ip,
				'datetime'   => $dateTime->format('Y-m-d H:i:s')
			]
		);
	}

	/**
	 * Login the user
	 *
	 * @param $user
	 * @param $username
	 * @param $password
	 * @return User|WP_Error
	 */
	public function login_user($user, $username, $password)
	{
		global $wpdb;

		if (empty($user->data)) {
			return $user;
		}

		$user_phone = get_user_meta($user->data->ID, 'mobile_phone', true);

		if (!$user_phone) {
			return $user;
		}

		if (empty($_REQUEST['otp_code'])) {
			$error = new WP_Error();

			$error->add('empty_password', __('<strong>Error</strong>: Wrong username or password!', $this->plugin_name));

			return $error;
		}

		$otp_code = $wpdb->_escape($_REQUEST['otp_code']);

		$email = $user->data->user_email;
		$action = 'Login';

		$valid_user = $this->authenticate_otp($username, $action, $otp_code);

		if ($valid_user) {
			$this->deletePastdata($username, $email, $action);

			return $user;
		}

		return new WP_Error(
			'invalid_password',
			__('OTP is not valid', $this->plugin_name)
		);

	}

	/**
	 * Select otp from db and compare
	 *
	 * @param $username
	 * @param $action
	 * @param $otp_code
	 * @return bool
	 */
	public function authenticate_otp($username, $action, $otp_code)
	{
		global $wpdb;
		$ip = $this->getClientIP();

		$passcode = $wpdb->get_var("SELECT passcode FROM `{$wpdb->prefix}alpha_sms_login_register_actions` WHERE `action` = '$action' AND (`user_login` = '$username' OR `user_email` = '$username') AND `ip` = '$ip' AND `datetime` > '" . TIMESTAMP . "' ORDER BY id DESC LIMIT 1");

		// check otp is correct or not
		return (!empty($passcode) && $otp_code === $passcode);
	}

	/**
	 * delete db data of current ip address user
	 *
	 * @param $user_login
	 * @param $user_email
	 * @param $action
	 */
	public function deletePastdata($user_login, $user_email, $action)
	{
		global $wpdb;
		$ip = $this->getClientIP();

		$wpdb->query(
			$wpdb->prepare(
				"DELETE FROM {$wpdb->prefix}alpha_sms_login_register_actions WHERE action=%s AND (user_login=%s OR user_email=%s OR ip=%s)", $action, $user_login, $user_email, $ip
			)
		);
	}

	/**
	 * Get user from database with field name and field data
	 * @param $db_field
	 * @param $mobile_phone
	 * @return null
	 */
	public function get_user_by_mobile_phone($db_field, $mobile_phone)
	{
		global $wpdb;

		$user_id = $wpdb->get_row(
			$wpdb->prepare("SELECT user_id FROM $wpdb->prefix" . "usermeta WHERE meta_key = %s AND REPLACE(meta_value, ' ', '') = %d LIMIT 1", $db_field, $mobile_phone)
		);

		if ($user_id) {
			$array = json_decode(json_encode($user_id), true);

			return get_user_by('ID', $array ["user_id"]);
		}

		return null;
	}

}
