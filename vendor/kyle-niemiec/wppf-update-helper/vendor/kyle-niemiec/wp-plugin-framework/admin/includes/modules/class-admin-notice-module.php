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

use WPPF\v1_2_0\WordPress\Admin\Admin_Notices;
use WPPF\v1_2_0\WPPF_Shadow_Plugin;
use WPPF\v1_2_0\Framework\Module;

if ( ! class_exists( '\WPPF\v1_2_0\WordPress\Admin\Admin_Notice_Module', false ) ) {

	/**
	 * This module holds the logic for saving our admin notices as transients and displaying them on an admin page load.
	 */
	final class Admin_Notice_Module extends Module {

		/**
		 * Module entry point.
		 */
		final public static function construct() {
			add_action( 'admin_notices', array( __CLASS__, '_admin_notices' ), 99999 );
			add_action( 'admin_enqueue_scripts', array( __CLASS__, '_admin_enqueue_scripts' ) );
		}

		/**
		 * WordPress 'admin_enqueue_scripts' hook.
		 */
		final public static function _admin_enqueue_scripts() {
			WPPF_Shadow_Plugin::instance()->get_admin_module()->enqueue_js( 'wppf-admin-notices', array( 'jquery' ) );
		}

		/**
		 * WordPress 'admin_notices' hook.
		 */
		final public static function _admin_notices() {
			Admin_Notices::print_notices();
		}

	}

}
