<?php
/**
 * DesignInk WP Framework Test Plugin
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
 * @author    DesignInk Digital
 * @copyright Copyright (c) 2008-2026, DesignInk, LLC
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

defined( 'ABSPATH' ) or exit;

use DesignInk\WordPress\Framework\v1_1_2\Module;

if ( ! class_exists( 'DesignInk_Test_Module', false ) ) {

	/**
	 * A test module to demonstrate functionality.
	 */
	final class DesignInk_Test_Module extends Module {

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
			if ( isset( $_GET['designink-test-email'] ) ) {
				DesignInk_Test_Email::send_email( null );
			}
		}

	}

}
