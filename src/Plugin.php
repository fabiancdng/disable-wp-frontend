<?php
/** Plugin Class
 *
 * @package disable-wp-frontend
 */

namespace DisableWpFrontend;

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;
use YahnisElsts\PluginUpdateChecker\v5p3\Vcs\Api;
use YahnisElsts\PluginUpdateChecker\v5p3\Vcs\GitHubApi;

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
		// Add settings link on plugin page.
		add_filter( 'plugin_action_links_' . plugin_basename( self::get_plugin_file() ), function ( $links ) {
			$settings_link = '<a href="options-general.php?page=disable-wp-frontend">' . __( 'Settings', 'disable-wp-frontend' ) . '</a>';
			$links[]       = $settings_link;

			return $links;
		} );

		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	/**
	 * Initialize the plugin by setting up all hook and filter calls of the plugin.
	 */
	public function init(): void {
		$this->init_plugin_update_checker();
		$this->init_plugin_settings_page();
		$this->init_redirect_controller();
	}

	/**
	 * Initialize the plugin update checker.
	 */
	private function init_plugin_update_checker(): void {
		// Initialize the plugin update checker.
		$plugin_update_checker = PucFactory::buildUpdateChecker(
			'https://github.com/fabiancdng/disable-wp-frontend/',
			self::get_plugin_file(),
			'disable-wp-frontend'
		);

		/**
		 * @var GitHubApi $puc_github_api
		 */
		$puc_github_api = $plugin_update_checker->getVcsApi();

		// Enable release assets for the plugin.
		$puc_github_api->enableReleaseAssets( '/.*disable-wp-frontend\.zip.*/', Api::REQUIRE_RELEASE_ASSETS );
	}

	/**
	 * Initialize the plugin settings page in the WordPress dashboard.
	 */
	private function init_plugin_settings_page(): void {
		// Initialize the plugin settings and register the settings page (if on the WP dashboard).
		$plugin_settings = new SettingsPage();
		$plugin_settings->register_settings_page();
	}

	/**
	 * Initialize the RedirectController in accordance with the plugin settings.
	 */
	private function init_redirect_controller(): void {
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

	/**
	 * Get the URL to the base of the plugin: https://{domain}/wp-content/plugins/{plugin-slug}/
	 *
	 * @return string URL of plugin base.
	 */
	public static function get_plugin_base_url(): string {
		return plugin_dir_url( __DIR__ );
	}

	/**
	 * Get the path to the base of the plugin: /{base_to_WordPress}/wp-content/plugins/{plugin-slug}/
	 *
	 * @return string URL of plugin base.
	 */
	public static function get_plugin_base_dir(): string {
		return plugin_dir_path( __DIR__ );
	}

	/**
	 * Get the path to the plugin file: /{base_to_WordPress}/wp-content/plugins/{plugin-slug}/disable-wp-frontend.php
	 *
	 * @return string Path to plugin file.
	 */
	public static function get_plugin_file(): string {
		return self::get_plugin_base_dir() . '/disable-wp-frontend.php';
	}
}
