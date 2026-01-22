<?php
/**
 * WordPress Plugin Framework
 *
 * Copyright (c) 2008–2026 DesignInk, LLC
 * Copyright (c) 2026 Kyle Niemiec
 *
 * This file is licensed under the GNU General Public License v3.0.
 * See the LICENSE file for details.
 *
 * @package WPPF
 */

namespace WPPF\v1_2_0\Admin;

defined( 'ABSPATH' ) or exit;

use WPPF\v1_2_0\Admin\Screens\WPPF_Settings_Page;
use WPPF\v1_2_0\Framework\Module;

if ( ! class_exists( '\WPPF\v1_2_0\Admin\WPPF_Settings_Page_Module', false ) ) {

	/**
	 * Manage the settings for for this plugin.
	 */
	final class WPPF_Settings_Page_Module extends Module {

		/** @var \WPPF\v1_2_0\WordPress\Admin\Pages\Options_Page $Settings_Page The Settings Page instance. */
		public static $Settings_Page;

		/**
		 * Add WordPress hooks, set Settings Page instance.
		 */
		final public static function construct() {
			add_action( 'admin_init', array( __CLASS__, '_admin_init' ), 11 );
			add_action( 'admin_menu', array( __CLASS__, '_admin_menu' ) );
		}

		/**
		 * WordPress 'admin_menu' hook.
		 */
		final public static function _admin_menu() {
			self::$Settings_Page = WPPF_Settings_Page::instance();
		}

		/**
		 * WordPress 'admin_init' hook.
		 */
		final public static function _admin_init() {
			if ( ! self::settings_sections_registered() ) {
				self::unset_menu();
			}
		}

		/**
		 * Check whether or not any settings have been registered into the settings page.
		 * 
		 * @return bool Whether or not sections for the settings page were found.
		 */
		private static function settings_sections_registered() {
			global $wp_settings_sections;

			if ( is_array( $wp_settings_sections ) && array_key_exists( WPPF_Settings_Page::page_option_group(), $wp_settings_sections ) ) {
				return true;
			} else {
				return false;
			}

		}

		/**
		 * Find the menu instance and unset it if it exists.
		 */
		private static function unset_menu() {
			global $submenu;
			$root = 'options-general.php';
			$page = WPPF_Settings_Page::page_option_group();

			if ( is_array( $submenu ) ) {
				foreach ( $submenu[ $root ] as $id => $options ) {
					if ( $page === $options[2] ) {
						unset( $submenu[ $root ][ $id ] );
						break;
					}
				}
			}
		}

	}

}
