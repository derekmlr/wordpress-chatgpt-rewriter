<?php
/*
  Plugin Name: ChatGPT Rewriter
  Plugin URI: https://gaminghybrid.com
  Description: Rewrites post content using ChatGPT before publishing.
  Version: 1.0
  Author: Derek
  Author URI: https://gaminghybrid.com
  License: GPLv2 or later
  Text Domain: chatgpt-rewriterr
*/

// Check if the necessary functions exist.
if (!function_exists('add_action')) {
  echo 'This plugin cannot be called directly.';
  exit;
}

// Include required plugin files.
require_once plugin_dir_path(__FILE__) . 'include/settings.php';
require_once plugin_dir_path(__FILE__) . 'include/meta-box.php';
require_once plugin_dir_path(__FILE__) . 'include/content-processing.php';

?>