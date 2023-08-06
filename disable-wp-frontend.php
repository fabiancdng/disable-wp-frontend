<?php
/*
Plugin Name: Disable WP Frontend
Plugin URI: https://github.com/fabiancdng/disable-wp-frontend
Description: Disables the WordPress front end (public-facing part of the website).
Author: Fabian Reinders
Author URI: https://fabiancdng.com
Version: 1.0.2
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
			/**
			 * Array of paths that are allowed to be accessed besides wp-admin and wp-login.
			 */
			$path_whitelist = array();

			// Make sure, the request is not a media file in wp-content (like an image).
			$path_whitelist[] = '/wp-content/uploads/';
			$path_whitelist[] = '/favicon.ico';

			// Make sure, the request is not a call to wp-cron.php.
			$path_whitelist[] = '/wp-cron.php';

			// Make sure, path is not in whitelist.
			foreach ( $path_whitelist as $path ) {
				if ( str_contains( $_SERVER['REQUEST_URI'], $path ) ) {
					return;
				}
			}

			// Redirect to wp-admin.
			wp_redirect( site_url( 'wp-admin' ) );
			exit;
		}
	}
}

// Hook up to the template_redirect action (so for instance REST API calls still work).
add_action( 'template_redirect', 'disable_wp_frontend' );