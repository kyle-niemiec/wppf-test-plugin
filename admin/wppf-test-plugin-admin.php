<?php
/**
 * WPPF Test Plugin
 *
 * Copyright (c) 2008–2026 DesignInk, LLC
 * Copyright (c) 2026 Kyle Niemiec
 *
 * This file is licensed under the GNU General Public License v3.0.
 * See the LICENSE file for details.
 */

defined( 'ABSPATH' ) or exit;

use WPPF\v1_2_0\Framework\Admin_Module;

if ( ! class_exists( 'WPPF_Test_Plugin_Admin', false ) ) {

	/**
	 * The admin wrapper class for this plugin.
	 */
	final class WPPF_Test_Plugin_Admin extends Admin_Module {

		/**
		 * Module entry point.
		 */
		final public static function construct() { }

	}

}
