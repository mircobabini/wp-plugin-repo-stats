<?php
/*
Plugin Name: WordPress Plugin Repo Stats
Plugin URI: http://www.jimmyscode.com/wordpress/wp-plugin-repo-stats/
Description: Plugin developers -- display the names and download counts for your WordPress plugins in a CSS-stylable table. Includes plugin ratings.
Version: 0.0.3
Author: Jimmy Pe&ntilde;a
Author URI: http://www.jimmyscode.com/
License: GPLv2 or later
*/
// plugin constants
define('WPPRS_VERSION', '0.0.3');
define('WPPRS_PLUGIN_NAME', 'WordPress Plugin Repo Stats');
define('WPPRS_SLUG', 'wp-plugin-repo-stats');
define('WPPRS_LOCAL', 'wpprs');
define('WPPRS_OPTION', 'wpprs');
/* default values */
define('WPPRS_DEFAULT_ENABLED', 1);
define('WPPRS_DEFAULT_NOFOLLOW', 1);
define('WPPRS_DEFAULT_SHOW_STARS', 1);
define('WPPRS_DEFAULT_CACHETIME', 3600);
define('WPPRS_MIN_CACHE_TIME', 300);
define('WPPRS_DEFAULT_UID', '');
define('WPPRS_DEFAULT_ROUNDED', 1);
/* option array member names */
define('WPPRS_DEFAULT_ENABLED_NAME', 'enabled');
define('WPPRS_DEFAULT_NOFOLLOW_NAME', 'nofollow');
define('WPPRS_DEFAULT_SHOW_STARS_NAME', 'stars');
define('WPPRS_DEFAULT_CACHETIME_NAME', 'cachetime');
define('WPPRS_DEFAULT_UID_NAME', 'uid');
define('WPPRS_DEFAULT_ROUNDED_NAME', 'rounded');

// add custom quicktag
add_action('admin_print_footer_scripts', 'add_wpprs_quicktag');
function add_wpprs_quicktag() {
?>
<script>
QTags.addButton('wpprs', 'Plugin Repo Stats', '[plugin-repo-stats]', '', '', 'display WP Plugin Repo Stats', '' );
</script>
<?php }

// localization to allow for translations
add_action('init', 'wpprs_translation_file');
function wpprs_translation_file() {
  $plugin_path = plugin_basename(dirname(__FILE__)) . '/translations';
  load_plugin_textdomain(WPPRS_LOCAL, '', $plugin_path);
  register_wpprs_style();
}
// tell WP that we are going to use new options
add_action('admin_init', 'wpprs_options_init');
function wpprs_options_init() {
  register_setting('wpprs_options', WPPRS_OPTION);
}
// add Settings sub-menu
add_action('admin_menu', 'wpprs_plugin_menu');
function wpprs_plugin_menu() {
  add_options_page(WPPRS_PLUGIN_NAME, WPPRS_PLUGIN_NAME, 'manage_options', WPPRS_SLUG, 'wpprs_page');
}
// plugin settings page
// http://planetozh.com/blog/2009/05/handling-plugins-options-in-wordpress-28-with-register_setting/
// http://www.onedesigns.com/tutorials/how-to-create-a-wordpress-theme-options-page
function wpprs_page() {
  // check perms
  if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permission to access this page', WPPRS_LOCAL));
  }
