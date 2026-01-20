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

namespace WPPF\v1_2_0\Admin\Screens;

defined( 'ABSPATH' ) or exit;

use WPPF\v1_2_0\WordPress\Admin\Pages\Options_Page;

if ( ! class_exists( '\WPPF\v1_2_0\Admin\Screens\WPPF_Settings_Page', false ) ) {

	/**
	 * The options page configuration for general settings regarding the modules included in this plugin.
	 */
	final class WPPF_Settings_Page extends Options_Page {

		/** @var string The page option group. */
		final public static function page_option_group() { return 'wppf-settings'; }

		/** @var string The page title. */
		final public static function page_title() { return 'WPPF Settings'; }

		/** @var string The page menu title. */
		final public static function menu_title() { return 'WPPF Settings'; }

		/** @var string The page capability. */
		final public static function page_capability() { return 'manage_options'; }

		/**
		 * Construct the parent model. Make sure to call after global $submenu is defined.
		 */
		final public function __construct() {
			if ( ! self::menu_item_exists() ) {
				parent::__construct();
			}
		}

	}

}
