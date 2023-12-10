<?php
/** RedirectController Class
 *
 * @package disable-wp-frontend
 */

namespace DisableWpFrontend;

// If this file is accessed directly, abort.
defined( 'ABSPATH' ) || exit;

/**
 * Class RedirectController handling the redirecting of front end requests.
 */
class RedirectController {
	/**
	 * Array of paths that are allowed to be accessed besides wp-admin and wp-login.
	 *
	 * @var array
	 */
	private array $path_whitelist = array(
		'/wp-content/uploads/',
		'/favicon.ico',
		'/rss',
		'/wp-cron.php',
	);

	/**
	 * Getter for the `$path_whitelist` attribute defining the paths that are allowed to be accessed besides wp-admin and wp-login.
	 */
	public function get_path_whitelist(): array {
		return $this->path_whitelist;
	}

	/**
	 * Setter for the `$path_whitelist` attribute defining the paths that are allowed to be accessed besides wp-admin and wp-login.
	 */
	public function set_path_whitelist( array $path_whitelist ): void {
		$this->path_whitelist = $path_whitelist;
	}

	public function redirect(): void {
		// Check if request is not wp-admin, wp-login.
		if ( ! is_admin() && ! is_login() ) {
			// Get path whitelist.
			$path_whitelist = $this->get_path_whitelist();

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
