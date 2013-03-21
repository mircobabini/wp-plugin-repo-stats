<?php
/*
Plugin Name: WordPress Plugin Repo Stats
Plugin URI: http://www.jimmyscode.com/wordpress/wp-plugin-repo-stats/
Description: Plugin developers -- display the names and download counts for your WordPress plugins in a CSS-stylable table. Includes plugin ratings.
Version: 0.0.6
Author: Jimmy Pe&ntilde;a
Author URI: http://www.jimmyscode.com/
License: GPLv2 or later
*/
// plugin constants
define('WPPRS_VERSION', '0.0.6');
define('WPPRS_PLUGIN_NAME', 'WordPress Plugin Repo Stats');
define('WPPRS_SLUG', 'wp-plugin-repo-stats');
define('WPPRS_LOCAL', 'wpprs');
define('WPPRS_OPTION', 'wpprs');
/* default values */
define('WPPRS_DEFAULT_ENABLED', true);
define('WPPRS_DEFAULT_NOFOLLOW', true);
define('WPPRS_DEFAULT_SHOW_STARS', true);
define('WPPRS_DEFAULT_CACHETIME', 3600);
define('WPPRS_MIN_CACHE_TIME', 300);
define('WPPRS_DEFAULT_UID', '');
define('WPPRS_DEFAULT_ROUNDED', true);
define('WPPRS_DEFAULT_SORT', '');
define('WPPRS_DEFAULT_SHOW', false);
define('WPPRS_DEFAULT_NEWWINDOW', false);
define('WPPRS_AVAILABLE_SORT', 'ascending,descending');
/* option array member names */
define('WPPRS_DEFAULT_ENABLED_NAME', 'enabled');
define('WPPRS_DEFAULT_NOFOLLOW_NAME', 'nofollow');
define('WPPRS_DEFAULT_SHOW_STARS_NAME', 'showstars');
define('WPPRS_DEFAULT_CACHETIME_NAME', 'cachetime');
define('WPPRS_DEFAULT_UID_NAME', 'uid');
define('WPPRS_DEFAULT_ROUNDED_NAME', 'rounded');
define('WPPRS_DEFAULT_SORT_NAME', 'sortorder');
define('WPPRS_DEFAULT_SHOW_NAME', 'show');
define('WPPRS_DEFAULT_NEWWINDOW_NAME', 'opennewwindow');

