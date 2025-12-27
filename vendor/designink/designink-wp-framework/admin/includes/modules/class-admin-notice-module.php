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

use DesignInk\WordPress\Framework\v1_1_2\Admin\Admin_Notices;
use DesignInk\WordPress\Framework\v1_1_2\DesignInk_Framework_Shadow_Plugin;
use DesignInk\WordPress\Framework\v1_1_2\Module;

if ( ! class_exists( '\DesignInk\WordPress\Framework\v1_1_2\Admin\Admin_Notice_Module', false ) ) {

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
			DesignInk_Framework_Shadow_Plugin::instance()->get_admin_module()->enqueue_js( 'designink-wp-admin-notices', array( 'jquery' ) );
		}

		/**
		 * WordPress 'admin_notices' hook.
		 */
		final public static function _admin_notices() {
			Admin_Notices::print_notices();
		}

	}

}