?>
  <div class="wrap">
    <?php screen_icon(); ?>
    <h2><?php echo WPPRS_PLUGIN_NAME; ?></h2>
    <form method="post" action="options.php">
      <?php settings_fields('wpprs_options'); ?>
      <?php $options = wpprs_getpluginoptions(); ?>
      <?php update_option(WPPRS_OPTION, $options); ?>
      <table class="form-table">
        <tr valign="top"><th scope="row"><?php _e('Plugin enabled?', WPPRS_LOCAL); ?></th>
		<td><input type="checkbox" name="wpprs[<?php echo WPPRS_DEFAULT_ENABLED_NAME; ?>]" value="1" <?php checked('1', $options[WPPRS_DEFAULT_ENABLED_NAME]); ?> /></td>
        </tr>
	  <tr valign="top"><td colspan="2"><?php _e('Is plugin enabled? Uncheck this to turn it off temporarily.', WPPRS_LOCAL); ?></td></tr>
        <tr valign="top"><th scope="row"><?php _e('WordPress.org Userid', WPPRS_LOCAL); ?></th>
		<td><input type="text" name="wpprs[<?php echo WPPRS_DEFAULT_UID_NAME; ?>]" value="<?php echo $options[WPPRS_DEFAULT_UID_NAME]; ?>" style="width:200px" /></td>
        </tr>
	  <tr valign="top"><td colspan="2"><?php _e('Enter your wordpress.org userid.', WPPRS_LOCAL); ?></td></tr>
        <tr valign="top"><th scope="row"><?php _e('Nofollow plugin link(s)?', WPPRS_LOCAL); ?></th>
		<td><input type="checkbox" name="wpprs[<?php echo WPPRS_DEFAULT_NOFOLLOW_NAME; ?>]" value="1" <?php checked('1', $options[WPPRS_DEFAULT_NOFOLLOW_NAME]); ?> /></td>
        </tr>
	  <tr valign="top"><td colspan="2"><?php _e('Check this box to add rel="nofollow" to WP plugin repo links.', WPPRS_LOCAL); ?></td></tr>
        <tr valign="top"><th scope="row"><?php _e('Rounded corner CSS?', WPPRS_LOCAL); ?></th>
		<td><input type="checkbox" name="wpprs[<?php echo WPPRS_DEFAULT_ROUNDED_NAME; ?>]" value="1" <?php checked('1', $options[WPPRS_DEFAULT_ROUNDED_NAME]); ?> /></td>
        </tr>
	  <tr valign="top"><td colspan="2"><?php _e('Check this box to use rounded corner CSS on the table header.', WPPRS_LOCAL); ?></td></tr>
        <tr valign="top"><th scope="row"><?php _e('Show plugin ratings?', WPPRS_LOCAL); ?></th>
		<td><input type="checkbox" name="wpprs[<?php echo WPPRS_DEFAULT_SHOW_STARS_NAME; ?>]" value="1" <?php checked('1', $options[WPPRS_DEFAULT_SHOW_STARS_NAME]); ?> /></td>
        </tr>
	  <tr valign="top"><td colspan="2"><?php _e('Check this box to show plugin star ratings.', WPPRS_LOCAL); ?></td></tr>
        <tr valign="top"><th scope="row"><?php _e('Cache time (seconds)', WPPRS_LOCAL); ?></th>
		<td><input type="text" name="wpprs[<?php echo WPPRS_DEFAULT_CACHETIME_NAME; ?>]" value="<?php echo $options[WPPRS_DEFAULT_CACHETIME_NAME]; ?>" style="width:200px" /></td>
        </tr>
	  <tr valign="top"><td colspan="2"><?php _e('Enter time in seconds between cache refreshes. Default is <strong>' . WPPRS_DEFAULT_CACHETIME . '</strong> seconds, minimum is <strong>' . WPPRS_MIN_CACHE_TIME . '</strong> seconds.', WPPRS_LOCAL); ?></td></tr>
      </table>
      <p class="submit">
      <input type="submit" class="button-primary" value="<?php _e('Save Changes', WPPRS_LOCAL); ?>" />
      </p>
    </form>
    <h2>Support</h2>
    <div style="background:#eff;border:1px solid gray;padding:20px">
    If you like this plugin, please <a href="http://wordpress.org/extend/plugins/<?php echo WPPRS_SLUG; ?>/">rate it on WordPress.org</a> and click the "Works" button so others know it will work for your WordPress version. For support please visit the <a href="http://wordpress.org/support/plugin/<?php echo WPPRS_SLUG; ?>">forums</a>.
    </div>
  </div>
  <?php  
}

