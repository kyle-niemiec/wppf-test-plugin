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

namespace DesignInk\WordPress\Framework\v1_1_2\Admin;

defined( 'ABSPATH' ) or exit;

use DesignInk\WordPress\Framework\v1_1_2\Admin\Admin_Notice_Queue;
use DesignInk\WordPress\Framework\v1_1_2\DesignInk_Framework_Shadow_Plugin;
use DesignInk\WordPress\Framework\v1_1_2\Utility;

if ( ! class_exists( '\DesignInk\WordPress\Framework\v1_1_2\Admin\Admin_Notice', false ) ) {

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
			DesignInk_Framework_Shadow_Plugin::instance()->get_admin_module()->get_template( 'admin-notice-print', array( 'Notice' => $this ) );
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
