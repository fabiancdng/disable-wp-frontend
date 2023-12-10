<?php
/** Plugin Class
 *
 * @package disable-wp-frontend
 */

namespace DisableWpFrontend;

// If this file is accessed directly, abort.
defined( 'ABSPATH' ) || exit;

/**
 * Class Plugin as main plugin class.
 */
class Plugin {
	/**
	 * Run the plugin.
	 */
	public function run(): void {
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	/**
	 * Initialize the plugin by setting up all hook and filter calls of the plugin.
	 */
	public function init(): void {
		// Initialize the plugin settings and register the settings page (if on the WP dashboard).
		$plugin_settings = new SettingsPage();
		$plugin_settings->register_settings_page();

		// Only run the redirect logic if the 'disable_wp_frontend' option is set to true.
		$disable_wp_frontend_settings = get_option( 'disable_wp_frontend_settings' );
		if ( ! isset( $disable_wp_frontend_settings['general_settings']['disable_wp_frontend'] ) || true === $disable_wp_frontend_settings['general_settings']['disable_wp_frontend'] ) {
			// Instantiate the RedirectController handling the redirecting of front end requests.
			$redirect_controller = new RedirectController();

			// If 'disable_wp_frontend_path_whitelist' option is set, use it to set the path whitelist.
			$path_whitelist = get_option( 'disable_wp_frontend_settings' )['exception_settings']['path_whitelist'] ?? array(
				'/favicon.ico',
			);
			if ( false !== $path_whitelist && is_array( $path_whitelist ) ) {
				$redirect_controller->set_path_whitelist( $path_whitelist );
			}

			// Hook up to the template_redirect action (so for instance REST API calls still work).
			add_action( 'template_redirect', array( $redirect_controller, 'redirect' ) );
		}
	}
}