// shortcode for plugin output
add_shortcode('plugin-repo-stats', 'wpprs');
function wpprs($atts) {
  extract( shortcode_atts( array(
	'uid' => WPPRS_DEFAULT_UID, 
      'nofollow' => WPPRS_DEFAULT_NOFOLLOW, 
	'rounded' => WPPRS_DEFAULT_ROUNDED, 
	'cachetime' => WPPRS_DEFAULT_CACHETIME, 
      'showstars' => WPPRS_DEFAULT_SHOW_STARS, 
      'show' => false
      ), $atts ) );

  $options = wpprs_getpluginoptions();
  $isenabled = (bool)$options[WPPRS_DEFAULT_ENABLED_NAME];

  if ($isenabled) { // check for parameters, then settings, then defaults
    if ($uid === WPPRS_DEFAULT_UID) { // no user id passed to function, try settings page
      $uid = $options[WPPRS_DEFAULT_UID_NAME];
      if (!$uid) { // no userid on settings page either
        $isenabled = false;
      }
    }
    if ($nofollow === WPPRS_DEFAULT_NOFOLLOW) {
      $nofollow = $options[WPPRS_DEFAULT_NOFOLLOW_NAME];
	if (!$nofollow) {
        $nofollow = WPPRS_DEFAULT_NOFOLLOW;
      }
    }
    if ($rounded === WPPRS_DEFAULT_ROUNDED) {
      $rounded = $options[WPPRS_DEFAULT_ROUNDED_NAME];
	if (!$rounded) {
        $rounded = WPPRS_DEFAULT_ROUNDED;
      }
    }
    // is cache time numeric? also, convert to positive integer
    if (!is_numeric(absint(intval($cachetime)))) {
      $cachetime = WPPRS_DEFAULT_CACHETIME;
    } else { // it's numeric
      if ($cachetime === WPPRS_DEFAULT_CACHETIME) {
        $cachetime = $options[WPPRS_DEFAULT_CACHETIME_NAME];
        if (!$cachetime) {
          $cachetime = WPPRS_DEFAULT_CACHETIME;
        }
      }
    }
    // cache time should not be less than WPPRS_MIN_CACHE_TIME seconds, to avoid overtaxing wp.org
    $cachetime = max(WPPRS_MIN_CACHE_TIME, $cachetime);

    if ($showstars === WPPRS_DEFAULT_SHOW_STARS) {
      $showstars = $options[WPPRS_DEFAULT_SHOW_STARS_NAME];
	if ($showstars === false) {
        $showstars = WPPRS_DEFAULT_SHOW_STARS;
      }
    }
  }
  // do something
  if ($isenabled) {
    wpprs_styles();

    $querypath = '//div[@class="info-group plugin-theme main-plugins"]//';
    $transient_name = 'wpprs_count_' . $uid;
    $response = get_transient($transient_name);

    if (!$response) { // regenerate and cache
      // get wordpress plugin stats page html
      $response = wp_remote_retrieve_body(wp_remote_get('http://profiles.wordpress.org/' . $uid . '/'));
      if (is_wp_error($response)) {
        exit();
      }
      // parse HTML response
      $dom = new DOMDocument();
      $dom->loadHTML($response);
      $xpath = new DOMXPath($dom);
      // put plugin download counts into array and calculate total number of downloads
      $pContent = $xpath->query($querypath . 'p');
	for ($i = 0; $i < $pContent->length; $i++) {
	  if ($pContent->item($i)->getAttribute('class') === 'downloads') {
	    $plugincount++;
	  }
	}
      $indivcounts = array();
      for ($i = 0; $i < $plugincount; $i++) {
	  $strippedcount = str_replace(" downloads", "", $pContent->item($i)->nodeValue);
        array_push($indivcounts, $strippedcount);
        $sum = $sum + (int)str_replace(",", "", $strippedcount);
      }
      // format total downloads with thousands separator
      $sum = number_format($sum);
	if ($sum < 1) { // why are you using this plugin :)
	  exit();
	}
      // put plugin URLs into array
      $indivurls = array();
	$aContent = $xpath->query($querypath . 'a');
      for ($i = 0; $i < $plugincount; $i++) {
        array_push($indivurls, $aContent->item($i)->getAttribute('href'));
      }
      // put plugin names into array
      $h3Content = $xpath->query($querypath . 'h3');
      $indivnames = array();
      for ($i = 0; $i < $plugincount; $i++) {
        array_push($indivnames, $h3Content->item($i)->nodeValue);
      }
      // do we want plugin ratings?
      if ($showstars) {
        // visit each plugin URL and get star count
	  $starcounts = array();
	  $querypath = '//div[@class="star-holder"]//';
	  for ($i = 0; $i < $plugincount; $i++) {
	    $response = wp_remote_retrieve_body(wp_remote_get($indivurls[$i]));
	    if (is_wp_error($response)) {
	      array_push($starcounts, 'width: 0px');
	    } else {
	      // parse HTML response
		$dom = new DOMDocument();
		$dom->loadHTML($response);
		$xpath = new DOMXPath($dom);
		$starholder = $xpath->query($querypath . 'div');
		array_push($starcounts, $starholder->item(0)->getAttribute('style'));
	    }
	  }
      }
      // start formatting output
      $output = '<div class="wpprs">';
      $output .= '<div class="wpprs-top' . ($rounded ? ' wpprs-rounded-corners ' : '') . '">';
      $output .= '<h2>' . $sum . '</h2>';
      $output .= 'The number of times my <span class="wpprs-plugincount">' . $plugincount . '</span> WordPress plugins have been downloaded according to the official <a' . ($nofollow ? ' rel="nofollow" ' : ' ') . 'href="http://wordpress.org/extend/plugins/">WordPress Plugins Repository</a>.';
      $output .= '</div> <!-- end wpprs-top -->';
      $output .= '<div class="wpprs-body">';
	$output .= '<h3 class="wp-logo">My WordPress Plugins</h3>';
      $output .= '<table class="wpprs-table">';
      $output .= '<thead>';
      $output .= '<tr><th class="wpprs-headindex">#</th><th class="wpprs-headname">Plugin Name</th><th class="wpprs-headcount">Download Count</th>';
      if ($showstars) {
        $output .= '<th class="wpprs-headrating">Rating</th>';
      }
      $output .= '</tr></thead>';
      $output .= '<tbody>';
      // loop through arrays and print URL, name and count for each plugin
      for ($i = 0; $i < $plugincount; $i++) {
        $output .= '<tr' . ($i % 2 != 0 ? ' class="wpprs-evenrow" ' : ' class="wpprs-oddrow" ') . '>';
	  $output .= '<td class="wpprs-index">' . ($i + 1) . '</td>';
        // plugin URL and name
        $pluginurl = '<a' . ($nofollow ? ' rel="nofollow" ' : ' ') . 'href="' . $indivurls[$i] . '">' . $indivnames[$i] . '</a>';
        $output .= '<td class="wpprs-name">' . $pluginurl . '</td>';
        // download count for that plugin
	  $output .= '<td class="wpprs-count">' . $indivcounts[$i] . '</td>';
	  // star rating
        if ($showstars) {
	    $output .= '<td class="wpprs-rating"><div class="star-holder"><div class="star-rating" style="' . $starcounts[$i] . '"></div></div></td>';
        }
	  // end row
	  $output .= '</tr>';
      }
      // finish output
      $output .= '</tbody>';
      $output .= '</table>';
      $output .= '</div> <!-- end wpprs body -->';
      $output .= '</div> <!-- end wpprs -->';
      // cache output
      set_transient($transient_name, $output, $cachetime);
    } else { // cache exists
      $output = $response;
    }
  } else { // plugin disabled
    $output = '<!-- ' . WPPRS_PLUGIN_NAME . ': plugin is disabled. Check Settings page. -->';
  }
  if ($show) {
    echo $output;
  } else {
    return $output;
  }
}

