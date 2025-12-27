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

use DesignInk\WordPress\Framework\v1_1_2\Admin\Admin_Notice;

if ( ! class_exists( '\DesignInk\WordPress\Framework\v1_1_2\Admin\Admin_Notices', false ) ) {

	/**
	 * Utility functions class to hold useful chunks of code we find ourselves often reusing.
	 */
	final class Admin_Notices {

		/** @var string The string identifying the transient used for the Admin Notice queue. */
		const NOTICE_QUEUE_TRANSIENT = '_di_admin_notice_queue';

		/**
		 * Add an Admin Notice to the global queue.
		 * 
		 * @param Admin_Notice $Notice The Admin Notice to be added to the queue.
		 */
		final public static function add_notice( Admin_Notice $Notice ) {
			$notices = get_transient( self::NOTICE_QUEUE_TRANSIENT );

			if ( false === $notices ) {
				$notices = array();
			}

			$notices[] = $Notice;
			set_transient( self::NOTICE_QUEUE_TRANSIENT, $notices, 0 );
		}

		/**
		 * A function to queue a success admin notice.
		 * 
		 * @param string $message The message to display a success notice for.
		 */
		final public static function success( string $message ) {
			self::add_notice( new Admin_Notice( 'success', $message ) );
		}

		/**
		 * A function to queue an info admin notice.
		 * 
		 * @param string $message The message to display an info notice for.
		 */
		final public static function info( string $message ) {
			self::add_notice( new Admin_Notice( 'info', $message ) );
		}

		/**
		 * A function to queue a warning admin notice.
		 * 
		 * @param string $message The message to display a warning notice for.
		 */
		final public static function warning( string $message ) {
			self::add_notice( new Admin_Notice( 'warning', $message ) );
		}

		/**
		 * A function to queue an error warning admin notice.
		 * 
		 * @param string $message The message to display an error notice for.
		 */
		final public static function error( string $message ) {
			self::add_notice( new Admin_Notice( 'error', $message ) );
		}

		/**
		 * Print all of the Admin Notices and remove the transient.
		 */
		final public static function print_notices() {
			$notices = get_transient( self::NOTICE_QUEUE_TRANSIENT );

			if ( ! is_array( $notices ) ) {
				return;
			}

			foreach ( $notices as $key => $Notice ) {
				if ( Admin_Notice::is_admin_notice( $Notice ) ) {
					$Notice->print();
					unset( $notices[ $key ] );
				}
			}

			set_transient( self::NOTICE_QUEUE_TRANSIENT, $notices, 0 );
		}

	}

}
