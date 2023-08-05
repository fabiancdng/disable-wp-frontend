<?php
/*
Plugin Name: Disable WP Frontend
Plugin URI: https://github.com/fabiancdng/disable-wp-frontend
Description: Disables the WordPress front end (public-facing part of the website).
Author: Fabian Reinders
Author URI: https://fabiancdng.com
Version: 1.1
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'disable_wp_frontend' ) ) {
	/**
	 * Catch all front end requests and redirect them to wp-admin/wp-login.
	 */
	function disable_wp_frontend(): void {
		// Check if request is not wp-admin, wp-login.
		if ( ! is_admin() && ! is_login() ) {
			// Make sure, the request is not a media file in wp-content (like an image).
			// Allow all requests to wp-content/uploads/*.
			if ( str_contains( $_SERVER['REQUEST_URI'], '/wp-content/uploads/' ) ) {
				return;
			}

			// Make sure, the request is not a call to wp-cron.php.
			if ( str_contains( $_SERVER['REQUEST_URI'], '/wp-cron.php' ) ) {
				return;
			}

			// Redirect to wp-admin.
			wp_redirect( site_url( 'wp-admin' ) );
			exit;
		}
	}
}

// Hook up to the template_redirect action (so for instance REST API calls still work).
add_action( 'template_redirect', 'disable_wp_frontend' );