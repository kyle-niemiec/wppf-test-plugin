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

namespace WPPF\v1_2_0;

defined( 'ABSPATH' ) or exit;

use WPPF\v1_2_0\Framework\Admin_Module;

if ( ! class_exists( '\WPPF\v1_2_0\WPPF_Shadow_Plugin_Admin', false ) ) {

	/**
	 * The 'shadow' plugin for the framework that will control the loading of crucial modules.
	 */
	final class WPPF_Shadow_Plugin_Admin extends Admin_Module {

		/**
		 * Module entry point.
		 */
		final public static function construct() {
			add_action( 'admin_enqueue_scripts', array( __CLASS__, '_admin_enqueue_scripts' ) );
		}

		/**
		 * WordPress 'admin_enqueue_scripts' hook.
		 */
		final public static function _admin_enqueue_scripts() {
			self::instance()->enqueue_css( 'wppf-admin-styles' );
		}

	}

}
