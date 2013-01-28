<?php
/*
Plugin Name: WordPress Plugin Repo Stats
Plugin URI: http://www.jimmyscode.com/
Description: Plugin developers -- display the names and download counts for your WordPress plugins in a CSS-stylable table.
Version: 0.0.1
Author: Jimmy Pena
Author URI: http://www.jimmyscode.com/
Contributors: jp2112
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=NRHAAC7Q9Q2X6
Tags: plugin, count, download, table
Requires at least: 3.5
Tested up to: 3.5
License: GPL3
License URI: http://www.gnu.org/licenses/gpl.html
*/
/*  Copyright 2013  Jimmy Pena

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, visit http://www.gnu.org/licenses/gpl.html
*/

add_shortcode('plugin-repo-stats', 'wpprs');
function wpprs($atts) {
  // get parameters
  extract( shortcode_atts( array(
			'uid' => '',
      'nofollow' => 'true',
			'rounded' => 'true',
			'cachetime' => '43200'
      ), $atts ) );
	// variables used throughout
	$querypath = '//div[@class="info-group plugin-theme main-plugins"]//';
	$transient_name = 'wpprs_count';
  // get cached copy
	if (false === ($response = get_transient($transient_name))) { // cache doesn't exist
    // get wordpress plugin stats page html
    $response = wp_remote_retrieve_body(wp_remote_get('http://profiles.wordpress.org/' . $uid . '/'));
    if (is_wp_error($response)) {
      return 'error';
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
		  return '';
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

    // start formatting output
    $output = '<div class="wpprs">';
    $output .= '<div class="wpprs-top' . ($rounded ? ' wpprs-rounded-corners ' : '') . '">';
    $output .= '<h2>' . $sum . '</h2>';
    $output .= 'The number of times my <span class="wpprs-plugincount">' . $plugincount . '</span> WordPress plugins have been downloaded according to the official <a href="http://wordpress.org/extend/plugins/">WordPress Plugins Repository</a>.';
    $output .= '</div> <!-- end wpprs-top -->';
    $output .= '<div class="wpprs-body">';
		$output .= '<h3>My WordPress Plugins</h3>';
    $output .= '<table class="wpprs-table">';
    $output .= '<thead>';
    $output .= '<tr><th class="wpprs-headindex">#</th><th class="wpprs-headname">Plugin Name</th><th class="wpprs-headcount">Download Count</th></tr>';
    $output .= '</thead>';
    $output .= '<tbody>';
    // loop through arrays and print URL, name and count for each plugin
    for ($i = 0; $i < $plugincount; $i++) {
      // order
      $output .= '<tr' . ($i % 2 != 0 ? ' class="wpprs-evenrow" ' : ' class="wpprs-oddrow" ') . '>';
			$output .= '<td class="wpprs-index">' . ($i + 1) . '</td>';
      // plugin URL and name
      $output .= '<td class="wpprs-name"><a' . ($nofollow ? ' rel="nofollow" ' : ' ') . 'href="' . $indivurls[$i] . '">' . $indivnames[$i] . '</a></td>';
      // download count for that plugin
			$output .= '<td class="wpprs-count">' . $indivcounts[$i] . '</td>';
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
    $output = get_transient($transient_name);
  }
	return $output;
}
?>