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

Alpha SMS connects your WordPress or WooCommerce site to Bangladeshi SMS messaging. It confirms the phone numbers people enter with one-time passwords (OTP) and keeps both customers and shop owners updated with simple text alerts.

= Key Features =
* OTP checks on registration, login, and checkout pages. The field is built with WooCommerce hooks, so it keeps the same name no matter which theme or builder you use.
* Safe limits for how often someone can request a new code and how long each code stays valid, stopping spam and repeat use.
* Order status texts for customers and admins with templates you can edit to match your tone.
* Campaign tools that let you send bulk SMS messages to saved lists or any custom number you need.
* Shortcodes and settings that help you point the OTP field at a different phone input when the default `#billing_phone` is not available.

= How it works =
1. A visitor fills out a supported form and taps “Send OTP.” The plugin sends a code to the phone number using the Alpha SMS gateway.
2. The visitor types the received code into the OTP box. Alpha SMS checks it on the server before allowing the form to finish.
3. Valid codes are removed right away. If something goes wrong, the plugin shows a clear, translatable error so people know what to do next.


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
