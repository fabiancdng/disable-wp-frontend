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
	public function init(): void {
		// Run the plugin.
		add_action( 'init', array( $this, 'run' ) );
	}

	/**
	 * Catch all front end requests and redirect them to wp-admin/wp-login.
	 */
	public function run(): void {
		// Instantiate the RedirectController handling the redirecting of front end requests.
		$redirect_controller = new RedirectController();

		// Hook up to the template_redirect action (so for instance REST API calls still work).
		add_action( 'template_redirect', array( $redirect_controller, 'redirect' ) );
	}
}