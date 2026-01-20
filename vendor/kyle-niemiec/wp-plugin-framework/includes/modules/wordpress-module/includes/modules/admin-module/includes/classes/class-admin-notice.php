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

namespace WPPF\v1_2_0\WordPress\Admin;

defined( 'ABSPATH' ) or exit;

use WPPF\v1_2_0\WordPress\Admin\Admin_Notice_Queue;
use WPPF\v1_2_0\WPPF_Shadow_Plugin;
use WPPF\v1_2_0\Framework\Utility;

if ( ! class_exists( '\WPPF\v1_2_0\WordPress\Admin\Admin_Notice', false ) ) {

	/**
	 * A class intended to provide application notifications to the admin users. Built off transients to be one-time use.
	 */
	final class Admin_Notice {

		/** @var string The type of notification (i.e. 'error', 'notice', 'warning'). */
		public $type;

		/** The message this Notice should display. */
		public $message;

		/** Extra options passed to the Notification. */
		public $options;

		/** The default options */
		private static $default_options = array(
			'header' => null,
			'status_code' => null,
			'hint' => null,
		);

		/**
		 * Construct the Notice.
		 * 
		 * @param string $type The type of notification.
		 * @param string $message The notification message.
		 * @param string $options The notification options.
		 */
		public function __construct( string $type, string $message, array $options = array() ) {
			$this->type = $type;
			$this->message = $message;
			$this->options = Utility::guided_array_merge( self::$default_options, $options );
		}

		/**
		 * Print the HTML notice (for use by the 'admin_notices' action hook).
		 */
		final public function print() {
			WPPF_Shadow_Plugin::instance()->get_admin_module()->get_template( 'admin-notice-print', array( 'Notice' => $this ) );
		}

		/**
		 * Add the Notice to the display queue.
		 */
		final public function queue_notice() {
			Admin_Notice_Queue::add_notice( $this );
		}

		/**
		 * Check whether a variable is an Admin Notice.
		 * 
		 * @var mixed The variable to check for being an Admin Notice.
		 */
		final public static function is_admin_notice( $var ) {

			if ( 'object' === gettype( $var ) ) {

				if ( self::class === get_class( $var ) ) {
					return true;
				} else {
					return false;
				}

			} else {
				return false;
			}

		}

	}

}
