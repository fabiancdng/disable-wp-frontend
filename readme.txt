=== Disable WP Frontend ===
Contributors: fabiancdng
Tags: disable, wp frontend, frontend, headless
Donate link: https://paypal.me/fabiancdng
Requires at least: 6.0
Tested up to: 6.4.2
Requires PHP: 7.4

Disables the WordPress front end (public-facing part of the website).

== Description ==
Disables the WordPress front end (public-facing part of the website).
Leaves Dashboard, API, Media Uploads, and Cron untouched.


== Changelog ==

= 2.0.1 =
* Fix: Uninstall script doesn't delete settings option correctly
* Fix: Regex pattern matching release asset for plugin update check is missing delimiters

= 2.0 =
* New: Settings page in the WordPress dashboard to whitelist paths and enable/disable the frontend
* New: Ability to update the plugin within the WordPress dashboard to the latest release
* Refactor: Use PSR-4 coding standards, composer, and autoloading

= 1.0.2 =
* Refactor: Use `$path_whitelist` array for easily whitelisting paths

= 1.0.1 =
* Fix: `/wp-content/uploads/` exception catches all requests

= 1.0 =
* Initial release