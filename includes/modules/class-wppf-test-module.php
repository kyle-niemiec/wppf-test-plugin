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

use WPPF\v1_2_0\Framework\Module;

if ( ! class_exists( 'WPPF_Test_Module', false ) ) {

	/**
	 * A test module to demonstrate functionality.
	 */
	final class WPPF_Test_Module extends Module {

		/**
		 * The module entry point.
		 */
		final public static function construct() {
			add_action( 'init', array( __CLASS__, '_init' ) );
		}

		/**
		 * The WordPress 'init' action hook.
		 */
		final public static function _init() {
			if ( isset( $_GET['wppf-test-email'] ) ) {
				WPPF_Test_Email::send_email();
			}
		}

	}

}
