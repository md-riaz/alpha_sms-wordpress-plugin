<?php

// If this file is called directly, abort.
    if ( ! defined('WPINC')) {
        die;
    }

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
    class Alpha_sms_Public
    {

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
        /**
         * @var false
         */
        private $pluginActive;

        /**
         * Initialize the class and set its properties.
         *
         * @param  string  $plugin_name  The name of the plugin.
         * @param  string  $version      The version of this plugin.
         *
         * @since    1.0.0
         */
        public function __construct($plugin_name, $version)
        {
            $this->plugin_name = $plugin_name;
            $this->version = $version;
            $this->options = get_option($this->plugin_name);
            $this->pluginActive = ! empty($this->options['api_key']) && $this->checkAPI($this->options['api_key']);
        }

        /**
         * Check if entered api key is valid or not
         *
         * @return bool
         */
        private function checkAPI($api_key)
        {
            require_once ALPHA_SMS_PATH . 'includes/sms.class.php';

            $smsPortal = new AlphaSMS($api_key);

            $response = $smsPortal->getBalance();

            return $response && $response->error === 0;
        }

        /**
         * @return void
         * @since 1.0.0
         * start session if not started
         */
        public function start_session_wp()
        {
            if ( ! session_id()) {
                session_start();
            }
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

            wp_enqueue_style(
                $this->plugin_name,
                plugin_dir_url(__FILE__) . 'css/alpha_sms-public.css',
                [],
                $this->version,
                'all'
            );
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

            wp_enqueue_script(
                $this->plugin_name,
                plugin_dir_url(__FILE__) . 'js/alpha_sms-public.js',
                ['jquery'],
                $this->version,
                false
            );

            // adding a js variable for ajax form submit url
            wp_localize_script(
                $this->plugin_name,
                $this->plugin_name . '_object',
                ['ajaxurl' => admin_url('admin-ajax.php')]
            );
        }

        /**
         * Woocommerce
         * show phone number on register page and my account
         */
        public function wc_phone_on_register()
        {
            if ( ! $this->pluginActive || ! $this->options['wc_reg']) {
                return;
            }

            $user = wp_get_current_user();
            $value = isset($_POST['billing_phone']) ? sanitize_text_field($_POST['billing_phone'])
                : $user->billing_phone;
            ?>

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="reg_billing_phone"><?php _e('Phone', 'woocommerce'); ?> <span class="required">*</span>
                </label>
                <input type="tel" minlength="11" maxlength="11" class="input-text" name="billing_phone"
                       id="reg_billing_phone"
                       value="<?php echo esc_attr($value) ?>" required/>
            </p>
            <div class="clear"></div>

            <?php
        }

        /**
         *  Default WordPress
         * show otp form in registration form
         */
        public function add_otp_field_on_wp_reg_form()
        {
            if ( ! $this->pluginActive || ! $this->options['wp_reg']) {
                return;
            }
            require_once('partials/add-otp-on-login-form.php');
            ?>
            <input type='hidden' name='action_type' id='action_type' value='wp_reg'/>
            <?php
        }

        /**
         *  Woocommerce
         * show otp form in registration form
         */
        public function add_otp_field_on_wc_reg_form()
        {
            if ( ! $this->pluginActive || ! $this->options['wc_reg']) {
                return;
            }

            require_once('partials/add-otp-on-wc-reg-form.php');
            ?>
            <input type='hidden' name='action_type' id='action_type' value='wc_reg'/>
            <?php
        }

        /**
         * Woocommerce + Default WordPress
         * ajax otp send on post phone number *
         */
        public function send_otp_for_reg()
        {
            $user_phone = $user_email = '';

            if (isset($_POST['billing_phone'], $_POST['email'])) {
                $user_phone = $this->validateNumber(sanitize_text_field($_POST['billing_phone']));
                $user_email = sanitize_text_field($_POST['email']);
            }

            if ( ! $user_email && ! empty($_POST['billing_email'])) {
                $user_email = sanitize_text_field($_POST['billing_email']);
            }

            if ( ! filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
                $response = ['status' => 400, 'message' => __('The email address you entered is not valid!')];
                echo wp_kses_post(json_encode($response));
                wp_die();
                exit;
            }

            if (isset($_POST['password']) && empty($_POST['password']) && strlen($_POST['password']) < 8) {
                $response = ['status' => 400, 'message' => __('Weak - Please enter a stronger password.')];
                echo wp_kses_post(json_encode($response));
                wp_die();
                exit;
            }

            if ( ! $user_phone) {
                $response = ['status' => 400, 'message' => __('The phone number you entered is not valid!')];
                echo wp_kses_post(json_encode($response));
                wp_die();
                exit;
            }

            //we will send sms
            $otp_code = $this->generateOTP();

            $body = 'Your OTP for Registration is ' . $otp_code . ' . Only valid for 2 min.';

            if ( ! empty($_POST['action_type']) && $_POST['action_type'] === 'wc_checkout') {
                $body = 'Your OTP for Order Checkout is ' . $otp_code . ' . Only valid for 2 min.';
            }

            $sms_response = $this->SendSMS($user_phone, $body);

            if ($sms_response->error === 0) {
                // save info in database for later verification
                if ($this->log_login_register_action(
                    $user_phone,
                    $otp_code
                )) {
                    $response = [
                        'status'  => 200,
                        'message' => 'A OTP (One Time Passcode) has been sent. Please enter the OTP in the field below to verify your phone.',
                    ];
                } else {
                    $response = ['status' => 400, 'message' => __('Error occurred while sending OTP. Please try again.')];
                }

                echo wp_kses_post(json_encode($response));
                wp_die();
                exit;
            }

            $response = ['status' => '400', 'message' => __('Error occurred while sending OTP. Contact Administrator.')];
            echo wp_kses_post(json_encode($response));
            wp_die();
            exit;
        }

        /**
         * Validate Bangladeshi phone number format
         *
         * @param $num
         *
         * @return false|int|string
         */
        public function validateNumber($num)
        {
            if ( ! $num) {
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
         * Generate 6 digit otp code
         *
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
         * Send SMS via sms api
         *
         * @param $to
         * @param $body
         *
         * @return false|mixed
         */
        public function SendSMS($to, $body)
        {
            if ( ! $this->pluginActive) {
                return false;
            }

            $api_key = ! empty($this->options['api_key']) ? $this->options['api_key'] : '';
            $sender_id = ! empty($this->options['sender_id']) ? trim($this->options['sender_id']) : '';

            require_once ALPHA_SMS_PATH . 'includes/sms.class.php';

            $sms = new AlphaSMS($api_key);
            $sms->numbers = $to;
            $sms->body = $body;
            $sms->sender_id = $sender_id;

            return $sms->Send();
        }

        /**
         * after sending otp to user, log the otp and data in db
         *
         * @param $mobile_phone
         * @param $otp_code
         *
         * @return bool
         */
        public function log_login_register_action(
            $mobile_phone,
            $otp_code
        ) {
            $dateTime = new DateTime(ALPHA_SMS_TIMESTAMP);
            $dateTime->modify('+2 minutes');

            $_SESSION['alpha_sms_otp_code'] = $otp_code;
            $_SESSION['alpha_sms_expires'] = $dateTime->format('Y-m-d H:i:s');

            if ( ! empty($_SESSION['alpha_sms_otp_code'])) {
                return true;
            }

            return false;
        }

        /**
         * Verify otp and register the user
         *
         * @param $customer_id
         */
        public function register_the_customer($customer_id)
        {
            if ( ! $this->pluginActive || ! $this->options['wp_reg'] || ! $this->options['wc_reg']) {
                return;
            }
            if (isset($_POST['billing_phone']) && $this->validateNumber(sanitize_text_field($_POST['billing_phone']))) {
                update_user_meta(
                    $customer_id,
                    'billing_phone',
                    sanitize_text_field($this->validateNumber($_POST['billing_phone']))
                );
            }
        }

        /**
         * Default WordPress
         * show phone number on register page
         */
        public function wp_phone_on_register()
        {
            if ( ! $this->pluginActive || ! $this->options['wp_reg']) {
                return;
            }

            $billing_phone = ( ! empty($_POST['billing_phone'])) ? sanitize_text_field($_POST['billing_phone']) : '';

            ?>
            <p>
                <label for="billing_phone"><?php _e('Phone', $this->plugin_name) ?><br/>
                    <input type="text" name="billing_phone" id="reg_billing_phone" class="input"
                           value="<?php echo esc_attr($billing_phone); ?>" size="25"/></label>
            </p>
            <?php
        }


        /**
         * WordPress validate phone and validate otp
         *
         * @param $errors
         * @param $sanitized_user_login
         * @param $user_email
         *
         * @return mixed
         */
        public function wp_register_form_validation($errors, $sanitized_user_login, $user_email)
        {
            if ($this->pluginActive && $this->options['wp_reg'] && ! empty($_POST['action_type']) &&
                $_POST['action_type'] === 'wp_reg') {
                $this->register_form_validation($errors, $sanitized_user_login, $user_email);
            }

            return $errors;
        }

        /**
         * Register Form Validation
         *
         * @param $errors
         * @param $sanitized_user_login
         * @param $user_email
         *
         * @return mixed
         */
        public function register_form_validation($errors, $sanitized_user_login, $user_email)
        {
            if (empty($_REQUEST['billing_phone']) || ! is_numeric($_REQUEST['billing_phone']) ||
                ! $this->validateNumber(sanitize_text_field($_REQUEST['billing_phone']))) {
                $errors->add('phone_error', __('You phone number is not valid.', $this->plugin_name));
            }

            $billing_phone = $this->validateNumber(sanitize_text_field($_REQUEST['billing_phone']));

            $hasPhoneNumber = get_users('meta_value=' . $billing_phone);

            if ( ! empty($hasPhoneNumber)) {
                $errors->add('duplicate_phone_error', __('Mobile number is already used!', $this->plugin_name));
            }

            if ( ! empty($_REQUEST['otp_code'])) {
                $otp_code = sanitize_text_field($_REQUEST['otp_code']);

                $email = sanitize_email($user_email);
                $action = 'Registration';

                $valid_user = $this->authenticate_otp(trim($otp_code));

                if ($valid_user) {
                    $this->deletePastData();

                    return $errors;
                }
            }
            // otp validation failed or no otp provided
            $errors->add('otp_error', __('Invalid OTP entered!', $this->plugin_name));

            return $errors;
        }

        /**
         * Select otp from db and compare
         *
         * @param $username
         * @param $action
         * @param $otp_code
         *
         * @return bool
         */
        public function authenticate_otp( $otp_code)
        {
            if ( ! empty($_SESSION['alpha_sms_otp_code']) && ! empty($_SESSION['alpha_sms_expires'])) {
                if (strtotime($_SESSION['alpha_sms_expires']) > strtotime(ALPHA_SMS_TIMESTAMP)) {
                    if ($otp_code === $_SESSION['alpha_sms_otp_code']) {
                        return true;
                    }
                }
            }

            return false;
        }

        /**
         * delete db data of current ip address user
         *
         * @param $user_login
         * @param $user_email
         * @param $action
         */
        public function deletePastData()
        {
            if (isset($_SESSION['alpha_sms_otp_code'], $_SESSION['alpha_sms_expires'])) {
                unset($_SESSION['alpha_sms_otp_code'], $_SESSION['alpha_sms_expires']);
            }
        }

        /**
         * Woocommerce validate phone and validate otp
         *
         * @param $errors
         * @param $sanitized_user_login
         * @param $user_email
         *
         * @return mixed
         */
        public function wc_register_form_validation($errors, $sanitized_user_login, $user_email)
        {
            if ( ! $this->pluginActive) {
                return $errors;
            }

            if ($this->options['otp_checkout'] || ($this->options['wc_reg'] && $_POST['action_type'] === 'wc_reg')) {
                $this->register_form_validation($errors, $sanitized_user_login, $user_email);
            }

            return $errors;
        }

        /**
         * Alert customer and admins when a new order is placed
         *
         * @param $order_id
         */
        public function wc_new_order_alert($order_id)
        {
            if ( ! $order_id) {
                return;
            }

            // option not enabled
            if ( ! $this->pluginActive || ! $this->options['order_status_buyer'] ||
                 ! $this->options['order_status_admin']) {
                return;
            }

            $this->wc_order_status_change_alert($order_id, 'pending', 'pending');


            // send sms to all admins if enabled
            if ($this->options['order_status_admin']) {
                $order = new WC_Order($order_id);

                $admin_msg = $this->options['ADMIN_STATUS_SMS'];

                $search = [
                    '[store_name]',
                    '[billing_first_name]',
                    '[order_id]',
                    '[order_status]',
                    '[order_currency]',
                    '[order_amount]',
                ];

                $replace = [
                    get_bloginfo(),
                    $order->get_billing_first_name(),
                    $order_id,
                    'pending',
                    $order->get_currency(),
                    $order->get_total(),
                ];

                $admin_msg = str_replace($search, $replace, $admin_msg);

                // if admin phone is not provided then send to all admins
                $admin_phones[] = $this->options['admin_phones'];

                if (empty($admin_phones)) {
                    $admin_phones = $this->admin_phones();
                }

                if ( ! empty($admin_phones)) {
                    $numbers = implode(',', $admin_phones);
                    $this->SendSMS($numbers, $admin_msg);
                }
            }
        }


        /**
         * Alert customer and user when order status changes
         *
         * @param $order_id
         * @param $old_status
         * @param $new_status
         */
        public function wc_order_status_change_alert($order_id, $old_status, $new_status)
        {
            if ( ! $order_id) {
                return;
            }

            // option not enabled
            if ( ! $this->pluginActive || ! $this->options['order_status_buyer'] ||
                 ! $this->options['order_status_admin']) {
                return;
            }

            $order = new WC_Order($order_id);

            // Get the Customer billing phone
            $billing_phone = $order->get_billing_phone();

            //we will send sms

            $buyer_msg = $this->options['BUYER_STATUS_SMS'];

            $search = [
                '[store_name]',
                '[billing_first_name]',
                '[order_id]',
                '[order_status]',
                '[order_currency]',
                '[order_amount]',
            ];

            $replace = [
                get_bloginfo(),
                $order->get_billing_first_name(),
                $order_id,
                $new_status,
                $order->get_currency(),
                $order->get_total(),
            ];

            $buyer_msg = str_replace($search, $replace, $buyer_msg);

            // if buyer notification is enabled,
            if ($this->options['order_status_buyer']) {
                $response = $this->SendSMS($billing_phone, $buyer_msg);

                if ($response->error === 0) {
                    $order->add_order_note(__('SMS Send to buyer Successfully.', $this->plugin_name));
                } else {
                    $order->add_order_note(__('Could not send sms to buyer', $this->plugin_name));
                }
            }
        }

        /**
         * Get all the phone number associated with administration role
         *
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

        /**
         * WordPress login with Phone Number methods
         *
         */

        public function login_enqueue_style()
        {
            if ($this->options['wp_login'] || $this->options['wp_reg']) {
                wp_enqueue_style(
                    $this->plugin_name,
                    plugin_dir_url(__FILE__) . 'css/otp-login-form.css',
                    [],
                    $this->version,
                    'all'
                );
            }
        }

        public function login_enqueue_script()
        {
            if ( ! $this->pluginActive) {
                return;
            }

            if ($this->options['wp_login'] || $this->options['wp_reg']) {
                wp_enqueue_script(
                    $this->plugin_name,
                    plugin_dir_url(__FILE__) . 'js/otp-login-form.js',
                    ['jquery'],
                    $this->version,
                    false
                );
                wp_localize_script(
                    $this->plugin_name,
                    $this->plugin_name . '_object',
                    ['ajaxurl' => admin_url('admin-ajax.php')]
                );
            }
        }

        /**
         * Add OTP view in Wp login form
         *
         */
        public function add_otp_field_in_wp_login_form()
        {
            if ( ! $this->pluginActive || ! $this->options['wp_login']) {
                return;
            }

            require_once('partials/add-otp-on-login-form.php');
            ?>
            <input type='hidden' name='action_type' id='action_type' value='wp_login'/>
            <?php
        }

        /**
         * Add OTP view in Wc login form
         *
         */
        public function add_otp_field_in_wc_login_form()
        {
            if ( ! $this->pluginActive || ! $this->options['wc_login']) {
                return;
            }
            require_once('partials/add-otp-on-login-form.php');
            ?>
            <input type='hidden' name='action_type' id='action_type' value='wc_login'/>
            <?php
        }


        /**
         * Verify number and send otp
         *
         */
        public function save_and_send_otp_login()
        {
            // First check the nonce, if it fails the function will break
            check_ajax_referer('ajax-login-nonce', $this->plugin_name);

            //Nonce is checked, get the POST data and sign user on
            $info = [];
            $info['user_login'] = sanitize_text_field($_POST['log']);
            $info['user_password'] = sanitize_text_field($_POST['pwd']);
            $info['remember'] = sanitize_text_field($_POST['rememberme']);

            $userdata = get_user_by('login', $info['user_login']);

            if ( ! $userdata) {
                $userdata = get_user_by('email', $info['user_login']);
            }
            // wp_authenticate()
            $user_id = $userdata->data->ID;

            $result = wp_check_password($info['user_password'], $userdata->data->user_pass, $user_id);

            if ( ! $user_id || ! $result) {
                $response = ['status' => 401, 'message' => __('Wrong username or password!')];
                echo wp_kses_post(json_encode($response));
                wp_die();
                exit;
            }

            $user_phone = get_user_meta($user_id, 'mobile_phone', true);

            if ( ! $user_phone) {
                $user_phone = get_user_meta($user_id, 'billing_phone', true);
            }

            // if user phone number is not valid then login without verification
            if ( ! $user_phone || ! $this->validateNumber($user_phone)) {
                $response = ['status' => 402, 'message' => __('No phone number found')];
                echo wp_kses_post(json_encode($response));
                wp_die();
                exit;
            }

            //we will send sms
            $otp_code = $this->generateOTP();

            $number = $user_phone;
            $body = $otp_code . ' is your one time password to login. Only valid for 2 min.';

            $sms_response = $this->SendSMS($number, $body);

            if ($sms_response->error === 0) {
                // save info in database for later verification
                $log_info = $this->log_login_register_action( $user_phone, $otp_code);

                if ($log_info) {
                    $response = ['status' => 200, 'message' => 'Please enter the verification code sent to your phone.'];
                } else {
                    $response = ['status' => 500, 'message' => 'Something went wrong. Please try again.'];
                }

                echo wp_kses_post(json_encode($response));
                exit;
            }

            $response = ['status' => '400', 'message' => 'Error sending Otp Code. Please contact administrator.'];
            echo wp_kses_post(json_encode($response));
            wp_die();
            exit;
        }

        /**
         * Login the user verifying otp code
         *
         * @param $user
         * @param $username
         *
         * @return User|WP_Error
         */
        public function login_user($user, $username)
        {
            if (empty($user->data)) {
                return $user;
            }
            if ( ! $this->pluginActive || ! $this->options['wp_login'] || ! $this->options['wc_login']) {
                return $user;
            }

            if (empty($_POST['action_type'])) {
                $error = new WP_Error();

                $error->add(
                    'empty_password',
                    __('<strong>Error</strong>: Authentication Error!', $this->plugin_name)
                );
            }

            if (($this->options['wp_login'] && $_POST['action_type'] == 'wp_login') ||
                ($this->options['wc_login'] && $_POST['action_type'] == 'wc_login')) {
                return $this->startOTPChallenge($user, $username);
            }

            return $user;
        }

        /**
         * @param $user
         * @param $username
         *
         * @return mixed|WP_Error
         */
        public function startOTPChallenge($user, $username)
        {
            $user_phone = get_user_meta($user->data->ID, 'mobile_phone', true);

            if ( ! $user_phone) {
                $user_phone = get_user_meta($user->data->ID, 'billing_phone', true);
            }

            if ( ! $user_phone || ! $this->validateNumber($user_phone)) {
                return $user;
            }

            if (empty($_REQUEST['otp_code'])) {
                $error = new WP_Error();

                $error->add(
                    'empty_password',
                    __('<strong>Error</strong>: Wrong OTP Code!', $this->plugin_name)
                );

                return $error;
            }

            $otp_code = sanitize_text_field($_REQUEST['otp_code']);

            $valid_user = $this->authenticate_otp( $otp_code);

            if ($valid_user) {
                $this->deletePastData();

                return $user;
            }

            return new WP_Error(
                'invalid_password',
                __('OTP is not valid', $this->plugin_name)
            );
        }

        /**
         * Woocommerce otp form in checkout
         */
        public function otp_form_at_checkout()
        {
            if ( ! $this->pluginActive || ! $this->options['otp_checkout']) {
                return;
            }

            if ( ! is_user_logged_in() && get_option('woocommerce_enable_signup_and_login_from_checkout')) {
                require_once('partials/add-otp-checkout-form.php');
                ?>
                <input type='hidden' name='action_type' id='action_type' value='wc_checkout'/>
                <?php
            }
        }

    }