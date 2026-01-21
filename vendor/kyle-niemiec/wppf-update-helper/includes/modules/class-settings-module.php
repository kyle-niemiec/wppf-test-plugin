<?php
/**
 * WPPF Update Helper
 *
 * Copyright (c) 2008–2020 DesignInk, LLC
 * Copyright (c) 2026 Kyle Niemiec
 *
 * This file is licensed under the GNU General Public License v3.0.
 * See the LICENSE file for details.
 *
 * @package WPPF\Update_Helper
 */

namespace WPPF\Update_Helper\v1_0_1;

defined( 'ABSPATH' ) or exit;

use WPPF\v1_2_0\Admin\Screens\WPPF_Settings_Page;
use WPPF\v1_2_0\Framework\Module;
use WPPF\v1_2_0\WordPress\Admin\Pages\Settings_Section;

if ( ! class_exists( 'WPPF\Update_Helper\v1_0_1\Settings_Module', false ) ) {

	/**
	 * The module controls the loading of the SSL settings into the WPPF settings page.
	 */
	final class Settings_Module extends Module {

		/** @var string The name of the plugin updates section in the WPPF settings page. */
		const SSL_SECTION_NAME = 'wppf_plugin_updates_ssl';

		/**
		 * Module entry point.
		 */
		final public static function construct() {
			add_action( 'admin_init', array( __CLASS__, '_admin_init' ) );
		}

		/**
		 * WordPress 'admin_init' hook
		 */
		public static function _admin_init() {
			self::add_ssl_settings();
		}

		/**
		 * Get the SSL key saved from the WPPF Settings Page.
		 * 
		 * @return false|string Return the saved SSL key or FALSE if it does not exist.
		 */
		final public static function get_ssl_key() {
			$option = sprintf(
				'_%s_%s',
				WPPF_Settings_Page::page_option_group(),
				self::SSL_SECTION_NAME
			);

			return get_option( $option, false );
		}

		/**
		 * Add the SSL key settings to the WPPF Settings Page.
		 */
		private static function add_ssl_settings() {
			$Settings_Page = WPPF_Settings_Page::instance();
			$section_description = 'The SSL key and initialization vector for self-hosted plugin updates if using encryption for a private key.';

			$Settings_Page->add_section( new Settings_Section(
				$Settings_Page,
				self::SSL_SECTION_NAME,
				array(
					'label' => __( "Plugin Update Server Settings" ),
					'description' => __( $section_description ),
					'inputs' => array(
						array(
							'label' => __( "SSL Key" ),
							'name' => 'key'
						)
					),
				)
			) );
		}

	}

}