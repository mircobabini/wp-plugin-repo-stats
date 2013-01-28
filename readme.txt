=== WP Plugin Repo Stats ===
Contributors: jp2112
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=NRHAAC7Q9Q2X6
Tags: wordpress, plugin, developer, download, count, stats
Requires at least: 3.5
Tested up to: 3.5
License: GPL3
License URI: http://www.gnu.org/licenses/gpl.html

Plugin developers can list their plugins and download counts from wordpress.org.

== Description ==

This plugin lists all of the plugins you have made available on wordpress.org. Using a shortcode on posts or pages, it creates a table listing each plugin and how many downloads it has.

Inspired by http://www.viper007bond.com/wordpress-plugins/wordpress-download-counter/ and modeled after http://lesterchan.net/portfolio/programming/php/

If you use and enjoy this plugin, please rate it and click the "Works" button below so others know that it works with the latest version of WordPress.

== Installation ==

1. Upload 'wp_plugin_repo_stats.zip' through the WordPress interface (http://www.yourblogurl.com/wp-admin/plugin-install.php?tab=upload), or
unzip the archive and place the PHP file 'wp_plugin_repo_stats.php' in your wp-content/plugins folder.

2. Activate the plugin through the 'Plugins' menu in WordPress.

3. Insert shortcode on posts or pages.

== Frequently Asked Questions ==

= How do I use the plugin? =

Use a shortcode to call the plugin from any page or post like this:

[plugin-repo-stats uid="your userid" rounded="false" cachetime="3600"]

This will:
<ul>
<li>fetch the download counts for a given wordpress.org profile ID</li>
<li>add rel="nofollow" to each link</li>
<li>use rounded corner CSS</li>
<li>cache the plugin output for one hour (3600 seconds = 60 seconds * 60 minutes)</li>
</ul>

The following are the shortcode parameters and their default values:

<ul>
<li>uid => '' - your wordpress.org profile ID</li>
<li>nofollow => true - include rel="nofollow" after each link?</li>
<li>rounded => true - CSS class to round corners</li>
<li>cachetime => 43200 - number of seconds to cache the plugin output</li>
</ul>

Leave out a parameter to use the default.

<strong>Your wordpress.org profile ID is your login on wordpress.org.</strong>

= How can I style the output? =

The output contains extensive CSS classes you can target in your style.css. You can style alternating rows, or style each table column. Here is some sample CSS you can use:

`.wpprs {margin:20px}
.wpprs-rounded-corners{-moz-border-radius:10px;-webkit-border-radius:10px;border-radius:10px}
.wpprs-top {padding:18px;background:#efefef;text-align:center}
.wpprs-top h2 {font-size:48px}
.wpprs-plugincount {font-weight:bold}
.wpprs-body h3 {margin:25px 0}
.wpprs-table {width:90%}
.wpprs-table th {text-align:left}
.wpprs-table td {border-top:1px solid #efefef;border-bottom:1px solid #efefef}
.wpprs-table .wpprs-evenrow {background:#efefef}`

== Screenshots ==

== Changelog ==

= 0.0.1 =
created

== Upgrade Notice ==