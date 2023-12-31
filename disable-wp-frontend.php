<?php
/*
Plugin Name: Disable WP Frontend
Plugin URI: https://github.com/fabiancdng/disable-wp-frontend
Description: Disables the WordPress front end (public-facing part of the website).
Author: Fabian Reinders
Author URI: https://fabiancdng.com
Version: 2.0.2
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Composer autoloader.
include_once __DIR__ . '/vendor/autoload.php';

// Initialize and run the plugin.
( new DisableWpFrontend\Plugin )->run();