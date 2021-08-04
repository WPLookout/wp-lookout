=== WP Lookout - Theme & Plugin Update Notifications ===
Contributors: chrishardie
Tags: notification, upgrade, admin, monitor, updates
Requires at least: 4.5
Tested up to: 5.8
Requires PHP: 5.6
Stable tag: 1.2.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

WP Lookout tracks updates to the plugins and themes you depend on, sending you notifications about changes.

== Description ==

This plugin is just one way to use [WP Lookout](https://wplookout.com/), a free service that tracks changes and updates to the plugins and themes you depend on and then sends you notifications. New version? Changelog update that mentions security? New plugin ownership? WP Lookout is looking out for you.

https://vimeo.com/453298744

After installing and activating this plugin, simply input the API token generated in your [free WP Lookout account](https://app.wplookout.com/register) to quickly start tracking the plugins and themes in use on your site. (There is also a WP CLI command available to automate setting the API key.) There are no limits to the number of sites you can connect!

Free features include:

* Theme/plugin monitoring across unlimited websites
* 24/7 tracking of changes and new releases
* Email notifications, with optional upgrades for Slack and custom Webhook notifications
* 2FA account security
* Email support

If WordPress auto-updates are sending you too many notifications from all of your sites, you can also use WP Lookout to stay on top of what's changing in a consolidated, easy to digest way, and then filter out those other notifications.

For more information about WP Lookout, [check out our website at WPLookout.com](https://wplookout.com).

By enabling a connection between your site and WP Lookout, you agree to the [WP Lookout terms of service](https://wplookout.com/terms-and-conditions/).

== Installation ==

WP Lookout is most easily installed via the Plugins tab in your admin dashboard.

== Frequently Asked Questions ==

= What WP CLI commands are available? =

You can customize WP Lookout's behavior with these WP CLI commands:

* `wp wplookout set_api_key <key>`: Sets the WP Lookout account API key to use for updates.
* `wp wplookout hide_settings_page <true|false>`: Hides or un-hides the WP Lookout settings page in the WordPress admin.

These commands are intended for use by site managers and developers who want to automate WP Lookout setup.

= What information is transmitted to WP Lookout? =

On a regular basis this plugin will send several pieces of information to your WP Lookout account via our API:

* The URL of this WordPress site
* A list of the plugins installed on this site, with current version
* A list of the themes installed on this site, with current version

No other part of your site configuration or content is transmitted or stored. You can disable this connection at any time by removing the API key from the settings page, or by disabling or deleting this plugin from your WordPress site.

== Screenshots ==

1. WP Lookout tracking activity example
2. The WP Lookout plugin settings screen
3. Example plugin update notification from WP Lookout

== Changelog ==

= 1.2.0 =

* Enhancement: validate API key in settings screen before saving
* Enhancement: use newer version and format of WP Lookout import API requests
* Maintenance: log API requests and results when WP_DEBUG and WP_DEBUG_LOG are enabled
* Maintenance: minor bits of cleanup, code standards, language consistency

= 1.1.0 =

* Enhancement: add WP CLI command to set API key via command line
* Enhancement: add WP CLI command to hide or un-hide WP Lookout settings page in admin UI
* Fix: hide API key from user view in API key settings field
* Fix: better validation for theme and plugin slugs before sending to WP Lookout

= 1.0.1 =

* Enhancement: make plugin settings page easier to find upon activation
* Enhancement: run import attempt when API key is first saved

= 1.0.0 =

* Initial release
