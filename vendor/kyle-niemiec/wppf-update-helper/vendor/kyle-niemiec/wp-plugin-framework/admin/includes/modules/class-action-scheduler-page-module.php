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

use WPPF\v1_2_0\Admin\Screens\Action_Scheduler_Page;
use WPPF\v1_2_0\Framework\Module;

if ( ! class_exists( '\WPPF\v1_2_0\Admin\Action_Scheduler_Page_Module', false ) ) {

	/**
	 * Manage the settings for for this plugin.
	 */
	final class Action_Scheduler_Page_Module extends Module {

		/** @var \WPPF\v1_2_0\Admin\Pages\Management_Settings_Page $Page The Page instance. */
		public static $Page;

		/**
		 * Add WordPress hooks, set Page instance.
		 */
		final public static function construct() {
			add_action( 'admin_menu', array( __CLASS__, '_admin_menu' ) );
		}

		/**
		 * WordPress 'admin_menu' hook.
		 */
		final public static function _admin_menu() {
			self::$Page = new Action_Scheduler_Page();
		}

	}

}
