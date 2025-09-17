<?php
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}
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

    /**
     * Background processor for queued SMS jobs.
     *
     * @var Alpha_SMS_Background|null
     */
    private $background;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     */
    public function __construct($plugin_name, $version, $background = null)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->background = $background;
    }

    /**
     * Set the background processor instance.
     *
     * @param Alpha_SMS_Background|null $background Background processor.
     *
     * @return void
     */
    public function set_background_processor($background)
    {
        $this->background = $background;
    }

    /**
     * Retrieve the background processor instance.
     *
     * @return Alpha_SMS_Background|null
     */
    private function get_background_processor()
    {
        if ($this->background instanceof Alpha_SMS_Background) {
            return $this->background;
        }

        return null;
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

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/alpha_sms-admin.css', [], $this->version,
            'all');
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

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/alpha_sms-admin.js', ['jquery'],
            $this->version, false);
    }

    /**
     * Render the settings page for this plugin.
     *
     * @since    1.0.0
     */
    public function display_setting_page()
    {
        require_once 'partials/' . $this->plugin_name . '-admin-display_settings.php';
    }

    /**
     * Render the campaign page for this plugin.
     *
     * @since    1.0.0
     */
    public function display_campaign_page()
    {
        require_once 'partials/' . $this->plugin_name . '-admin-display_campaign.php';
    }

    /**
     *  Add the main menu and sub menu of the plugin
     *
     * @since    1.0.0
     */

    public function add_admin_menu()
    {
        // Primary Main menu
        add_menu_page(
            'Alpha SMS',
            'Alpha SMS',
            'manage_options',
            $this->plugin_name,
            [$this, 'display_campaign_page'],
            'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAyNS4wLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiDQoJIHZpZXdCb3g9IjAgMCAzMiAzMiIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMzIgMzI7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxzdHlsZSB0eXBlPSJ0ZXh0L2NzcyI+DQoJLnN0MHtmaWxsOiM0QkRFOUQ7fQ0KCS5zdDF7ZmlsbDojMkYzNTNCO30NCjwvc3R5bGU+DQo8Zz4NCgk8cGF0aCBjbGFzcz0ic3QwIiBkPSJNMTEuMiwxYzIuOS0xLjIsNi40LTEsOS4yLDAuNmMyLjYsMS41LDQuNiw0LjIsNS4xLDcuMmMwLjMsMS43LDAuMSwzLjMtMC4zLDVjMC0xLjEsMC4xLTIuMy0wLjItMy40DQoJCWMtMC42LTMtMi42LTUuOC01LjMtNy4yYy0yLjMtMS4yLTUtMS42LTcuNS0wLjlDOS4yLDMuMSw2LjYsNS4yLDUuMyw4Yy0wLjksMi42LTAuNiw1LjYsMC42LDguMWMwLjksMiwyLjUsMy42LDQuMyw0LjgNCgkJYzIuNCwxLjcsNC44LDMuNSw3LjEsNS4yYy0yLjYsMC01LjIsMC03LjgsMGMtMy4yLDAuMS02LjEtMi45LTYtNi4xYzAtNC4zLDAtOC43LDAtMTNjMC0zLjEsMi45LTUuOCw2LTUuNw0KCQlDMTAuMSwxLjIsMTAuNywxLjIsMTEuMiwxeiIvPg0KCTxwYXRoIGNsYXNzPSJzdDAiIGQ9Ik05LjQsMTAuMWMxLjEtMC41LDIuNiwwLjIsMi44LDEuNGMwLjMsMS4yLTAuOCwyLjYtMi4xLDIuNGMtMS4zLDAtMi4yLTEuNC0xLjktMi42DQoJCUM4LjQsMTAuOSw4LjksMTAuNCw5LjQsMTAuMXoiLz4NCgk8cGF0aCBjbGFzcz0ic3QxIiBkPSJNMTUuMSwxMC4xYzEuMS0wLjUsMi41LDAuMSwyLjgsMS4zYzAuMywxLjEtMC40LDIuMy0xLjUsMi41Yy0xLjEsMC4zLTIuNC0wLjYtMi41LTEuOA0KCQlDMTMuOCwxMS4zLDE0LjMsMTAuNSwxNS4xLDEwLjF6Ii8+DQoJPHBhdGggY2xhc3M9InN0MCIgZD0iTTIwLjcsMTAuMWMxLjItMC42LDIuOCwwLjMsMi45LDEuNmMwLjIsMS4yLTEsMi40LTIuMiwyLjJjLTEuMS0wLjEtMi0xLjEtMS45LTIuMg0KCQlDMTkuNiwxMS4xLDIwLDEwLjQsMjAuNywxMC4xeiIvPg0KPC9nPg0KPGc+DQoJPGc+DQoJCTxwYXRoIGNsYXNzPSJzdDAiIGQ9Ik0yMC45LDEuMmMxLjIsMCwyLjQtMC4xLDMuNSwwLjNjMi40LDAuOCw0LjEsMy4yLDQuMSw1LjdjMCwzLjksMCw3LjksMCwxMS44YzAsMS4zLDAsMi42LTAuNiwzLjcNCgkJCWMtMC45LDItMywzLjMtNS4xLDMuNGMwLjEsMS45LDAuMSwzLjgsMC4yLDUuN2MtMS4xLTMuMy0yLjEtNi42LTMuMi05LjljMi44LTEsNS4xLTMsNi40LTUuNmMxLjUtMywxLjUtNi43LDAuMS05LjgNCgkJCUMyNS4xLDQuMiwyMy4xLDIuMywyMC45LDEuMnoiLz4NCgk8L2c+DQo8L2c+DQo8L3N2Zz4NCg==',
            76
        );

        add_submenu_page($this->plugin_name, 'SMS Campaign', 'Campaign', 'manage_options', $this->plugin_name,
            [$this, 'display_campaign_page']);

        add_submenu_page($this->plugin_name, 'Alpha SMS Settings', 'Settings', 'manage_options',
            $this->plugin_name . '_settings', [$this, 'display_setting_page']);
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
        $settings_link = [
            '<a href="' . admin_url('admin.php?page=' . $this->plugin_name . '_settings') . '">' . __('Settings',
                $this->plugin_name) . '</a>',
        ];

        // -- OR --

        // $settings_link = array( '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __( 'Settings', $this->plugin_name ) . '</a>', );

        return array_merge($settings_link, $links);
    }

    /**
     * Validate fields from admin area plugin settings form ('exopite-lazy-load-xt-admin-display.php')
     * @param mixed $input as field form settings form
     * @return mixed as validated fields
     */
    public function validate($input)
    {
        $options = get_option($this->plugin_name);

        if (!empty($input['api_key']) && strpos(esc_attr($input['api_key']), str_repeat('*', 24), '12')) {

            $input['api_key'] = $options['api_key'];

        }

        $options['api_key'] = (isset($input['api_key']) && !empty($input['api_key'])) ? esc_attr($input['api_key']) : '';
        $options['sender_id'] = (isset($input['sender_id']) && !empty($input['sender_id'])) ? esc_attr($input['sender_id']) : '';

        $options['order_status'] = (isset($input['order_status']) && !empty($input['order_status'])) ? 1 : 0;
        $options['wp_reg'] = (isset($input['wp_reg']) && !empty($input['wp_reg'])) ? 1 : 0;
        $options['wp_login'] = (isset($input['wp_login']) && !empty($input['wp_login'])) ? 1 : 0;
        $options['wc_reg'] = (isset($input['wc_reg']) && !empty($input['wc_reg'])) ? 1 : 0;
        $options['wc_login'] = (isset($input['wc_login']) && !empty($input['wc_login'])) ? 1 : 0;
        $options['otp_checkout'] = (isset($input['otp_checkout']) && !empty($input['otp_checkout'])) ? 1 : 0;
        $options['admin_phones'] = (isset($input['admin_phones']) && !empty($input['admin_phones'])) ? esc_attr($input['admin_phones']) : '';

        $options['order_status_pending'] = (isset($input['order_status_pending']) && !empty($input['order_status_pending'])) ? 1 : 0;
        $options['order_status_processing'] = (isset($input['order_status_processing']) && !empty($input['order_status_processing'])) ? 1 : 0;
        $options['order_status_on_hold'] = (isset($input['order_status_on_hold']) && !empty($input['order_status_on_hold'])) ? 1 : 0;
        $options['order_status_completed'] = (isset($input['order_status_completed']) && !empty($input['order_status_completed'])) ? 1 : 0;
        $options['order_status_cancelled'] = (isset($input['order_status_cancelled']) && !empty($input['order_status_cancelled'])) ? 1 : 0;
        $options['order_status_refunded'] = (isset($input['order_status_refunded']) && !empty($input['order_status_refunded'])) ? 1 : 0;
        $options['order_status_failed'] = (isset($input['order_status_failed']) && !empty($input['order_status_failed'])) ? 1 : 0;
        $options['order_status_admin'] = (isset($input['order_status_admin']) && !empty($input['order_status_admin'])) ? 1 : 0;
        
        $options['ORDER_STATUS_PENDING_SMS'] = (isset($input['ORDER_STATUS_PENDING_SMS']) && !empty($input['ORDER_STATUS_PENDING_SMS'])) ? esc_attr($input['ORDER_STATUS_PENDING_SMS']) : '';
        $options['ORDER_STATUS_PROCESSING_SMS'] = (isset($input['ORDER_STATUS_PROCESSING_SMS']) && !empty($input['ORDER_STATUS_PROCESSING_SMS'])) ? esc_attr($input['ORDER_STATUS_PROCESSING_SMS']) : '';
        $options['ORDER_STATUS_ON_HOLD_SMS'] = (isset($input['ORDER_STATUS_ON_HOLD_SMS']) && !empty($input['ORDER_STATUS_ON_HOLD_SMS'])) ? esc_attr($input['ORDER_STATUS_ON_HOLD_SMS']) : '';
        $options['ORDER_STATUS_COMPLETED_SMS'] = (isset($input['ORDER_STATUS_COMPLETED_SMS']) && !empty($input['ORDER_STATUS_COMPLETED_SMS'])) ? esc_attr($input['ORDER_STATUS_COMPLETED_SMS']) : '';
        $options['ORDER_STATUS_CANCELLED_SMS'] = (isset($input['ORDER_STATUS_CANCELLED_SMS']) && !empty($input['ORDER_STATUS_CANCELLED_SMS'])) ? esc_attr($input['ORDER_STATUS_CANCELLED_SMS']) : '';
        $options['ORDER_STATUS_REFUNDED_SMS'] = (isset($input['ORDER_STATUS_REFUNDED_SMS']) && !empty($input['ORDER_STATUS_REFUNDED_SMS'])) ? esc_attr($input['ORDER_STATUS_REFUNDED_SMS']) : '';
        $options['ORDER_STATUS_FAILED_SMS'] = (isset($input['ORDER_STATUS_FAILED_SMS']) && !empty($input['ORDER_STATUS_FAILED_SMS'])) ? esc_attr($input['ORDER_STATUS_FAILED_SMS']) : '';
        $options['ADMIN_STATUS_SMS'] = (isset($input['ADMIN_STATUS_SMS']) && !empty($input['ADMIN_STATUS_SMS'])) ? esc_attr($input['ADMIN_STATUS_SMS']) : '';
       
        if (!$this->checkAPI($options['api_key'])) {

            $options['order_status'] =
            $options['wp_reg'] =
            $options['wp_login'] =
            $options['wc_reg'] =
            $options['wc_login'] =
            $options['otp_checkout'] =
            $options['order_status_pending'] =
            $options['order_status_processing'] =
            $options['order_status_on_hold'] =
            $options['order_status_completed'] =
            $options['order_status_cancelled'] =
            $options['order_status_refunded'] =
            $options['order_status_failed'] =
            $options['order_status_admin'] = 0;

            add_settings_error(
                $this->plugin_name, // Slug title of setting
                $this->plugin_name, // Slug-name , Used as part of 'id' attribute in HTML output.
                __('Please configure a valid SMS API Key.', $this->plugin_name),
                // message text, will be shown inside styled <div> and <p> tags
                'error' // Message type, controls HTML class. Accepts 'error' or 'updated'.
            );
        }

        return $options;
    }

    /**
     * Check if entered api key is valid or not
     * @return bool
     */
    private function checkAPI($api_key)
    {
        if (empty($api_key)) {
            return false;
        }

        require_once ALPHA_SMS_PATH . 'includes/sms.class.php';

        $smsPortal = new AlphaSMS($api_key);

        $response = $smsPortal->getBalance();

        return $response && $response->error === 0;
    }

    /**
     * update all settings
     */
    public function options_update()
    {
        register_setting($this->plugin_name, $this->plugin_name, [
            'sanitize_callback' => [$this, 'validate'],
        ]);
    }

    /**
     * send campaign msg to users
     */
    public function alpha_sms_send_campaign()
    {
        $numbersArr = [];

        $numbers = (isset($_POST[$this->plugin_name]['numbers']) && !empty($_POST[$this->plugin_name]['numbers'])) ? sanitize_textarea_field($_POST[$this->plugin_name]['numbers']) : '';
        $include_all_users = (isset($_POST[$this->plugin_name]['all_users']) && !empty($_POST[$this->plugin_name]['all_users'])) ? 1 : 0;
        $body = (isset($_POST[$this->plugin_name]['body']) && !empty($_POST[$this->plugin_name]['body'])) ? sanitize_textarea_field($_POST[$this->plugin_name]['body']) : false;

        //Grab all options
        $options = get_option($this->plugin_name);
        $api_key = !empty($options['api_key']) ? $options['api_key'] : '';
        $sender_id = !empty($options['sender_id']) ? trim($options['sender_id']) : '';

        // Empty body
        if (!$body) {
            $this->add_flash_notice(__("Fill the required fields properly", $this->plugin_name), "error");

            // Redirect to plugin page
            wp_redirect($_SERVER['HTTP_REFERER']);
            exit();
        }
        if (!$api_key) {
            $this->add_flash_notice(__("No valid API Key is set.", $this->plugin_name), "error");

            // Redirect to plugin page
            wp_redirect($_SERVER['HTTP_REFERER']);
            exit();
        }

        if ($numbers) {
            // split by new line
            $numbersArr = explode(PHP_EOL, $numbers);
        }

        if ($include_all_users) {
            $woo_numbers = $this->getCustomersPhone();
            $numbersArr = array_merge($numbersArr, $woo_numbers);
        }

        $numbersArr = array_map('trim', $numbersArr);
        $numbersArr = array_filter($numbersArr);
        $numbersArr = array_unique($numbersArr);

        if (empty($numbersArr)) {
            $this->add_flash_notice(__("No valid recipients were provided.", $this->plugin_name), "error");

            // Redirect to plugin page
            wp_redirect($_SERVER['HTTP_REFERER']);
            exit();
        }

        $background = $this->get_background_processor();

        if (!$background) {
            $this->add_flash_notice(__("Background processing is unavailable. Please try again later.", $this->plugin_name),
                "error");

            // Redirect to plugin page
            wp_redirect($_SERVER['HTTP_REFERER']);
            exit();
        }

        $queued = 0;
        $failedQueue = [];

        foreach ($numbersArr as $number) {
            if ($background->dispatch($number, $body, $sender_id, $api_key)) {
                $queued++;
            } else {
                $failedQueue[] = $number;
            }
        }

        if ($queued > 0) {
            $notice = sprintf(
                _n('Queued %d SMS message for background sending.', 'Queued %d SMS messages for background sending.', $queued,
                    $this->plugin_name),
                $queued
            );
            $this->add_flash_notice(esc_html($notice), 'success');
        }

        if (!empty($failedQueue)) {
            $failedQueue = array_map('sanitize_text_field', $failedQueue);
            $preview = array_slice($failedQueue, 0, 5);
            $summary = implode(', ', $preview);
            if ('' === trim($summary)) {
                $summary = __('unknown recipients', $this->plugin_name);
            }
            $message = sprintf(
                __('Unable to queue %1$d recipient(s): %2$s', $this->plugin_name),
                count($failedQueue),
                $summary
            );
            if (count($failedQueue) > count($preview)) {
                $message .= ' ' . sprintf(__('and %d more.', $this->plugin_name), count($failedQueue) - count($preview));
            }

            $this->add_flash_notice(esc_html($message), 'error');
        }

        // Redirect to plugin page
        wp_redirect($_SERVER['HTTP_REFERER']);
        exit();
    }

    /**
     * Add a flash notice to {prefix}options table until a full page refresh is done
     *
     * @param string $notice our notice message
     * @param string $type This can be "info", "warning", "error" or "success", "warning" as default
     * @param boolean $dismissible set this to TRUE to add is-dismissible functionality to your notice
     * @return void
     */

    public function add_flash_notice($notice = "", $type = "warning", $dismissible = true)
    {
        // Here we return the notices saved on our option, if there are no notices, then an empty array is returned
        $notices = get_option($this->plugin_name . '_notices', []);

        $dismissible_text = ($dismissible) ? "is-dismissible" : "";

        // We add our new notice.
        $notices[] = [
            "notice" => $notice,
            "type" => $type,
            "dismissible" => $dismissible_text,
        ];

        // Then we update the option with our notices array
        update_option($this->plugin_name . '_notices', $notices);
    }

    public function getCustomersPhone()
    {
        global $wpdb;

        // return $wpdb->get_col( "SELECT DISTINCT meta_value FROM `{$wpdb->prefix}postmeta` WHERE meta_key = '_billing_phone'" );

        return $wpdb->get_col("
        SELECT DISTINCT um.meta_value FROM {$wpdb->prefix}users as u
        INNER JOIN {$wpdb->prefix}usermeta as um ON um.user_id = u.ID
        INNER JOIN {$wpdb->prefix}usermeta as um2 ON um2.user_id = u.ID
        WHERE um.meta_key LIKE 'billing_phone' AND um.meta_value != ''
        AND um2.meta_key LIKE 'wp_capabilities' AND um2.meta_value NOT LIKE '%administrator%'
    ");
    }

    /**
     * Persist job results as flash notices for display in the admin area.
     *
     * @return void
     */
    private function maybe_add_job_result_notice()
    {
        $results = get_option($this->plugin_name . '_job_results', []);

        if (empty($results) || !is_array($results)) {
            return;
        }

        $defaults = [
            'success'    => 0,
            'failed'     => 0,
            'last_error' => '',
            'failures'   => [],
        ];

        $results = wp_parse_args($results, $defaults);

        if (!empty($results['success'])) {
            $success_notice = sprintf(
                _n('%d SMS message was sent successfully.', '%d SMS messages were sent successfully.', (int)$results['success'],
                    $this->plugin_name),
                (int)$results['success']
            );
            $this->add_flash_notice(esc_html($success_notice), 'success');
        }

        if (!empty($results['failed'])) {
            $error_notice = sprintf(
                _n('%d SMS message failed to send.', '%d SMS messages failed to send.', (int)$results['failed'],
                    $this->plugin_name),
                (int)$results['failed']
            );

            if (!empty($results['last_error'])) {
                $error_notice .= ' ' . sprintf(__('Last error: %s', $this->plugin_name), $results['last_error']);
            } elseif (!empty($results['failures']) && is_array($results['failures'])) {
                $details = [];
                foreach ($results['failures'] as $failure) {
                    $number = isset($failure['number']) ? $failure['number'] : '';
                    $message = isset($failure['message']) ? $failure['message'] : '';
                    $detail = trim($number);
                    if ($message !== '') {
                        $detail .= $detail ? ' - ' . $message : $message;
                    }

                    if ($detail !== '') {
                        $details[] = $detail;
                    }
                }

                if (!empty($details)) {
                    $error_notice .= ' ' . sprintf(
                        _n('Latest error: %s', 'Latest errors: %s', count($details), $this->plugin_name),
                        implode('; ', $details)
                    );
                }
            }

            $this->add_flash_notice(esc_html($error_notice), 'error');
        }

        delete_option($this->plugin_name . '_job_results');
    }

    /**
     * Function executed when the 'admin_notices' action is called, here we check if there are notices on
     * our database and display them, after that, we remove the option to prevent notices being displayed forever.
     * @return void
     */

    public function display_flash_notices()
    {
        $this->maybe_add_job_result_notice();

        $notices = get_option($this->plugin_name . '_notices', []);

        // Iterate through our notices to be displayed and print them.
        foreach ($notices as $notice) {
            printf('<div class="notice notice-%1$s %2$s"><p>%3$s</p></div>',
                $notice['type'],
                $notice['dismissible'],
                $notice['notice']
            );
        }

        // Now we reset our options to prevent notices being displayed forever.
        if (!empty($notices)) {
            delete_option($this->plugin_name . '_notices', []);
        }
    }

}
