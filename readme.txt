=== WP Plugin Repo Stats ===
Contributors: jp2112
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=NRHAAC7Q9Q2X6
Tags: wordpress, plugin, developer, download, count, stats
Requires at least: 3.5
Tested up to: 3.5.1
License: GPL3
License URI: http://www.gnu.org/licenses/gpl.html

Plugin developers can list their plugins and download counts from wordpress.org.

== Description ==

<strong>Are you a WordPress plugin developer who wants to show off how many plugins you have in the repo and how many times they've been downloaded?</strong> Then look no further.

This plugin lists all of the plugins you have made available on wordpress.org. Using a shortcode on posts or pages, it creates a table listing each plugin and how many downloads it has, as well as the plugin rating.

Inspired by http://www.viper007bond.com/wordpress-plugins/wordpress-download-counter/ and modeled after http://lesterchan.net/portfolio/programming/php/

If you use and enjoy this plugin, please rate it and click the "Works" button below so others know that it works with the latest version of WordPress.

== Installation ==

1. Upload 'wp_plugin_repo_stats.zip' through the WordPress interface (http://www.yourblogurl.com/wp-admin/plugin-install.php?tab=upload), or unzip the archive and place the PHP file 'wp_plugin_repo_stats.php' in your wp-content/plugins folder.

2. Activate the plugin through the 'Plugins' menu in WordPress.

3. Insert shortcode on posts or pages.

== Frequently Asked Questions ==

= How do I use the plugin? =

Use a shortcode to call the plugin from any page or post like this:

[plugin-repo-stats uid="your userid" cachetime="7200"]

This will:
<ul>
<li>fetch the download counts for a given wordpress.org profile ID</li>
<li>add rel="nofollow" to each link</li>
<li>use rounded corner CSS</li>
<li>cache the plugin output for two hours (7200 seconds = 60 seconds * 60 minutes * 2 hours)</li>
</ul>

The following are the shortcode parameters and their default values:

<ul>
<li>uid => '' - your wordpress.org profile ID</li>
<li>nofollow => true - include rel="nofollow" after each link?</li>
<li>rounded => true - CSS class to round corners</li>
<li>cachetime => 3600 - number of seconds to cache the plugin output (default: one hour)</li>
</ul>

Leave out a parameter to use the default.

<strong>Your wordpress.org profile ID is your login on wordpress.org.</strong>

= How can I style the output? =

The output contains extensive CSS classes you can target in your style.css. You can style alternating rows, or style each table column. View the output source to see the table CSS structure. The CSS to duplicate the screenshot is included in the plugin. You will need to override this if you want to see something different.

== Screenshots ==

1. Sample screenshot

== Changelog ==

= 0.0.2 =
added stars rating, WP logo, changed default cache time to one hour

= 0.0.1 =
created

== Upgrade Notice ==

= 0.0.2 =
added stars rating, WP logo, changed default cache time to one hour