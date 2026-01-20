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

namespace WPPF\v1_2_0\Plugin\Staging_Detection;

defined( 'ABSPATH' ) or exit;

use WPPF\v1_2_0\WordPress\Admin\Admin_Notices;
use WPPF\v1_2_0\Framework\Module;
use WPPF\v1_2_0\Plugin\Staging_Detection;
use WPPF\v1_2_0\Framework\Utility;

if ( ! class_exists( '\WPPF\v1_2_0\Plugin\Staging_Detection\Staging_Detection_Notice', false ) ) {

	/**
	 * A Module which registers a site host as an expected host and throws a notification if the expected
	 * host differs from the current host and at least one Plugin has registered a notification.
	 */
	final class Staging_Detection_Notice extends Module {

		/** @var string[] A list of registered messages to display in case of staging detection. */
		private static $notices = array();

		/**
		 * Module entry point.
		 */
		final public static function construct() {
			add_action( 'admin_notices', array( __CLASS__, '_admin_notices' ) );
		}

		/**
		 * The WordPress 'admin_notices' action hook.
		 */
		final public static function _admin_notices() {
			if ( Staging_Detection::is_staging() && ! empty( self::$notices ) ) {
				foreach ( self::$notices as $notice ) {
					$data = get_plugin_data( $notice['plugin_file'] );
					Admin_Notices::warning( sprintf( '%s says %s', $data['Name'], $notice['message'] ) );
				}
			}
		}

		/**
		 * Add a notice message to the Queue.
		 * 
		 * @param string $plugin The file of the plugin to show a staging notification for.
		 * @param string $message The message to display.
		 */
		final public static function add_notice( string $plugin_file, string $message ) {
			if ( ! file_exists( $plugin_file ) ) {
				$message = sprintf( 'A valid file path must be passed to %s.', __METHOD__ );
				Utility::doing_it_wrong( __METHOD__, $message );
				return;
			}

			array_push( self::$notices, array( 'plugin_file' => $plugin_file, 'message' => $message ) );
		}

	}

}
