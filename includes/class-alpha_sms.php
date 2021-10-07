<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://alpha.net.bd
 * @since      1.0.0
 *
 * @package    Alpha_sms
 * @subpackage Alpha_sms/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Alpha_sms
 * @subpackage Alpha_sms/includes
 * @author     Alpha Net Developer Team <support@alpha.net.bd>
 */
class Alpha_sms
{

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Alpha_sms_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;
	private $options;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct()
	{
		if (defined('ALPHA_SMS_VERSION')) {
			$this->version = ALPHA_SMS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'alpha_sms';

		$this->options = get_option($this->plugin_name);

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Alpha_sms_Loader. Orchestrates the hooks of the plugin.
	 * - Alpha_sms_i18n. Defines internationalization functionality.
	 * - Alpha_sms_Admin. Defines all hooks for the admin area.
	 * - Alpha_sms_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies()
	{

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-alpha_sms-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-alpha_sms-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-alpha_sms-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-alpha_sms-public.php';

		$this->loader = new Alpha_sms_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Alpha_sms_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale()
	{

		$plugin_i18n = new Alpha_sms_i18n();

		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks()
	{

		$plugin_admin = new Alpha_sms_Admin($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

		// Add menu item
		$this->loader->add_action('admin_menu', $plugin_admin, 'add_admin_menu');

		// Add Settings link to the plugin
		$plugin_basename = plugin_basename(plugin_dir_path(__DIR__) . $this->plugin_name . '.php');
		$this->loader->add_filter('plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links');

		// Save/Update our plugin options
		$this->loader->add_action('admin_init', $plugin_admin, 'options_update');

		// campaign page form submit
		$this->loader->add_action('admin_post_'. $this->plugin_name.'_campaign', $plugin_admin, 'alpha_sms_send_campaign');

		$this->loader->add_action( 'admin_notices', $plugin_admin, 'display_flash_notices');

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks()
	{

		$plugin_public = new Alpha_sms_Public($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');


		// Woocommerce Reg/Account Phone field
		if (isset($this->options['woocommerce_reg_phone'])){
			// Display a field in Registration Form / Edit account
			$this->loader->add_action('woocommerce_register_form_start', $plugin_public, 'wc_phone_on_reg');
			$this->loader->add_action('woocommerce_edit_account_form_start', $plugin_public, 'wc_phone_on_reg');
			// registration Field validation
			$this->loader->add_filter( 'woocommerce_registration_errors', $plugin_public,'wc_registration_field_validation' );
			// Save registration Field value
			$this->loader->add_action( 'woocommerce_created_customer', $plugin_public, 'wc_save_account_registration_field' );
			// Save Field value in Edit account
			$this->loader->add_action('woocommerce_save_account_details', $plugin_public, 'wc_save_my_account_billing_phone');
		}

		// Phone field on WordPress Reg page
		if ($this->options['reg_otp']){
			// Display a field in Registration Form
			$this->loader->add_action('register_form', $plugin_public, 'wp_phone_on_register');
			// Add validation. In this case, we make sure phone is required.
			$this->loader->add_filter( 'registration_errors', $plugin_public,'wp_phone_field_validation', 10, 3 );
			// otp transaction challenge
			$this->loader->add_action($this->plugin_name.'_generate_otp', $plugin_public, 'otp_challenge', 10, 4);
			$this->loader->add_action('wp_ajax_nopriv_wp_otp_action', $plugin_public, 'process_otp_action');
			$this->loader->add_action('init', $plugin_public, 'handle_otp_form_action'); // ekhan theke kaj korte hobe

			// Finally, save our extra registration user meta.
			$this->loader->add_action( 'user_register', $plugin_public, 'wp_user_register' );

		}

		// Woocommerce order status notifications
		if ($this->options['order_status']){
			$this->loader->add_action('woocommerce_order_status_changed', $plugin_public, 'woo_order_status_change', 10, 3);
			$this->loader->add_action('woocommerce_new_order', $plugin_public, 'woo_new_order');
		}

		// Phone number otp verification on login
		if ($this->options['login_otp']){
			// load css and js to login page
			$this->loader->add_action('login_enqueue_scripts', $plugin_public, 'login_enqueue_styles');
			// add otp form to login page
			$this->loader->add_action('login_form', $plugin_public, 'add_otp_in_login_form');
			// phone number submit action from jQuery $.post
			$this->loader->add_action('wp_ajax_alpha_sms_to_save_and_send_otp_login', $plugin_public, 'save_and_send_otp_login');
			$this->loader->add_action('wp_ajax_nopriv_alpha_sms_to_save_and_send_otp_login', $plugin_public, 'save_and_send_otp_login');

			// login user based on otp
			$this->loader->add_filter('authenticate', $plugin_public,'login_user', 30, 3);

			$this->loader->add_action('woocommerce_login_form_end', $plugin_public, 'woocommerce_login_form_end');
		}
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run()
	{
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name()
	{
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Alpha_sms_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader()
	{
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version()
	{
		return $this->version;
	}
}