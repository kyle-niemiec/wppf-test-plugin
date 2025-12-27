<?php
/**
 * DesignInk WordPress Framework
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to answers@designinkdigital.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the plugin to newer
 * versions in the future. If you wish to customize the plugin for your
 * needs please refer to https://designinkdigital.com
 *
 * @package   DesignInk/WordPress/Framework
 * @author    DesignInk Digital
 * @copyright Copyright (c) 2008-2026, DesignInk, LLC
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

namespace DesignInk\WordPress\Framework\v1_1_2\Admin\Pages;

defined( 'ABSPATH' ) or exit;

use DesignInk\WordPress\Framework\v1_1_2\Module;

if ( ! class_exists( '\DesignInk\WordPress\Framework\v1_1_2\Admin\Pages\DesignInk_Settings_Page_Module', false ) ) {

	/**
	 * Manage the settings for for this plugin.
	 */
	final class DesignInk_Settings_Page_Module extends Module {

		/** @var \DesignInk\WordPress\Framework\v1_1_2\Admin\Pages $Settings_Page The Settings Page instance. */
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
			self::$Settings_Page = new DesignInk_Settings_Page();
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

			if ( is_array( $wp_settings_sections ) && array_key_exists( DesignInk_Settings_Page::page_option_group(), $wp_settings_sections ) ) {
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
			$page = DesignInk_Settings_Page::page_option_group();

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