// add custom quicktag
add_action('admin_print_footer_scripts', 'add_wpprs_quicktag', 100);
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
	register_wpprs_admin_style();
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
      <table class="form-table" id="theme-options-wrap">
        <tr valign="top"><th scope="row"><strong><label title="<?php _e('Is plugin enabled? Uncheck this to turn it off temporarily.', WPPRS_LOCAL); ?>" for="wpprs[<?php echo WPPRS_DEFAULT_ENABLED_NAME; ?>]"><?php _e('Plugin enabled?', WPPRS_LOCAL); ?></label></strong></th>
					<td><input type="checkbox" id="wpprs[<?php echo WPPRS_DEFAULT_ENABLED_NAME; ?>]" name="wpprs[<?php echo WPPRS_DEFAULT_ENABLED_NAME; ?>]" value="1" <?php checked('1', $options[WPPRS_DEFAULT_ENABLED_NAME]); ?> /></td>
        </tr>
				<tr valign="top"><td colspan="2"><?php _e('Is plugin enabled? Uncheck this to turn it off temporarily.', WPPRS_LOCAL); ?></td></tr>
				<tr valign="top"><th scope="row"><strong><label title="<?php _e('Enter your wordpress.org userid.', WPPRS_LOCAL); ?>" for="wpprs[<?php echo WPPRS_DEFAULT_UID_NAME; ?>]"><?php _e('WordPress.org Userid', WPPRS_LOCAL); ?></label></strong></th>
					<td><input type="text" id="wpprs[<?php echo WPPRS_DEFAULT_UID_NAME; ?>]" name="wpprs[<?php echo WPPRS_DEFAULT_UID_NAME; ?>]" value="<?php echo $options[WPPRS_DEFAULT_UID_NAME]; ?>" /></td>
        </tr>
				<tr valign="top"><td colspan="2"><?php _e('Enter your wordpress.org userid.', WPPRS_LOCAL); ?></td></tr>
        <tr valign="top"><th scope="row"><strong><label title="<?php _e('Check this box to add rel=nofollow to WP plugin repo links.', WPPRS_LOCAL); ?>" for="wpprs[<?php echo WPPRS_DEFAULT_NOFOLLOW_NAME; ?>]"><?php _e('Nofollow plugin link(s)?', WPPRS_LOCAL); ?></label></strong></th>
					<td><input type="checkbox" id="wpprs[<?php echo WPPRS_DEFAULT_NOFOLLOW_NAME; ?>]" name="wpprs[<?php echo WPPRS_DEFAULT_NOFOLLOW_NAME; ?>]" value="1" <?php checked('1', $options[WPPRS_DEFAULT_NOFOLLOW_NAME]); ?> /></td>
        </tr>
				<tr valign="top"><td colspan="2"><?php _e('Check this box to add rel="nofollow" to WP plugin repo links.', WPPRS_LOCAL); ?></td></tr>
				<tr valign="top"><th scope="row"><strong><label title="<?php _e('Check this box to use rounded corner CSS on the table header.', WPPRS_LOCAL); ?>" for="wpprs[<?php echo WPPRS_DEFAULT_ROUNDED_NAME; ?>]"><?php _e('Rounded corner CSS?', WPPRS_LOCAL); ?></label></strong></th>
					<td><input type="checkbox" id="wpprs[<?php echo WPPRS_DEFAULT_ROUNDED_NAME; ?>]" name="wpprs[<?php echo WPPRS_DEFAULT_ROUNDED_NAME; ?>]" value="1" <?php checked('1', $options[WPPRS_DEFAULT_ROUNDED_NAME]); ?> /></td>
        </tr>
				<tr valign="top"><td colspan="2"><?php _e('Check this box to use rounded corner CSS on the table header.', WPPRS_LOCAL); ?></td></tr>
        <tr valign="top"><th scope="row"><strong><label title="<?php _e('Check this box to show plugin star ratings.', WPPRS_LOCAL); ?>" for="wpprs[<?php echo WPPRS_DEFAULT_SHOW_STARS_NAME; ?>]"><?php _e('Show plugin ratings?', WPPRS_LOCAL); ?></label></strong></th>
					<td><input type="checkbox" id="wpprs[<?php echo WPPRS_DEFAULT_SHOW_STARS_NAME; ?>]" name="wpprs[<?php echo WPPRS_DEFAULT_SHOW_STARS_NAME; ?>]" value="1" <?php checked('1', $options[WPPRS_DEFAULT_SHOW_STARS_NAME]); ?> /></td>
        </tr>
				<tr valign="top"><td colspan="2"><?php _e('Check this box to show plugin star ratings.', WPPRS_LOCAL); ?></td></tr>
				<tr valign="top"><th scope="row"><strong><label title="<?php _e('Enter time in seconds between cache refreshes. Default is ' . WPPRS_DEFAULT_CACHETIME . ' seconds, minimum is ' . WPPRS_MIN_CACHE_TIME . ' seconds.', WPPRS_LOCAL); ?>" for="wpprs[<?php echo WPPRS_DEFAULT_CACHETIME_NAME; ?>]"><?php _e('Cache time (seconds)', WPPRS_LOCAL); ?></label></strong></th>
					<td><input type="number" min="<?php echo WPPRS_MIN_CACHE_TIME; ?>" id="wpprs[<?php echo WPPRS_DEFAULT_CACHETIME_NAME; ?>]" name="wpprs[<?php echo WPPRS_DEFAULT_CACHETIME_NAME; ?>]" value="<?php echo $options[WPPRS_DEFAULT_CACHETIME_NAME]; ?>" /></td>
				</tr>
				<tr valign="top"><td colspan="2"><?php _e('Enter time in seconds between cache refreshes. Default is <strong>' . WPPRS_DEFAULT_CACHETIME . '</strong> seconds, minimum is <strong>' . WPPRS_MIN_CACHE_TIME . '</strong> seconds.', WPPRS_LOCAL); ?></td></tr>

				<tr valign="top"><th scope="row"><strong><label title="<?php _e('Select the sort order. Default is ascending (by plugin name).', WPPRS_LOCAL); ?>" for="wpprs[<?php echo WPPRS_DEFAULT_SORT_NAME; ?>]"><?php _e('Default sort order', WPPRS_LOCAL); ?></label></strong></th>
					<td><select id="wpprs[<?php echo WPPRS_DEFAULT_SORT_NAME; ?>]" name="wpprs[<?php echo WPPRS_DEFAULT_SORT_NAME; ?>]">
					<?php $orders = explode(",", WPPRS_AVAILABLE_SORT);
          foreach($orders as $order) {
						echo '<option value="' . $order . '" ' . selected($order, $options[WPPRS_DEFAULT_SORT_NAME]) . '>' . $order . '</option>';
          } ?>
					</select></td>
				</tr>
				<tr valign="top"><td colspan="2"><?php _e('Select the sort order. Default is ascending (by plugin name).', WPPRS_LOCAL); ?></td></tr>
        <tr valign="top"><th scope="row"><strong><label title="<?php _e('Check this box to open links in a new window. target="_blank" will be added to all links', WPPRS_LOCAL); ?>" for="wpprs[<?php echo WPPRS_DEFAULT_NEWWINDOW_NAME; ?>]"><?php _e('Open links in new window?', WPPRS_LOCAL); ?></label></strong></th>
					<td><input type="checkbox" id="wpprs[<?php echo WPPRS_DEFAULT_NEWWINDOW_NAME; ?>]" name="wpprs[<?php echo WPPRS_DEFAULT_NEWWINDOW_NAME; ?>]" value="1" <?php checked('1', $options[WPPRS_DEFAULT_NEWWINDOW_NAME]); ?> /></td>
        </tr>
				<tr valign="top"><td colspan="2"><?php _e('Check this box to open links in a new window. target="_blank" will be added to all links', WPPRS_LOCAL); ?></td></tr>
      </table>
      <?php submit_button(); ?>
    </form>
    <h2>Support</h2>
    <div class="support">
    If you like this plugin, please <a href="http://wordpress.org/extend/plugins/<?php echo WPPRS_SLUG; ?>/">rate it on WordPress.org</a> and click the "Works" button so others know it will work for your WordPress version. For support please visit the <a href="http://wordpress.org/support/plugin/<?php echo WPPRS_SLUG; ?>">forums</a>. <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7EX9NB9TLFHVW"><img src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" alt="Donate with PayPal" title="Donate with PayPal" width="92" height="26" /></a>
    </div>
  </div>
  <?php  
}

