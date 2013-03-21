=== WP Plugin Repo Stats ===
Tags: wordpress, plugin, developer, download, count, rating, stats, rounded
Requires at least: 3.5
Tested up to: 3.5.1
Contributors: jp2112
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7EX9NB9TLFHVW
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Plugin developers can list their plugins and download counts from wordpress.org.

== Description ==

<strong>Are you a WordPress plugin developer who wants to show off how many plugins you have in the repo and how many times they've been downloaded?</strong> Then look no further.

This plugin lists all of the plugins you have made available on wordpress.org. Using a shortcode on posts or pages, it creates a table listing each plugin and how many downloads it has, as well as the plugin rating.

= Features =

- Display your plugin stats on any post or page
- Works with most browsers, but degrades nicely in older browsers
- CSS only loads on pages with shortcode or function call
- Includes star rating for each plugin (optional)

Inspired by http://www.viper007bond.com/wordpress-plugins/wordpress-download-counter/ and modeled after http://lesterchan.net/portfolio/programming/php/

Requires WordPress 3.5 due to use of transients for caching.

<strong>If you use and enjoy this plugin, please rate it and click the "Works" button below so others know that it works with the latest version of WordPress.</strong>

== Installation ==

1. Upload plugin through the WordPress interface.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to Settings &raquo; WordPress Plugin Repo Stats and configure the plugin.
4. Insert shortcode on posts or pages, or call PHP function from functions.php or another plugin.

To remove this plugin, go to the 'Plugins' menu in WordPress, find the plugin in the listing and click "Deactivate". After the page refreshes, find the plugin again in the listing and click "Delete".

== Frequently Asked Questions ==

= What are the plugin defaults? =

By default, following values are passed to the plugin:

- uid => '' (blank)
- nofollow => true
- rounded => false
- showstars => true
- cachetime => 3600 (seconds)
- sortorder => ascending
- opennewwindow => false

Also, by default, values are returned, not echoed. You need to explicitly request that the plugin output be echoed by passing `'show' => true` in the array when calling the plugin via function.

To change defaults on a site-wide basis, go to the Settings page. To change defaults on a per-shortcode basis, pass new values to each shortcode or function call. Priority is given to parameters passed via shortcode or function.

= How do I use the plugin? =

Use a shortcode to call the plugin from any page or post like this:

[plugin-repo-stats]

This will:
<ul>
<li>fetch the download counts for a given wordpress.org profile ID (which you put in the plugin's Settings page, right?)</li>
<li>add rel="nofollow" to each link</li>
<li>use rounded corner CSS</li>
<li>cache the plugin output for one hour (3600 seconds = 60 seconds * 60 minutes * 1 hour)</li>
<li>include plugin star rating images</li>
<li>links open in same window</li>
</ul>

<strong>Your wordpress.org profile ID is your login on wordpress.org.</strong> You must include this or the plugin will do nothing.

You can also call the plugin in your functions.php, ex:

`if (function_exists('wpprs')) {
  wpprs(array('uid' => 'your userid', 'show' => true));
}`

Always wrap plugin function calls with a `function_exists` check so that your site doesn't go down if the plugin isn't active.

= How can I style the output? =

The output contains extensive CSS classes you can target in your style.css. You can style alternating rows and style each table column separately. View the output source to see the table CSS structure, or browse the plugin's 'css' folder. The CSS to duplicate the screenshot is included in the plugin. You will need to override this if you want to see something different.

= I inserted the shortcode but don't see anything on the page. =

Clear your browser cache and also clear your cache plugin (if any). If you still don't see anything, check your webpage source for the following:

`<!-- WP Plugin Repo Stats: plugin is disabled. Check Settings page. -->`

This means you didn't pass a necessary setting to the plugin, so it disabled itself. You need to pass at least the wordpress.org userid, either by entering it on the settings page or passing it to the plugin in the shortcode or PHP function. You should also check that the "enabled" checkbox on the plugin settings page is checked. If this box is unchecked, the plugin will do nothing even if you pass your userid.

= I changed a setting on the plugin's settings page but the table still reflects my old settings. =

The plugin output is cached (using WP 3.5+ transients) to avoid overtaxing the wordpress.org website with excessive scraping requests. Excessive scraping may result in an IP ban, which would cause denial of service. Wait until the cache expires, then reload the page to view your settings changes.

Even if you specify a very low cache time (ex: 60 seconds), the plugin will only re-fetch content every 5 minutes. <strong>Please do not edit the plugin to change this.</strong>
Unless the download count is very low, it is <em>highly unlikely</em> that any given plugin is downloaded so often that someone visiting a page after five minutes would actually notice how many more downloads it has.

You might also consider clearing your browser cache and your caching plugin.

= I cleared my browser cache and my caching plugin but the output is still old. =

Are you using a plugin that minifies CSS? If so, try excluding the plugin CSS file from minification.

= I called the plugin on two different pages with two different cache times, but the cache doesn't refresh as expected. =

The cache is userid-dependent and site-wide. You cannot specify different cache times for the same userid concurrently -- the first shortcode or function call that is made on an empty cache will set the cache time. Ideally you are only displaying the output of this plugin once on your site.

= The plugin feels slow. =

WP Plugin Repo Stats has to visit a given user's wordpress.org profile page, then (if requested) visit each plugin's page and parse it for the plugin's rating. This is time consuming and totally dependent on wordpress.org's network availability. If a given user has a large amount of plugins in the repo, it could take a relatively significant amount of time to fetch each page. To optimize the plugin's operation, the star rating is left blank if there is a page error on wordpress.org (no stars).

To speed up the plugin operation, increase the cache time and (if possible) do not request plugin star ratings (uncheck 'Show plugin ratings?' on the plugin settings page).

= I want to remove the post editor toolbar button. =

Add this to your functions.php:

`remove_action('admin_print_footer_scripts', 'add_wpprs_quicktag');`

== Screenshots ==

1. Sample screenshot
2. Settings page

== Changelog ==

= 0.0.6 =
- sanitize some inputs to reduce attack vectors
- added donate link on admin page
- admin page CSS added

= 0.0.5 =
- moved quicktag script further down the page
- minor admin page update
- added option to open links in new window
- minor code refactoring
- css file refactoring

= 0.0.4 =
- updated admin messages code
- output can be sorted by plugin name either ascending or descending
- updated readme

= 0.0.3 =
- added admin menu
- option to exclude star ratings
- added rel=nofollow option
- added quicktag to post editor toolbar

= 0.0.2 =
added stars rating, WP logo, changed default cache time to one hour

= 0.0.1 =
created

== Upgrade Notice ==

= 0.0.6 =
- sanitize some inputs to reduce attack vectors
- added donate link on admin page
- admin page CSS added
- minor admin page tweaks

= 0.0.5 =
- moved quicktag script further down the page
- minor admin page update
- added option to open links in new window
- minor code refactoring
- css file refactoring

= 0.0.4 =
- updated admin messages code
- output can be sorted by plugin name either ascending or descending
- updated readme

= 0.0.3 =
- added admin menu
- option to exclude star ratings
- added rel=nofollow option

= 0.0.2 =
added stars rating, WP logo, changed default cache time to one hour