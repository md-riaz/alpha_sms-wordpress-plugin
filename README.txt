=== Alpha SMS ===
Contributors: alphanetbd,mdriazwd
Tags: order notification, order SMS, woocommerce sms integration, sms plugin, mobile verification, OTP, SMS notifications, two-step verification, OTP verification, SMS, signup security, user verification, user security, SMS gateway, order SMS, order notifications, WordPress OTP, 2FA, login OTP, WP SMS
Requires at least: 3.5
Tested up to: 6.6.2
Requires PHP: 5.6
Stable tag: 1.0.11
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WooCommerce SMS Notification. SMS OTP Verification for Registration and Login forms, 2FA Login.

== Description ==

Alpha SMS adds Bangladeshi SMS delivery and verification to WordPress and WooCommerce. It keeps customer phone numbers accurate with OTP challenges and keeps shop owners informed with transaction updates.

= Key Features =
* OTP verification for WordPress and WooCommerce registration, login, and checkout screens using WooCommerce-managed form fields.
* Configurable rate limiting and expiry windows that stop repeated OTP requests and block code reuse.
* Order status notifications for customers and admins with customizable SMS templates.
* Bulk messaging tools for campaigns to any stored or custom phone numbers.
* Shortcodes and settings to target alternate phone inputs when the default billing phone is customized by a page builder.

= How it works =
1. When a supported form is submitted, the plugin sends an OTP to the supplied mobile number using Alpha SMS.
2. Customers confirm the OTP, and the plugin validates it server-side before completing the action.
3. Verified codes are cleared immediately, while failed attempts trigger informative, translatable errors.


== Installation ==

= From your WordPress dashboard =
1. Visit `Plugins > Add New`
2. Search for `Alpha SMS`. Find and Install `Alpha SMS`
3. Activate the plugin from your Plugins page

== Frequently Asked Questions ==

= Which forms are supported right now? =
WordPress default registration form, WooCommerce registration form, WooCommerce checkout form, Default WordPress Login Form


== Screenshots ==

1. Configuration settings for the plugin.
2. Campaign form for sending bulk sms.

== Changelog ==

= 1.0.11 =
* Added a WooCommerce-managed OTP field that renders consistently across themes and page builders.
* Routed OTP requests through WooCommerce AJAX endpoints with transient-backed storage and validation.
* Hardened OTP rate limiting and cleanup to prevent repeated code reuse during checkout and login.

= 1.0.4 =
* Separated message for order status change

= 1.0.2 =
* Order SMS Notification fixed

= 1.0.1 =
* fixed woocommerce registration issue.

= 1.0.0 =
* First version of plugin.
