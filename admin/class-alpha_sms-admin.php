<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://alpha.net.bd
 * @since      1.0.0
 *
 * @package    Alpha_sms
 * @subpackage Alpha_sms/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Alpha_sms
 * @subpackage Alpha_sms/admin
 * @author     Alpha Net Developer Team <support@alpha.net.bd>
 */
class Alpha_sms_Admin
{

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/alpha_sms-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/alpha_sms-admin.js', array('jquery'), $this->version, false);
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function alpha_sms_setup_page()
	{
		require_once('partials/' . $this->plugin_name . '-admin-display_settings.php');
	}

	/**
	 * Render the campain page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function alpha_sms_campain_page()
	{
		require_once('partials/' . $this->plugin_name . '-admin-display_campain.php');
	}

	public function alpha_sms_admin_menu()
	{

		// Primary Main menu 
		add_menu_page(
			'Alpha SMS',
			'Alpha SMS',
			'manage_options',
			$this->plugin_name,
			array($this, 'alpha_sms_campain_page'),
			'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAyNS4wLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiDQoJIHZpZXdCb3g9IjAgMCAzMiAzMiIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMzIgMzI7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxzdHlsZSB0eXBlPSJ0ZXh0L2NzcyI+DQoJLnN0MHtmaWxsOiM0QkRFOUQ7fQ0KCS5zdDF7ZmlsbDojMkYzNTNCO30NCjwvc3R5bGU+DQo8Zz4NCgk8cGF0aCBjbGFzcz0ic3QwIiBkPSJNMTEuMiwxYzIuOS0xLjIsNi40LTEsOS4yLDAuNmMyLjYsMS41LDQuNiw0LjIsNS4xLDcuMmMwLjMsMS43LDAuMSwzLjMtMC4zLDVjMC0xLjEsMC4xLTIuMy0wLjItMy40DQoJCWMtMC42LTMtMi42LTUuOC01LjMtNy4yYy0yLjMtMS4yLTUtMS42LTcuNS0wLjlDOS4yLDMuMSw2LjYsNS4yLDUuMyw4Yy0wLjksMi42LTAuNiw1LjYsMC42LDguMWMwLjksMiwyLjUsMy42LDQuMyw0LjgNCgkJYzIuNCwxLjcsNC44LDMuNSw3LjEsNS4yYy0yLjYsMC01LjIsMC03LjgsMGMtMy4yLDAuMS02LjEtMi45LTYtNi4xYzAtNC4zLDAtOC43LDAtMTNjMC0zLjEsMi45LTUuOCw2LTUuNw0KCQlDMTAuMSwxLjIsMTAuNywxLjIsMTEuMiwxeiIvPg0KCTxwYXRoIGNsYXNzPSJzdDAiIGQ9Ik05LjQsMTAuMWMxLjEtMC41LDIuNiwwLjIsMi44LDEuNGMwLjMsMS4yLTAuOCwyLjYtMi4xLDIuNGMtMS4zLDAtMi4yLTEuNC0xLjktMi42DQoJCUM4LjQsMTAuOSw4LjksMTAuNCw5LjQsMTAuMXoiLz4NCgk8cGF0aCBjbGFzcz0ic3QxIiBkPSJNMTUuMSwxMC4xYzEuMS0wLjUsMi41LDAuMSwyLjgsMS4zYzAuMywxLjEtMC40LDIuMy0xLjUsMi41Yy0xLjEsMC4zLTIuNC0wLjYtMi41LTEuOA0KCQlDMTMuOCwxMS4zLDE0LjMsMTAuNSwxNS4xLDEwLjF6Ii8+DQoJPHBhdGggY2xhc3M9InN0MCIgZD0iTTIwLjcsMTAuMWMxLjItMC42LDIuOCwwLjMsMi45LDEuNmMwLjIsMS4yLTEsMi40LTIuMiwyLjJjLTEuMS0wLjEtMi0xLjEtMS45LTIuMg0KCQlDMTkuNiwxMS4xLDIwLDEwLjQsMjAuNywxMC4xeiIvPg0KPC9nPg0KPGc+DQoJPGc+DQoJCTxwYXRoIGNsYXNzPSJzdDAiIGQ9Ik0yMC45LDEuMmMxLjIsMCwyLjQtMC4xLDMuNSwwLjNjMi40LDAuOCw0LjEsMy4yLDQuMSw1LjdjMCwzLjksMCw3LjksMCwxMS44YzAsMS4zLDAsMi42LTAuNiwzLjcNCgkJCWMtMC45LDItMywzLjMtNS4xLDMuNGMwLjEsMS45LDAuMSwzLjgsMC4yLDUuN2MtMS4xLTMuMy0yLjEtNi42LTMuMi05LjljMi44LTEsNS4xLTMsNi40LTUuNmMxLjUtMywxLjUtNi43LDAuMS05LjgNCgkJCUMyNS4xLDQuMiwyMy4xLDIuMywyMC45LDEuMnoiLz4NCgk8L2c+DQo8L2c+DQo8L3N2Zz4NCg==',
			76
		);

		add_submenu_page($this->plugin_name, 'SMS Campain', 'Campain', 'manage_options', $this->plugin_name, array($this, 'alpha_sms_campain_page'));

		add_submenu_page($this->plugin_name, 'Alpha SMS Settings', 'Settings', 'manage_options', $this->plugin_name . '_settings', array($this, 'alpha_sms_setup_page'));
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links($links)
	{

		/**
		 * Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
		 * The "plugins.php" must match with the previously added add_submenu_page first option.
		 * For custom post type you have to change 'plugins.php?page=' to 'edit.php?post_type=your_custom_post_type&page='
		 */
		$settings_link = array('<a href="' . admin_url('admin.php?page=' . $this->plugin_name . '_settings') . '">' . __('Settings', $this->plugin_name) . '</a>',);

		// -- OR --

		// $settings_link = array( '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __( 'Settings', $this->plugin_name ) . '</a>', );

		return array_merge($settings_link, $links);
	}


	/**
	 * Validate fields from admin area plugin settings form ('exopite-lazy-load-xt-admin-display.php')
	 * @param  mixed $input as field form settings form
	 * @return mixed as validated fields
	 */
	public function validate($input)
	{
		$options = get_option($this->plugin_name);

		$options['example_checkbox'] = (isset($input['example_checkbox']) && !empty($input['example_checkbox'])) ? 1 : 0;
		$options['api_key'] = (isset($input['api_key']) && !empty($input['api_key'])) ? esc_attr($input['api_key']) : '';

		return $options;
	}

	public function options_update()
	{
		register_setting($this->plugin_name, $this->plugin_name, array(
			'sanitize_callback' => array($this, 'validate'),
		));
	}
}