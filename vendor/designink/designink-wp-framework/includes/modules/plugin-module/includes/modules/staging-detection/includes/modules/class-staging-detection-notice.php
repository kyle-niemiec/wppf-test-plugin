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

namespace DesignInk\WordPress\Framework\v1_1_2\Staging_Detection;

defined( 'ABSPATH' ) or exit;

use DesignInk\WordPress\Framework\v1_1_2\Admin\Admin_Notices;
use DesignInk\WordPress\Framework\v1_1_2\Module;
use DesignInk\WordPress\Framework\v1_1_2\Staging_Detection;
use DesignInk\WordPress\Framework\v1_1_2\Utility;

if ( ! class_exists( '\DesignInk\WordPress\Framework\v1_1_2\Staging_Detection\Staging_Detection_Notice', false ) ) {

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
