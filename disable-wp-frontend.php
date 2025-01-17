<?php
/*
Plugin Name: Disable WP Frontend
Plugin URI: https://github.com/fabiancdng/disable-wp-frontend
Description: Disables the WordPress front end (public-facing part of the website).
Author: Fabian Reinders
Author URI: https://github.com/fabiancdng
Version: 2.0.3
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Composer autoloader.
include_once __DIR__ . '/vendor/autoload.php';

// Initialize and run the plugin.
( new DisableWpFrontend\Plugin )->run();