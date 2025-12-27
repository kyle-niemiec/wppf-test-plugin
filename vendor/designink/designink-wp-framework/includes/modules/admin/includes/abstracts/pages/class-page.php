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

use DesignInk\WordPress\Framework\v1_1_2\Singleton;
use DesignInk\WordPress\Framework\v1_1_2\Utility;

if ( ! class_exists( '\DesignInk\WordPress\Framework\v1_1_2\Admin\Pages\Page', false ) ) {

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
		 * @return \DesignInk\WordPress\Framework\v1_1_2\Admin\Pages\Settings_Section[] The Sections of this Page.
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