// show admin messages to plugin user
add_action('admin_notices', 'wpprs_showAdminMessages');
function wpprs_showAdminMessages() {
  // http://wptheming.com/2011/08/admin-notices-in-wordpress/
  global $pagenow;
  if (current_user_can('manage_options')) { // user has privilege
    if ($pagenow == 'options-general.php') {
      if ($_GET['page'] == WPPRS_SLUG) { // we are on settings page
        $options = get_option(WPPRS_OPTION); // don't use encapsulated function here
        if ($options) {
	    $isenabled = (bool)$options[WPPRS_DEFAULT_ENABLED_NAME];
	    if (!$isenabled) {
	      echo '<div class="updated">' . WPPRS_PLUGIN_NAME . ' ' . __('is currently disabled.', WPPRS_LOCAL) . '</div>';
	    }
	    if (!$options[WPPRS_DEFAULT_UID_NAME]) {
            echo '<div class="error">' . __('No userid entered. If you do not set userid here, you must pass it to the plugin via shortcode or function call.', WPPRS_LOCAL) . '</div>';
          }
	  }
	} 
    } // end page check
  } // end privilege check
}
// http://bavotasan.com/2009/a-settings-link-for-your-wordpress-plugins/
// Add settings link on plugin page
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'wpprs_plugin_settings_link' );
function wpprs_plugin_settings_link($links) { 
  $settings_link = '<a href="options-general.php?page=' . WPPRS_SLUG . '">' . __('Settings', WPPRS_LOCAL) . '</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
function wpprs_styles() {
  wp_enqueue_style('wpprs_style');
}
function register_wpprs_style() {
  wp_register_style( 'wpprs_style', 
    plugins_url(plugin_basename(dirname(__FILE__)) . '/css/wp_plugin_repo_stats.css'), 
    array(), 
    WPPRS_VERSION, 
    'all' );
}
function wpprs_getpluginoptions() {
  return get_option(WPPRS_OPTION, array(WPPRS_DEFAULT_ENABLED_NAME => WPPRS_DEFAULT_ENABLED, WPPRS_DEFAULT_NOFOLLOW_NAME => WPPRS_DEFAULT_NOFOLLOW, WPPRS_DEFAULT_SHOW_STARS_NAME => WPPRS_DEFAULT_SHOW_STARS, WPPRS_DEFAULT_CACHETIME_NAME => WPPRS_DEFAULT_CACHETIME, WPPRS_DEFAULT_UID_NAME => WPPRS_DEFAULT_UID, WPPRS_DEFAULT_ROUNDED_NAME => WPPRS_DEFAULT_ROUNDED));
}
?>