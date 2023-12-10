<?php
/** SettingsPage Class
 *
 * @package disable-wp-frontend
 */


namespace DisableWpFrontend;

// If this file is accessed directly, abort.
defined( 'ABSPATH' ) || exit;

/**
 * Class SettingsPage for providing the page in the WordPress dashboard to manage the settings for the plugin.
 */
class SettingsPage {
	/**
	 * Calls the necessary hooks and filters to add the settings page for Disable WP Frontend.
	 */
	public function register_settings_page(): void {
		// Hook call to add the settings page.
		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
	}

	/**
	 * Registers the settings sections and fields.
	 */
	private function register_settings(): void {
		// Register the settings.
		register_setting(
			'disable_wp_frontend_settings',
			'disable_wp_frontend_settings',
			array( $this, 'sanitize_settings' )
		);

		// Register the settings sections.

		// General settings section.
		add_settings_section(
			'disable_wp_frontend_general_settings',
			'General settings',
			array( $this, 'render_general_settings_section' ),
			'disable-wp-frontend'
		);

		// Exception settings section.
		add_settings_section(
			'disable_wp_frontend_exception_settings',
			'Exception settings',
			array( $this, 'render_exception_settings_section' ),
			'disable-wp-frontend'
		);

		// Settings section to restore default settings.
		add_settings_section(
			'disable_wp_frontend_restore_default_settings',
			'Restore default settings',
			array( $this, 'render_restore_default_settings_section' ),
			'disable-wp-frontend'
		);

		// Register the settings fields.

		// 'Disable WP Frontend' settings field.
		add_settings_field(
			'disable_wp_frontend_general_settings_disable_wp_frontend',
			'Disable WordPress Frontend',
			array( $this, 'render_general_settings_disable_wp_frontend_field' ),
			'disable-wp-frontend',
			'disable_wp_frontend_general_settings'
		);

		// 'Path Whitelist' settings field.
		add_settings_field(
			'disable_wp_frontend_exception_settings_path_whitelist',
			'Path Whitelist',
			array( $this, 'render_exception_settings_path_whitelist_field' ),
			'disable-wp-frontend',
			'disable_wp_frontend_exception_settings'
		);

		// 'Restore default settings' settings field.
		add_settings_field(
			'disable_wp_frontend_restore_default_settings_restore_default_settings',
			'Restore default settings',
			array( $this, 'render_restore_default_settings_restore_default_settings_field' ),
			'disable-wp-frontend',
			'disable_wp_frontend_restore_default_settings'
		);
	}

	/**
	 * Adds the settings page to the WordPress dashboard under the 'Settings' menu.
	 */
	public function add_settings_page(): void {
		// Register the settings, settings sections and settings fields first.
		$this->register_settings();

		// Register the dashboard in the WordPress menu.
		add_options_page(
			'Disable WP Frontend',
			'Disable WP Frontend',
			'manage_options',
			'disable-wp-frontend',
			array( $this, 'render_settings_page' )
		);
	}

	/**
	 * Renders the settings page.
	 */
	public function render_settings_page(): void {
		?>
        <style>
            #disable-wp-frontend-settings form h2 {
                border-top: 1px solid #dcdc;
                padding-top: 25px;
            }
        </style>

        <div class="wrap" id="disable-wp-frontend-settings">
            <h1><?php esc_html_e( 'Disable WP Frontend Settings', 'disable-wp-frontend' ); ?></h1>
            <p><?php esc_html_e( 'Here, you can manage the settings for disabling the WordPress front end.', 'disable-wp-frontend' ); ?></p>

