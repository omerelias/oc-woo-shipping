<?php

/**
 * Internationalization: load `ocws` only from this plugin’s `languages/` folder.
 *
 * Avoids `load_plugin_textdomain()` which also loads `wp-content/languages/plugins/…`
 * and can merge a broken `ocws-{locale}.mo` (e.g. Hebrew strings under en_US) that wins over plugin files.
 *
 * Always loads from `.mo` (not WP 6.5's `.l10n.php`) so Loco Translate edits show up
 * immediately without needing `wp i18n make-php`.
 *
 * @package Oc_Woo_Shipping
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register and load text domain for `ocws`.
 */
class Oc_Woo_Shipping_i18n {

	/**
	 * Load translations from `wp-content/plugins/oc-woo-shipping/languages/` only.
	 */
	public function load_plugin_textdomain(): void {
		if ( ! defined( 'OCWS_PATH_FILE' ) ) {
			return;
		}

		$domain   = 'ocws';
		$locale   = determine_locale();
		$lang_dir = trailingslashit( plugin_dir_path( OCWS_PATH_FILE ) . 'languages' );

		// Drop any earlier `ocws` catalog (core path, other hooks) so only plugin files apply.
		// Use reloadable=false so WP_Translation_Controller clears; true skips that and can leave stale merges.
		if ( function_exists( 'unload_textdomain' ) ) {
			unload_textdomain( $domain, false );
		}

		add_filter( 'translation_file_format', array( $this, 'translation_file_format_prefer_mo' ), 10, 2 );

		$mofile = $lang_dir . $domain . '-' . $locale . '.mo';
		if ( is_readable( $mofile ) ) {
			load_textdomain( $domain, $mofile, $locale );
		}
	}

	/**
	 * Always use MO for the `ocws` domain so Loco edits apply without `wp i18n make-php`.
	 *
	 * @param string $format Preferred format: 'php' or 'mo'.
	 * @param string $domain Text domain.
	 * @return string
	 */
	public function translation_file_format_prefer_mo( string $format, string $domain ): string {
		return 'ocws' === $domain ? 'mo' : $format;
	}
}
