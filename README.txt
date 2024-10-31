=== Sensfrx - Fraud Prevention for WooCommerce ===

Contributors: sensfrx

Tags: ai-based protection, account security, registration security, transaction security, anti-fraud

Requires at least: 3.0.1

Tested up to: 6.6.1

Requires PHP: 5.5

Stable tag: 1.0.10

License: GPLv2 or later

License URI: https://www.gnu.org/licenses/gpl-2.0.html




Sensfrx is a reliable online anti-fraud solution tailored for WordPress and WooCommerce, designed to help store for fighting fraud.


== Description ==

Running a WooCommerce store comes with constant fraud risks, from fake registrations to suspicious transactions. Many solutions only focus on one stage, like checkout, but [Sensfrx offers AI-powered fraud prevention](https://sensfrx.ai/woocommerce) that works smarter, covering all entire life cycle of the buyer's journey to keep your store safe.

[How to Install WooCommerce AI-Powered Fraud Prevention Plugin | Step-by-Step Guide](https://www.youtube.com/watch?v=3VZUqghKy_k)
<iframe width="560" height="315" src="https://www.youtube.com/embed/3VZUqghKy_k?si=OGGWTHzMc228uKle" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>

<strong>Here's what makes Sensfrx stand out:</strong>

It starts by detecting fraud through geolocation and the risky profile of IP addresses. Sensfrx identifies users from risky locations or those hiding behind proxies, preventing them from registering or making purchases. It also cross-checks disposable email and shipping addresses to flag mismatched or suspicious details before they become a problem.
One of the key features is credit card and transaction velocity monitoring. This ensures that patterns like rapid or repetitive transactions are flagged, stopping fraudsters early.

With Sensfrx, you're not just reacting to fraud after checkout. It actively blocks suspicious customers during registration, login and checkout. You can also manage your blacklist easily, blocking users by IP, state, zip code, or email, with options to customize messages for blacklisted users.

The real power lies in Sensfrx's AI intelligence. It learns from user behavior over time, improving its accuracy and reducing false positives, keeping your store secure without blocking genuine customers.

By covering everything from registration to transaction completion, Sensfrx offers a proactive approach to fraud, ensuring your WooCommerce store is protected at every stage.




== Installation ==

How to Install SensFRX plugin:



1. Upload `sensfrx.php` to the `/wp-content/plugins/` directory

2. Activate the plugin through the 'Plugins' menu in WordPress

3. Goto SensFRX Settings page from admin menu.

4. Enter Property ID and Property Secret to activate SensFRX. (To get details on how to get Property ID and Secret scroll down to check details)



== Frequently Asked Questions ==

*Is is free using SensFRX?*

SensFRX is providing free full product access to up to 2,500 Monthly Active Users. For more than 2500 monthly active users, you need to buy our paid plans. For more details you can check out https://sensfrx.ai/price.



*How to get Property ID and Property Secret?*

You can signup at https://client.sensfrx.ai and add your property (web application) to get Property ID and Property Secret. For details on how to get those details scroll down.


*What external services does this plugin use?

- **URL**: `https://a.sensfrx.ai`
- **Purpose**: To retrieve and send data required for the plugin's functionality.
- **Authentication**: This service requires an API key which can be obtained by signing up at `https://client.sensfrx.ai`. You will need to enter this API key in the plugin settings.
- **Data Sent**: This plugin sends user data including, but not limited to, user actions, preferences, and settings to the API for processing.
- **Data Received**: This plugin retrieves processed data from the Example API to enhance user experience and provide additional features.
- **Privacy Policy**: You can read the privacy policy of the Example API at `https://sensfrx.ai/privacy-policy`.

- **URL**: `https://p.sensfrx.ai`
- **Purpose**: To retrieve and send data required for the plugin's functionality.
- **Authentication**: This service requires an API key which can be obtained by signing up at `https://client.sensfrx.ai`. You will need to enter this API key in the plugin settings.
- **Data Sent**: This plugin sends user data including, but not limited to, user actions, preferences, and settings to the Pixel API for processing.
- **Data Received**: This plugin retrieves processed data from the Pixel API to enhance user experience and provide additional features.
- **Privacy Policy**: You can read the privacy policy of the Pixel API at `https://sensfrx.ai/privacy-policy`.


*How do I configure external API & service settings? 

To configure the settings:
1. Go to the Settings->Sensfrx - Fraud Prevention for WooCommerce in WordPress.
2. Enter the necessary information like Property ID and Secret Key for the external service integration.
3. Save the settings.



== Screenshots ==

1. This screenshot shows the SensFRX Settings page. Here you can configure the SensFRX WordPress Plugin for activation.

2. This screenshot shows the SensFRX Policies page. Here you can set the policies on how to handle the "Allow", "Challenge" and "Deny" cases.



== Changelog ==

= 1.0.0 =

* SensFRX first version, in this version we have developed an easy-to-install and configure SDK of SensFRX for WordPress users.

= 1.0.1 =
* Added support for manual review
* Fixed Transaction attempt data discrepencies

= 1.0.2 =
* Fixed handling of deny signal on registrations

= 1.0.3 =
* Fixed bugs on deny signal handling on registrations

= 1.0.4 =
* Added activity logs feature

= 1.0.5 =
* Activity logs feature bugs fixed
* Fixed product category information sent with transaction data

= 1.0.6 =
* Bug fixed

= 1.0.7 =
* Custom rules support added for tags and custom fields

= 1.0.8 =
* Admin role users whitelisting feature
* Shadow mode performance improved
* Bug fixes in cron jobs

= 1.0.9 =

* Order Review Feature added
* Bug fixes

= 1.0.10 =

* Bug fixes

== Upgrade Notice ==

= 1.0.0 =

This is the first installation of SensFRX so no upgrade required yet.

= 1.0.1 =

We added support for manual review of transactions and some bugs fixed on transaction attempt data

= 1.0.2 =

We fixed handling of deny signal on registrations

= 1.0.3 =

We fixed bugs on deny signal handling on registrations

= 1.0.4 =

We added activity logs feature

= 1.0.5 =

We fixed bugs related activity logs feature and product category information sent with transaction data

= 1.0.6 =

We fixed some security bugs.

= 1.0.7 =

We added custom rules support by adding support for tags and custom fields

= 1.0.8 =

Admin role users whitelisting feature added
Shadow mode performance improved
Bug fixes in cron jobs

= 1.0.9 =

Order Review Feature added
Bug fixes

= 1.0.10 =

Bug fixes

== How to get Property ID and Property Secret? ==



SensFRX WordPress Plugin is a SDK to install and configure SensFRX. So, in order to really use SensFRX you will need to signup at SensFRX website. Below is the procedure on how to signup and configure SensFRX WordPress Plugin.



1. Visit https://client.sensfrx.ai/signup, fill up the form and register.

2. After registration, login and you will be redirected to add property page. (Property here means your wordpress website)

3. Add the necessary details on the add property page, i.e., property name and property domain. Now, hit next and your proerperty should be added.

4. Now, you will be redirected to the property integration page, here you will find Property ID and will see a Generate button to generate Property Secret. Hit Generate to generate property secret.

5. Copy both Property name and Property Secret and save it somewhere safe.

6. Come to the WordPress website dashboard and open SensFRX Settings page. Paste the Property ID and Property Secret here and hit "Save Changes" button.



This will complete the activation process for SensFRX WordPress Plugin.