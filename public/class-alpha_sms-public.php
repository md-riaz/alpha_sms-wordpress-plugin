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
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/alpha_sms-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/alpha_sms-public.js', array( 'jquery' ), $this->version, false );

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
		$billing_phone = ( ! empty( $_POST['billing_phone'] ) ) ? sanitize_text_field( $_POST['billing_phone'] ) : '';

		?>
		<p>
			<label for="billing_phone"><?php _e( 'Phone', $this->plugin_name ) ?><br />
				<input type="text" name="billing_phone" id="billing_phone" class="input" value="<?php echo esc_attr(  $billing_phone  ); ?>" size="25" /></label>
		</p>
		<?php
	}

	public function wp_phone_field_validation($errors, $sanitized_user_login, $user_email)
	{
		if ( empty( $_POST['billing_phone'] ) || !is_numeric( $_POST['billing_phone'] ) || !$this->validateNumber($_POST['billing_phone']) ) {
			$errors->add( 'phone_error', sprintf('<strong>%s</strong>: %s',__( 'ERROR', $this->plugin_name ),__( 'You phone number is not valid.', $this->plugin_name ) ) );
		}

        $billing_phone = $_POST['billing_phone'];

		if(!empty($errors->errors)) return $errors;

		return $this->startOTPTransaction($errors, $sanitized_user_login, $user_email, $billing_phone);
	}

	public function wp_user_register( $user_id  )
	{
		if ( ! empty( $_POST['billing_phone'] ) ) {
			update_user_meta( $user_id, 'billing_phone', sanitize_text_field( $_POST['billing_phone'] ) );
		}
	}


	/**
	 * Validate Bangladeshi phone number format
	 * @param $num
	 * @return false|int|string
	 */
	private function validateNumber($num)
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

	public function startOTPTransaction($errors, $sanitized_user_login, $user_email, $billing_phone)
	{
		if (!isset($_POST['register_nonce'])) return $errors;

		$_SESSION['otp_session'] = 'default_wp_registration';

		$this->sendChallenge($errors, $sanitized_user_login, $user_email, $billing_phone);

		return $errors;
	}

	public function sendChallenge($errors, $user_login, $user_email, $billing_phone)
	{
		do_action($this->plugin_name.'_generate_otp',$errors, $user_login, $user_email, $billing_phone);
	}

	public function otp_challenge($errors, $user_login, $user_email, $billing_phone, $password = ''){

		$_SESSION['current_url'] = $this->currentPageUrl();
		$_SESSION['user_login'] = $user_login;
		$_SESSION['user_email'] = $user_email;
		$_SESSION['user_password'] = $password;
		$_SESSION['billing_phone'] = $billing_phone;

		$this->handleOTPAction($user_login, $user_email, $billing_phone);
	}

	public function currentPageUrl() {
		global $wp;
		return add_query_arg( $wp->query_vars, home_url( $wp->request ) );
	}

	public function handleOTPAction($user_login, $user_email, $billing_phone) {

		$otp_template = 'An OTP Code has been sent to ##phone##';
		$message = str_replace("##phone##",$billing_phone,$otp_template);

		if(!headers_sent()) header('Content-Type: text/html; charset=utf-8');

		$htmlContent = "
<div class='otp-container'>
    <h1>ENTER OTP</h1>
    <p>{$message}</p>
    <form action='". admin_url('admin-ajax.php')."' method='post' id='otp_form'>
    ".wp_nonce_field('wp_otp_action',$this->plugin_name)."
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

	public function process_otp_action()
	{
		if ( empty($_POST) || !wp_verify_nonce($_POST[$this->plugin_name],'wp_otp_action') ) {
			echo 'You targeted the right function, but sorry, your nonce did not verify.';
			die();
		}

        // do your function here
	    echo json_encode(array('status' => '200'));
	}
}
