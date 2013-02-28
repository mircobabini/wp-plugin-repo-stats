=== WP Plugin Repo Stats ===
Tags: wordpress, plugin, developer, download, count, stats
Requires at least: 3.5
Tested up to: 3.5.1
Contributors: jp2112
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=NRHAAC7Q9Q2X6
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Plugin developers can list their plugins and download counts from wordpress.org.

== Description ==

<strong>Are you a WordPress plugin developer who wants to show off how many plugins you have in the repo and how many times they've been downloaded?</strong> Then look no further.

This plugin lists all of the plugins you have made available on wordpress.org. Using a shortcode on posts or pages, it creates a table listing each plugin and how many downloads it has, as well as the plugin rating.

Inspired by http://www.viper007bond.com/wordpress-plugins/wordpress-download-counter/ and modeled after http://lesterchan.net/portfolio/programming/php/

Requires WordPress 3.5 due to use of transients.

If you use and enjoy this plugin, please rate it and click the "Works" button below so others know that it works with the latest version of WordPress.

== Installation ==

1. Upload plugin through the WordPress interface.

2. Activate the plugin through the 'Plugins' menu in WordPress.

3. Go to Settings &raquo; WordPress Plugin Repo Stats and configure the plugin.

4. Insert shortcode on posts or pages, or call PHP function from functions.php or another plugin.

== Frequently Asked Questions ==

= How do I use the plugin? =

Use a shortcode to call the plugin from any page or post like this:

[plugin-repo-stats]

This will:
<ul>
<li>fetch the download counts for a given wordpress.org profile ID (which you put in the plugin's Settings page, right?)</li>
<li>add rel="nofollow" to each link</li>
<li>use rounded corner CSS</li>
<li>cache the plugin output for one hour (3600 seconds = 60 seconds * 60 minutes * 1 hour)</li>
<li>include plugin star rating image</li>
</ul>

The following are the shortcode parameters and their default values:

<ul>
<li>uid => '' - your wordpress.org profile ID</li>
<li>nofollow => true - include rel="nofollow" after each link?</li>
<li>rounded => true - CSS class to round corners</li>
<li>cachetime => 3600 - number of seconds to cache the plugin output (default: one hour)</li>
<li>showstars => true - whether to show plugin rating stars</li>
<li>show => false - Echo (true) or Return (default).
</ul>

Leave out a parameter to use the default.

<strong>Your wordpress.org profile ID is your login on wordpress.org.</strong> You must include this or the plugin will do nothing.

You can also call the plugin in your functions.php, ex:

`if (function_exists('wpprs')) {
  wpprs(array('uid' => 'your userid', 'show' => true));
}`

= How can I style the output? =

The output contains extensive CSS classes you can target in your style.css. You can style alternating rows and style each table column separately. View the output source to see the table CSS structure, or browse the plugin's 'css' folder. The CSS to duplicate the screenshot is included in the plugin. You will need to override this if you want to see something different.

= I inserted the shortcode but don't see anything on the page. =

Clear your browser cache and also clear your cache plugin (if any).

= I changed a setting on the plugin's settings page but the table still reflects my old settings. =

The plugin output is cached (using WP 3.5+ transients) to avoid overtaxing the wordpress.org website with excessive scraping requests. This may result in an IP ban, which would cause denial of service. Wait until the cache expires, then reload the page to view your settings changes.

Even if you specify a very low cache time (ex: 60 seconds), the plugin will only re-fetch content every 5 minutes. Unless the download count is very low, it is highly unlikely that any given plugin is downloaded so often that someone visiting a page after five minutes would actually notice how many more downloads it has.

= I called the plugin on two different pages with two different cache times, but the cache doesn't refresh as expected. =

The cache is userid-dependent and site-wide. You cannot specify different cache times for the same userid concurrently -- the first shortcode or function call that is made on an empty cache will set the cache time.

= The plugin feels slow. =

WP Plugin Repo Stats has to visit a given user's wordpress.org profile page, then (if requested) visit each plugin's page and parse it for the plugin's rating. This is time consuming and totally dependent on wordpress.org's network availability. If a given user has 30 plugins, it could take a relatively significant amount of time to fetch each page. To optimize the plugin's operation, if wordpress.org does not respond then the star rating is left blank (no stars).

To speed up the plugin operation, increase the cache time and (if possible) do not request plugin star ratings (uncheck 'Show plugin ratings?' on the plugin settings page).

= I want to remove the post editor toolbar button. =

Add this to your functions.php:

`remove_action('admin_print_footer_scripts', 'add_wpprs_quicktag');`

== Screenshots ==

1. Sample screenshot
2. Settings page

== Changelog ==

= 0.0.3 =
* added admin menu
* option to exclude star ratings
* added rel=nofollow option
* added quicktag to post editor toolbar

= 0.0.2 =
added stars rating, WP logo, changed default cache time to one hour

= 0.0.1 =
created

== Upgrade Notice ==

= 0.0.3 =
* added admin menu
* option to exclude star ratings
* added rel=nofollow option

= 0.0.2 =
added stars rating, WP logo, changed default cache time to one hour