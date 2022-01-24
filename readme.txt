=== WP Lookout - Theme & Plugin Update Notifications ===
Contributors: chrishardie
Tags: notifications, updates, monitoring, plugin updates, theme updates, email
Requires at least: 4.5
Tested up to: 5.9
Requires PHP: 5.6
Stable tag: 1.2.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

WP Lookout tracks updates to the plugins and themes you depend on, sending you notifications about changes.

== Description ==

[WP Lookout](https://wplookout.com/) is a free tool that tracks important changes and updates to the plugins and themes you depend on. Instead of visiting wp-admin on all of your sites to see what's new and what needs updating, WP Lookout brings the information to you in a timely, helpful, centralized way.

New version released? Changelog update that mentions a security fix? New plugin author ownership? WP Lookout will notify you of these events and others by email, Slack, webhook, RSS feed and more.

After installing and activating this plugin, simply input the API token generated in your [free WP Lookout account](https://app.wplookout.com/register) to quickly start tracking the plugins and themes in use on your site. (There is also a WP CLI command available to automate setting the API key.) There are no limits to the number of sites you can connect!

Free features include:

* [Theme and plugin monitoring](https://wplookout.com/docs/creating-trackers/) for unlimited websites
* [24/7 tracking](https://wplookout.com/docs/tracking/) of critical changes and new releases
* [Email notifications](https://wplookout.com/docs/notifications/), with optional upgrades for Slack and custom Webhook notifications
* At-a-glance [dashboard overview](https://wplookout.com/docs/sites/) of all your connected sites
* 2FA account security
* Friendly email support

If you're not ready to go all in on auto-updates, or if you're getting too many update notifications from all of your sites, WP Lookout is a great alternative. Knowing the details of what's changing and what it means for the sites you've built will help you avoid any problems and better troubleshoot theme or plugin conflicts that do arise.

https://vimeo.com/453298744

For more information about WP Lookout, [check out our website at WPLookout.com](https://wplookout.com).

== Installation ==

WP Lookout is most easily installed via the Plugins tab in your admin dashboard.

After installing and activating this plugin, input the API token generated in your [free WP Lookout account](https://app.wplookout.com/register) to start tracking the plugins and themes in use on your site.

== Frequently Asked Questions ==

= What WP CLI commands are available? =

You can customize WP Lookout's behavior with these WP CLI commands:

* `wp wplookout set_api_key <key>`: Sets the WP Lookout account API key to use for updates.
* `wp wplookout hide_settings_page <true|false>`: Hides or un-hides the WP Lookout settings page in the WordPress admin.

These commands are intended for use by site managers and developers who want to automate WP Lookout setup.

= What information is transmitted to WP Lookout? =

On a regular basis this plugin will send several pieces of information to your WP Lookout account via our API:

* The URL and current core version of this WordPress site
* A list of the plugins installed on this site, with current version
* A list of the themes installed on this site, with current version

No other part of your site configuration or content is transmitted or stored. You can disable this connection at any time by removing the API key from the settings page, or by disabling or deleting this plugin from your WordPress site.

== Screenshots ==

1. WP Lookout tracking activity example
2. The WP Lookout plugin settings screen
3. Example plugin update email notification
4. Sites dashboard example

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
