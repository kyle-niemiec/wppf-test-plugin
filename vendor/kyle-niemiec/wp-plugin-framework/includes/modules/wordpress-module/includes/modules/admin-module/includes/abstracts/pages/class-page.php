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

namespace WPPF\v1_2_0\WordPress\Admin\Pages;

defined( 'ABSPATH' ) or exit;

use WPPF\v1_2_0\Framework\Singleton;
use WPPF\v1_2_0\Framework\Utility;

if ( ! class_exists( '\WPPF\v1_2_0\WordPress\Admin\Pages\Page', false ) ) {

	/**
	 * A class to abstract and automate the process of building Pages.
	 */
	abstract class Page extends Singleton {

		/**
		 * The function which will be called to add the page to the menu.
		 */
		abstract protected static function add_menu_item();

		/**
		 * The page slug for registering settings, adding menu items, etc.
		 * 
		 * @return string The page slug.
		 */
		abstract public static function page_option_group();

		/**
		 * The page name/title for display.
		 * 
		 * @return string The page title.
		 */
		abstract public static function page_title();

		/**
		 * The menu name/title for display.
		 * 
		 * @return string The menu title.
		 */
		abstract public static function menu_title();

		/**
		 * The capability required for the page to be displayed to the user.
		 * 
		 * @return string The capability required to display the settings page.
		 */
		abstract public static function page_capability();

		/**
		 * Return the submenu ID for use with the WordPress $submenu global.
		 * 
		 * @return string The ID of the submenu from the WordPress global $submenu.
		 */
		abstract public static function submenu_id();

		/**
		 * Return the Sections associated with this Settings Page.
		 * 
		 * @return \WPPF\v1_2_0\WordPress\Admin\Pages\Settings_Section[] The Sections of this Page.
		 */
		final public static function get_sections() { return $this->Sections; }

		/**
		 * Add action for creating submenu page.
		 */
		protected function __construct() {
			if ( ! self::menu_item_exists() ) {
				static::add_menu_item();
			} else {
				Utility::doing_it_wrong( __METHOD__, __( sprintf( 'Trying to register a Page which has already been registered in %s', static::class ) ) );
			}

		}

		/**
		 * See if the Settings Page exists in the WP global $submenu.
		 * 
		 * @return boolean Whether or not the Page has been set.
		 */
		final public static function menu_item_exists() {
			global $submenu;

			if ( isset( $submenu[ static::submenu_id() ] ) && is_array( $submenu[ static::submenu_id() ] ) ) {
				foreach ( $submenu[ static::submenu_id() ] as $page_options ) {
					if ( static::page_option_group() === $page_options[2] ) {
						return true;
					}
				}
			}

			return false;
		}

	}

}
