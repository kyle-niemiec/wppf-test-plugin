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

namespace WPPF\v1_2_0\Admin\Pages;

defined( 'ABSPATH' ) or exit;

use WPPF\v1_2_0\WordPress\Admin\Pages\Page;

if ( ! class_exists( '\WPPF\v1_2_0\Admin\Pages\Management_Page', false ) ) {

	/**
	 * A class to abstract and automate the process of creating a page under the 'tools' menu item.
	 */
	abstract class Management_Page extends Page {

		/**
		 * The inherited function from the abstract returning the submenu ID.
		 * 
		 * @return string The ID of the submenu from the WordPress global $submenu.
		 */
		final public static function submenu_id() { return 'tools.php'; }

		/**
		 * Construct the parent settings page.
		 */
		public function __construct() {
			parent::__construct();
		}

		/**
		 * The inherited, abstract menu item function.
		 */
		final protected static function add_menu_item() {
			add_management_page(
				__( static::page_title() ),
				__( static::menu_title() ),
				static::page_capability(),
				static::page_option_group(),
				array( static::class, 'render')
			);
		}

	}

}