            <form action="options.php" method="post">
				<?php
				settings_fields( 'disable_wp_frontend_settings' );
				do_settings_sections( 'disable-wp-frontend' );
				submit_button();
				?>
            </form>
        </div>
		<?php
	}

	/**
	 * Renders the general settings section.
	 */
	public function render_general_settings_section(): void {
		// If the 'disable_wp_frontend' option is set to true, display a warning and recommend to disable the plugin.
		if ( isset( get_option( 'disable_wp_frontend_settings' )['general_settings']['disable_wp_frontend'] ) && false === get_option( 'disable_wp_frontend_settings' )['general_settings']['disable_wp_frontend'] ) {
			?>
            <p style="color: red;"><?php esc_html_e( 'Warning: The WordPress front end is currently enabled (see option below).', 'disable-wp-frontend' ); ?></p>
            <p style="color: red; margin-bottom: 25px;"><?php esc_html_e( 'If you want to keep it enabled permanently, we recommend you to disable the plugin.', 'disable-wp-frontend' ); ?></p>
			<?php
		}
		?>
        <p><?php esc_html_e( 'Here, you can manage the general settings for disabling the WP front end.', 'disable-wp-frontend' ); ?></p>
		<?php
	}

	/**
	 * Renders the exception settings section.
	 */
	public function render_exception_settings_section(): void {
		?>
        <p><?php esc_html_e( 'Here, you can manage the exception settings for disabling the WP front end.', 'disable-wp-frontend' ); ?></p>
		<?php
	}

	/**
	 * Renders the restore default settings section.
	 */
	public function render_restore_default_settings_section(): void {
		?>
        <p style="color: red; margin-bottom: 25px;"><?php esc_html_e( 'Warning: Checking the option below will delete all settings and restore default values!', 'disable-wp-frontend' ); ?></p>
        <p><?php esc_html_e( 'Here, you can restore the default settings for disabling the WP front end.', 'disable-wp-frontend' ); ?></p>
		<?php
	}

	/**
	 * Renders the 'Disable WP Frontend' field.
	 */
	public function render_general_settings_disable_wp_frontend_field(): void {
		$disable_wp_frontend = get_option( 'disable_wp_frontend_settings' )['general_settings']['disable_wp_frontend'] ?? true;
		?>
        <input type="checkbox" name="disable_wp_frontend_settings[general_settings][disable_wp_frontend]" id="disable_wp_frontend_general_settings_disable_wp_frontend"
               value="1" <?php checked( $disable_wp_frontend, true ); ?> />
        <label for="disable_wp_frontend_general_settings_disable_wp_frontend"><?php esc_html_e( 'Disable WordPress Frontend', 'disable-wp-frontend' ); ?></label>
        <p class="description"><?php esc_html_e( 'Check this box to disable the WordPress frontend and redirect all pages to wp-admin or wp-login (except for the paths whitelisted below).', 'disable-wp-frontend' ); ?></p>
        <p class="description"><?php esc_html_e( 'We only recommend unchecking this box for testing purposes and otherwise simply deactivating the entire plugin.', 'disable-wp-frontend' ); ?></p>
		<?php
	}

	/**
	 * Renders the 'Path Whitelist' field.
	 */
	public function render_exception_settings_path_whitelist_field(): void {
		$path_whitelist = get_option( 'disable_wp_frontend_settings' )['exception_settings']['path_whitelist'] ?? array(
			'/favicon.ico',
		);
		// Textarea field and paths separated by new line.
		?>
        <textarea name="disable_wp_frontend_settings[exception_settings][path_whitelist]" id="disable_wp_frontend_exception_settings_path_whitelist" cols="65"
                  rows="15"><?php echo esc_textarea( implode( "\n", $path_whitelist ) ); ?></textarea>

        <p class="description"><?php esc_html_e( 'Enter the paths that are allowed to be accessed besides some crucial internal WP pages.', 'disable-wp-frontend' ); ?></p>
        <p class="description"><?php esc_html_e( 'One path per line.', 'disable-wp-frontend' ); ?></p>
        <p class="description"><?php esc_html_e( 'Matching works by checking whether the path in the whitelist is contained in the request\'s URL.', 'disable-wp-frontend' ); ?></p>
        <p class="description"><?php esc_html_e( 'Hence, be careful with rules that are too general.', 'disable-wp-frontend' ); ?></p>
		<?php
	}

	/**
	 * Renders the 'Restore default settings' field.
	 */
	public function render_restore_default_settings_restore_default_settings_field(): void {
		// Red disclaimer that this will delete all settings.
		?>
        <input type="checkbox" name="disable_wp_frontend_settings[restore_default_settings][restore_default_settings]" id="disable_wp_frontend_restore_default_settings_restore_default_settings"
               value="1"/>
        <label for="disable_wp_frontend_restore_default_settings_restore_default_settings"><?php esc_html_e( 'Restore default settings', 'disable-wp-frontend' ); ?></label>
        <p style="margin-bottom: 25px;"
           class="description"><?php esc_html_e( 'Check this box and click save to restore the default settings for disabling the WP front end.', 'disable-wp-frontend' ); ?></p>
		<?php
	}

	/**
	 * Sanitizes the settings.
	 */
	public function sanitize_settings( array $settings ): array {
		// If the restore default settings checkbox is checked, restore the default settings.
		if ( isset( $settings['restore_default_settings']['restore_default_settings'] ) ) {
			return array();
		}

		// Sanitize the general settings.
		$settings['general_settings']['disable_wp_frontend'] = isset( $settings['general_settings']['disable_wp_frontend'] );

		// Sanitize the path whitelist. Take in the string from the textarea and split it into an array by new line. Make sure the array items are sanitized.
		$settings['exception_settings']['path_whitelist'] = array_map( 'sanitize_text_field', explode( "\n", $settings['exception_settings']['path_whitelist'] ) );

		return $settings;
	}
}