// shortcode for plugin output
add_shortcode('plugin-repo-stats', 'wpprs');
function wpprs($atts) {
  extract( shortcode_atts( array(
  WPPRS_DEFAULT_UID_NAME => WPPRS_DEFAULT_UID, 
  WPPRS_DEFAULT_NOFOLLOW_NAME => WPPRS_DEFAULT_NOFOLLOW, 
  WPPRS_DEFAULT_ROUNDED_NAME => WPPRS_DEFAULT_ROUNDED, 
  WPPRS_DEFAULT_CACHETIME_NAME => WPPRS_DEFAULT_CACHETIME, 
  WPPRS_DEFAULT_SHOW_STARS_NAME => WPPRS_DEFAULT_SHOW_STARS, 
  WPPRS_DEFAULT_SORT_NAME => WPPRS_DEFAULT_SORT, 
  WPPRS_DEFAULT_NEWWINDOW_NAME => WPPRS_DEFAULT_NEWWINDOW, 
  WPPRS_DEFAULT_SHOW_NAME => WPPRS_DEFAULT_SHOW
  ), $atts ) );

  $options = wpprs_getpluginoptions();
  $enabled = $options[WPPRS_DEFAULT_ENABLED_NAME];

  if ($enabled) { // check for parameters, then settings, then defaults
    if ($uid === WPPRS_DEFAULT_UID) { // no user id passed to function, try settings page
      $uid = $options[WPPRS_DEFAULT_UID_NAME];
      if (!$uid) { // no userid on settings page either
        $enabled = false;
      }
    }
    if ($nofollow === WPPRS_DEFAULT_NOFOLLOW) {
      $nofollow = $options[WPPRS_DEFAULT_NOFOLLOW_NAME];
	if ($nofollow === false) {
        $nofollow = WPPRS_DEFAULT_NOFOLLOW;
      }
    }
    if ($rounded === WPPRS_DEFAULT_ROUNDED) {
      $rounded = $options[WPPRS_DEFAULT_ROUNDED_NAME];
	if ($rounded === false) {
        $rounded = WPPRS_DEFAULT_ROUNDED;
      }
    }
    // is cache time numeric? also, convert to positive integer
    if (!is_numeric(absint(intval($cachetime)))) {
      $cachetime = WPPRS_DEFAULT_CACHETIME;
    } else { // it's numeric
      if ($cachetime === WPPRS_DEFAULT_CACHETIME) {
        $cachetime = $options[WPPRS_DEFAULT_CACHETIME_NAME];
        if ($cachetime === false) {
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
    if ($sortorder == WPPRS_DEFAULT_SORT) {
      $sortorder = $options[WPPRS_DEFAULT_SORT_NAME];
      if ($sortorder === false) {
        $sortorder = WPPRS_DEFAULT_SORT;
      }
    }
    if ($opennewwindow === WPPRS_DEFAULT_NEWWINDOW) {
      $opennewwindow = $options[WPPRS_DEFAULT_NEWWINDOW_NAME];
      if ($opennewwindow === false) {
        $opennewwindow = WPPRS_DEFAULT_NEWWINDOW;
      }
    }
  }
  // do something
  if ($enabled) {
    $orders = explode(",", WPPRS_AVAILABLE_SORT);
    if (!in_array($sortorder, $orders)) {
      $sortorder = $options[WPPRS_DEFAULT_SORT_NAME];
      if ($sortorder === false) {
        $sortorder = WPPRS_DEFAULT_SORT;
      }
    }
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
      $output .= '<div class="wpprs-top' . ($rounded ? ' wpprs-rounded-corners' : '') . '">';
      $output .= '<h2>' . $sum . '</h2>';
      $output .= 'The number of times my <span class="wpprs-plugincount">' . $plugincount . '</span> WordPress plugins have been downloaded according to the official <a' . ($opennewwindow ? ' target="_blank" ' : ' ') . ($nofollow ? ' rel="nofollow" ' : ' ') . 'href="http://wordpress.org/extend/plugins/">WordPress Plugins Repository</a>.';
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
			// combine records into a single array for sorting
			$outputarray = array();
			if ($showstars) { // include star counts
        for ($i = 0; $i < $plugincount; $i++) {
					$outputarray[$i] = array($indivnames[$i], $indivurls[$i], $indivcounts[$i], $starcounts[$i]);
				}
			} else {
        for ($i = 0; $i < $plugincount; $i++) {
					$outputarray[$i] = array($indivnames[$i], $indivurls[$i], $indivcounts[$i]);
				}
			}
			// sort array ascending?
      if ($sortorder === 'ascending') {
				array_multisort($outputarray);
      } else {
        array_multisort($outputarray, SORT_DESC);
      }
      // loop through arrays and print URL, name and count for each plugin
      for ($i = 0; $i < $plugincount; $i++) {
        $output .= '<tr' . ($i % 2 != 0 ? ' class="wpprs-evenrow" ' : ' class="wpprs-oddrow" ') . '>';
				$output .= '<td class="wpprs-index">' . ($i + 1) . '</td>';
        // plugin URL and name
        $pluginurl = '<a' . ($opennewwindow ? ' target="_blank" ' : ' ') . ($nofollow ? ' rel="nofollow" ' : ' ') . 'href="' . $outputarray[$i][1] . '">' . $outputarray[$i][0] . '</a>';
        $output .= '<td class="wpprs-name">' . $pluginurl . '</td>';
        // download count for that plugin
				$output .= '<td class="wpprs-count">' . $outputarray[$i][2] . '</td>';
				// star rating
        if ($showstars) {
					// get stars width from 'width: ##px'
					$starswidth = explode(":", $outputarray[$i][3]);
					$starswidth = $starswidth[1];
					$starswidth = explode("px", $starswidth);
					$starswidth = $starswidth[0];
					if ($starswidth > 0) {
						$starswidth = round($starswidth / 18.4, 2);
					} else {
						$starswidth = 0;
					}
					$output .= '<td class="wpprs-rating"><div title="' . $starswidth . ' out of 5 stars' . ((bool)$starswidth ? '' : ' or rating not available') .'" class="star-holder"><div class="star-rating" style="' . $outputarray[$i][3] . '"></div></div></td>';
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
    echo esc_html($output);
  } else {
    return esc_html($output);
  }
}
// show admin messages to plugin user
add_action('admin_notices', 'wpprs_showAdminMessages');
function wpprs_showAdminMessages() {
  // http://wptheming.com/2011/08/admin-notices-in-wordpress/
  global $pagenow;
  if (current_user_can('manage_options')) { // user has privilege
    if ($pagenow == 'options-general.php') {
      if (sanitize_text_field($_GET['page']) == WPPRS_SLUG) { // we are on settings page
        $options = wpprs_getpluginoptions(); // get_option(WPPRS_OPTION); // don't use encapsulated function here
        if ($options) {
					$enabled = (bool)$options[WPPRS_DEFAULT_ENABLED_NAME];
					if ($enabled === false) {
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

// add admin CSS if we are on the plugin options page
add_action('admin_head', 'insert_wpprs_admin_css');
function insert_wpprs_admin_css() {
	global $pagenow;
	if (current_user_can('manage_options')) {
		if ($pagenow == 'options-general.php') {
			if (sanitize_text_field($_GET['page']) == WPPRS_SLUG) {
				wpprs_admin_styles();
			}
		}
	}
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

function wpprs_admin_styles() {
	wp_enqueue_style('wpprs_admin_style');
}
function register_wpprs_admin_style() {
	wp_register_style( 'wpprs_admin_style',
		plugins_url(plugin_basename(dirname(__FILE__)) . '/css/admin.css'),
		array(),
		WPPRS_VERSION,
		'all' );
}

function wpprs_getpluginoptions() {
  return get_option(WPPRS_OPTION, array(WPPRS_DEFAULT_ENABLED_NAME => WPPRS_DEFAULT_ENABLED, WPPRS_DEFAULT_NOFOLLOW_NAME => WPPRS_DEFAULT_NOFOLLOW, WPPRS_DEFAULT_SHOW_STARS_NAME => WPPRS_DEFAULT_SHOW_STARS, WPPRS_DEFAULT_CACHETIME_NAME => WPPRS_DEFAULT_CACHETIME, WPPRS_DEFAULT_UID_NAME => WPPRS_DEFAULT_UID, WPPRS_DEFAULT_ROUNDED_NAME => WPPRS_DEFAULT_ROUNDED, WPPRS_DEFAULT_SORT_NAME => WPPRS_DEFAULT_SORT, WPPRS_DEFAULT_NEWWINDOW_NAME => WPPRS_DEFAULT_NEWWINDOW));
}
?>