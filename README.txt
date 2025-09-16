=== Alpha SMS ===
Contributors: alphanetbd, mdriazwd
Tags: order notification, order SMS, woocommerce sms integration, sms plugin, mobile verification, OTP, SMS notifications, two-step verification, OTP verification, SMS, signup security, user verification, user security, SMS gateway, order SMS, order notifications, WordPress OTP, 2FA, login OTP, WP SMS
Requires at least: 3.5
Tested up to: 6.6.2
Requires PHP: 5.6
Stable tag: 1.0.11
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Alpha SMS connects your WordPress and WooCommerce store with the Alpha SMS platform to deliver OTP verification, transactional messaging, and order notifications for Bangladeshi mobile numbers.

== Description ==

= Overview =
Alpha SMS makes it easy to add SMS-based two-factor authentication and transactional notifications to your WordPress site. Replace unreliable email-based logins with one-time passwords, confirm customer phone numbers during registration, and keep shoppers updated with automated WooCommerce order status messages.

= Key Features =
* OTP verification for WordPress and WooCommerce registration and login forms.
* WooCommerce order status notifications for customers and administrators.
* Bulk SMS campaign tool for WordPress and WooCommerce users or custom phone lists.
* Message templates that can be tailored directly from the WordPress admin.
* Built specifically for Bangladeshi mobile operators using the Alpha SMS gateway.

= How It Works =
1. A user submits a supported form (registration, login, or checkout).
2. Alpha SMS sends a one-time password (OTP) to the provided Bangladeshi mobile number.
3. The OTP is validated before the registration, login, or checkout process is completed.
4. WooCommerce stores can optionally send transactional order notifications to customers and administrators.

= Requirements =
* An active Alpha SMS account and API key from https://sms.net.bd/.
* WooCommerce 3.0+ for eCommerce-specific features (optional for OTP-only usage).

== Installation ==

= From your WordPress dashboard =
1. Visit `Plugins > Add New`.
2. Search for `Alpha SMS`, then install the plugin.
3. Activate the plugin from your Plugins page.
4. Navigate to `Alpha SMS` in the WordPress admin menu and add your API credentials.
5. Enable OTP flows, WooCommerce notifications, or campaigns as needed.

== Frequently Asked Questions ==

= Which forms are supported right now? =
Alpha SMS currently works with the default WordPress registration form, the WordPress login form, the WooCommerce registration form, the WooCommerce checkout form, and the WooCommerce login form.

= Do I need an Alpha SMS account? =
Yes. You must have an Alpha SMS account with available credits in order to send OTPs and notifications. Enter your API key and token in the plugin settings to connect your site.

= Does the plugin work without WooCommerce? =
Yes. OTP verification for WordPress and WooCommerce registration and login works independently of WooCommerce. OTP data is stored with WordPress transients, so WooCommerce sessions are not required. WooCommerce is only needed if you want order notifications or checkout verification.

== Screenshots ==

1. Configuration settings for the plugin.
2. Campaign form for sending bulk SMS.

== Changelog ==

= 1.0.11 =
* Updated the WooCommerce checkout OTP workflow to clone whichever submit button is present instead of relying on the `#place_order` ID so guest checkout themes remain compatible.
* Simplified OTP storage to rely solely on WordPress transients instead of WooCommerce sessions.
* Added a WordPress transient-based OTP fallback for sites without WooCommerce while removing the unused session bootstrapper.
* Refreshed plugin documentation and guidance in the readme.

= 1.0.4 =
* Separated messages for order status changes.

= 1.0.2 =
* Order SMS notification fixes.

= 1.0.1 =
* Fixed WooCommerce registration issue.

= 1.0.0 =
* First version of the plugin.
