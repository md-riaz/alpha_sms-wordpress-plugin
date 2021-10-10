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
            'DEFAULT_BUYER_SMS_PENDING'        => sprintf(__('Hello %s, you are just one step away from placing your order, please complete your payment, to proceed.',
                $this->plugin_name), '[billing_first_name]'),
            'DEFAULT_BUYER_SMS_ON_HOLD'        => sprintf(__('Hello %1$s, your order %2$s with %3$s has been put on hold, our team will contact you shortly with more details.',
                $this->plugin_name), '[billing_first_name]', '#[order_id]', '[store_name]'),
            'DEFAULT_BUYER_SMS_PROCESSING'     => sprintf(__('Hello %1$s, thank you for placing your order %2$s with %3$s. Your order is processing.',
                $this->plugin_name), '[billing_first_name]', '#[order_id]', '[store_name]'),
            'DEFAULT_BUYER_SMS_COMPLETED'      => sprintf(__('Hello %1$s, your order %2$s with %3$s has been dispatched and shall deliver to you shortly.',
                $this->plugin_name), '[billing_first_name]', '#[order_id]', '[store_name]', PHP_EOL, PHP_EOL),
            'DEFAULT_BUYER_SMS_CANCELLED'      => sprintf(__('Hello %1$s, your order %2$s with %3$s has been cancelled due to some un-avoidable conditions. Sorry for the inconvenience caused.',
                $this->plugin_name), '[billing_first_name]', '#[order_id]', '[store_name]'),
            'DEFAULT_BUYER_SMS_STATUS_CHANGED' => sprintf(__('Hello %1$s, status of your order %2$s with %3$s has been changed to %4$s.%5$s',
                $this->plugin_name), '[billing_first_name]', '#[order_id]', '[store_name]', '[order_status]', PHP_EOL,
                PHP_EOL),

            'DEFAULT_ADMIN_SMS_PENDING'        => sprintf(__('%1$s: Hello, %2$s is trying to place order %3$s value %4$s %5$s',
                $this->plugin_name), '[store_name]', '[billing_first_name]', '#[order_id]', '[order_currency]',
                '[order_amount]'),
            'DEFAULT_ADMIN_SMS_ON_HOLD'        => sprintf(__('%1$s: Your order %2$s %3$s %4$s. is On Hold Now.',
                $this->plugin_name), '[store_name]', '#[order_id]', '[order_currency]', '[order_amount]'),
            'DEFAULT_ADMIN_SMS_PROCESSING'     => sprintf(__('%1$s: You have a new order %2$s for order value %3$s. Please check your admin dashboard for complete details.',
                $this->plugin_name), '[store_name]', '#[order_id]', '[order_amount]'),
            'DEFAULT_ADMIN_SMS_COMPLETED'      => sprintf(__('%1$s: Your order %2$s %3$s %4$s. is completed.',
                $this->plugin_name), '[store_name]', '#[order_id]', '[order_currency]', '[order_amount]'),
            'DEFAULT_ADMIN_SMS_CANCELLED'      => sprintf(__('%1$s: Your order %2$s %3$s %4$s. is Cancelled.%5$s',
                $this->plugin_name), '[store_name]', '#[order_id]', '[order_currency]', '[order_amount]', PHP_EOL),
            'DEFAULT_ADMIN_SMS_STATUS_CHANGED' => sprintf(__('%1$s: status of order %2$s has been changed to %3$s.',
                $this->plugin_name), '[store_name]', '#[order_id]', '[order_status]')
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

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/alpha_sms-public.css', [], $this->version,
            'all');

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

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/alpha_sms-public.js', ['jquery'],
            $this->version, false);

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
        $user = wp_get_current_user();
        $value = isset($_POST['billing_phone']) ? esc_attr($_POST['billing_phone']) : $user->billing_phone;
        ?>

        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="reg_billing_phone"><?php _e('Phone', 'woocommerce'); ?> <span class="required">*</span>
            </label>
            <input type="tel" minlength="11" maxlength="11" class="input-text" name="billing_phone"
                   id="reg_billing_phone" value="<?php echo $value ?>" required/>
        </p>
        <div class="clear"></div>

        <?php
    }

    /**
     * Woocommerce / Default WordPress
     * show otp form in registration form
     */
    public function add_otp_field_on_reg_form()
    {
        ?>
        <div id="alpha_sms_otp_reg" class="mb-3" style="display:none;">
            <div class="alpha_sms-generate-otp">
                <label for="otp_code" class="d-inline-block">OTP Code</label>
                <div id="wc_resend_otp" class="float-right"></div>
                <input type="number" class="input" id="otp_code" name="otp_code"/>
            </div>
        </div>

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

        if (!$user_email && !empty($_POST['billing_email'])) {
            $user_email = sanitize_text_field($_POST['billing_email']);
        }

        if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
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

        if (!$user_phone) {
            $response = ['status' => 400, 'message' => __('The phone number you entered is not valid!')];
            echo wp_kses_post(json_encode($response));
            wp_die();
            exit;
        }

        $ip = $this->getClientIP();
        $action = 'Registration';

        //we will send sms
        $otp_code = $this->generateOTP();

        $body = 'Your OTP for Registration is ' . $otp_code . ' . Only valid for 2 min.';

        $sms_response = $this->SendSMS($user_phone, $body);

        if ($sms_response->error === 0) {
            // save info in database for later verification
            $this->log_login_register_action(null, null, sanitize_text_field($_POST['email']), $user_phone, $otp_code,
                $ip, $action);
            $response = [
                'status'  => 200,
                'message' => 'A OTP (One Time Passcode) has been sent. Please enter the OTP in the field below to verify your phone.'
            ];
            echo wp_kses_post(json_encode($response));
            exit;
        }

        $response = ['status' => '400', 'message' => $sms_response->msg];
        echo wp_kses_post(json_encode($response));
        exit;
        wp_die();
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
     * Get client IP Address
     * @return mixed|string
     */
    public function getClientIP()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        } else {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                if (isset($_SERVER['HTTP_X_FORWARDED'])) {
                    $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
                } else {
                    if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
                        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
                    } else {
                        if (isset($_SERVER['HTTP_FORWARDED'])) {
                            $ipaddress = $_SERVER['HTTP_FORWARDED'];
                        } else {
                            if (isset($_SERVER['REMOTE_ADDR'])) {
                                $ipaddress = $_SERVER['REMOTE_ADDR'];
                            } else {
                                $ipaddress = 'UNKNOWN';
                            }
                        }
                    }
                }
            }
        }

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
    public function log_login_register_action(
        $user_id,
        $user_login,
        $user_email,
        $mobile_phone,
        $otp_code,
        $ip,
        $action
    ) {
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
     * Verify otp and register the user
     * @param $customer_id
     */
    public function register_the_customer($customer_id)
    {
        if (isset($_POST['billing_phone']) && $this->validateNumber($_POST['billing_phone'])) {
            update_user_meta($customer_id, 'billing_phone',
                sanitize_text_field($this->validateNumber($_POST['billing_phone'])));
        }
    }

    /**
     * Default WordPress
     * show phone number on register page
     */
    public function wp_phone_on_register()
    {
        $billing_phone = (!empty($_POST['billing_phone'])) ? sanitize_text_field($_POST['billing_phone']) : '';

        ?>
        <p>
            <label for="billing_phone"><?php _e('Phone', $this->plugin_name) ?><br/>
                <input type="text" name="billing_phone" id="reg_billing_phone" class="input"
                       value="<?php echo esc_attr($billing_phone); ?>" size="25"/></label>
        </p>
        <?php
    }


    /**
     * Woocommerce validate phone and validate otp
     * @param $errors
     * @return mixed
     */
    public function register_form_validation($errors, $sanitized_user_login, $user_email)
    {
        global $wpdb;
        if (empty($_REQUEST['billing_phone']) || !is_numeric($_REQUEST['billing_phone']) || !$this->validateNumber(sanitize_text_field($_REQUEST['billing_phone']))) {
            $errors->add('phone_error', __('You phone number is not valid.', $this->plugin_name));
        }

        $billing_phone = $this->validateNumber(sanitize_text_field($_REQUEST['billing_phone']));

        $hasPhoneNumber = get_users('meta_value=' . $billing_phone);

        if (!empty($hasPhoneNumber)) {
            $errors->add('duplicate_phone_error', __('Mobile number is already used!', $this->plugin_name));
        }

        if (!empty($_REQUEST['otp_code'])) {

            $otp_code = $wpdb->_escape($_REQUEST['otp_code']);

            $email = sanitize_email($user_email);
            $action = 'Registration';

            $valid_user = $this->authenticate_otp($email, $action, trim($otp_code));

            if ($valid_user) {
                $this->deletePastdata($email, $email, $action);

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
                "DELETE FROM {$wpdb->prefix}alpha_sms_login_register_actions WHERE action=%s AND (user_login=%s OR user_email=%s OR ip=%s)",
                $action, $user_login, $user_email, $ip
            )
        );
    }

    /**
     * Alert customer and admins when a new order is placed
     * @param $order_id
     */
    public function wc_new_order_alert($order_id)
    {
        if (!$order_id) {
            return;
        }

        $this->wc_order_status_change_alert($order_id, 'pending', 'pending');
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
        if (!$order_id) {
            return;
        }

        $order = new WC_Order($order_id);
        $currency_code = $order->get_currency();
        $currency_symbol = get_woocommerce_currency_symbol( $currency_code );

        // Get the Customer billing phone
        $billing_phone = $order->get_billing_phone();

        //we will send sms

        $buyer_msg = $this->order_alerts['DEFAULT_BUYER_SMS_' . str_replace(' ', '_', strtoupper($new_status))];

        $search = [
            '[store_name]',
            '[billing_first_name]',
            '[order_id]',
            '[order_status]',
            '[order_currency]',
            '[order_amount]'
        ];

        $replace = [
            get_bloginfo(),
            $order->get_billing_first_name(),
            $order_id,
            $new_status,
            $order->get_currency(),
            $order->get_total()
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

            $admin_msg = $this->order_alerts['DEFAULT_ADMIN_SMS_' . str_replace(' ', '_', strtoupper($new_status))];

            $admin_msg = str_replace($search, $replace, $admin_msg);

            $this->SendSMS($numbers, $admin_msg);
        }


    }

    /**
     * Get all the phone number associated with administration role
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
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/otp-login-form.css', [], $this->version,
            'all');
    }

    public function login_enqueue_script()
    {
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/otp-login-form.js', ['jquery'],
            $this->version, false);
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
    public function add_otp_field_in_login_form()
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

        if (!$user_id || !$result) {
            $response = ['status' => 401, 'message' => __('Wrong username or password!')];
            echo wp_kses_post(json_encode($response));
            wp_die();
            exit;
        }

        $user_login = $userdata->data->user_login;
        $user_email = $userdata->data->user_email;
        $user_phone = get_user_meta($user_id, 'mobile_phone', true);

        if (!$user_phone) {
            $user_phone = get_user_meta($user_id, 'billing_phone', true);
        }

        if (!$user_phone) {
            $response = ['status' => 402, 'message' => __('No phone number found')];
            echo wp_kses_post(json_encode($response));
            wp_die();
            exit;
        }

        $ip = $this->getClientIP();
        $action = 'Login';

        //we will send sms
        $otp_code = $this->generateOTP();

        $number = $user_phone;
        $body = $otp_code . ' is your one time password to login. Only valid for 2 min.';

        $sms_response = $this->SendSMS($number, $body);

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
     * Login the user verifying otp code
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
            $user_phone = get_user_meta($user->data->ID, 'billing_phone', true);
        }

        if (!$user_phone) {
            return $user;
        }

        if (empty($_REQUEST['otp_code'])) {
            $error = new WP_Error();

            $error->add('empty_password',
                __('<strong>Error</strong>: Wrong username or password!', $this->plugin_name));

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

    public function otp_form_at_checkout()
    {
        if (!is_user_logged_in() && get_option('woocommerce_enable_signup_and_login_from_checkout')) {
            require_once('partials/' . $this->plugin_name . '-otp-checkout-form.php');
        }
    }


}
