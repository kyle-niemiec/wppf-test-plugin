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

if ( ! class_exists( '\DesignInk\WordPress\Framework\v1_1_2\Admin\Screens', false ) ) {

	/**
	 * An extension to the Admin Module that manages code related to particular admin screens, and what code should be run in global or local context.
	 */
	abstract class Screens {

		/** @var int The priority during which to run the 'current_screen' hook context. Default is 51 because WooCommerce adds their help pages at 50. */
		protected static $screen_hook_priority = 51;

		/**
		 * The primary entry function for running code globally in the admin area. This code will run with regular Module constructions.
		 */
		abstract public static function construct_screen();

		/**
		 * The primary entry function for running code locally in the screen. This code will run in the 'current_screen' WordPress hook.
		 * This function will be called when any page from the valid screens is matched.
		 * 
		 * @param \WP_Screen $current_screen The current Screen being viewed.
		 */
		abstract public static function current_screen( \WP_Screen $current_screen );

		/**
		 * Return a set of nested associative arrays which define the screens for which code should run.
		 * The order of nesting should have screen bases, outside screen IDs, outside screen actions, and the actions hold a callable function in the class.
		 * An example set of screens for a post type will look like such:
		 * 
		 * 	return array(
		 * 		// The edit.php base pages
		 * 		'edit' => array(
		 * 			// The view posts screen action for screen ID 'edit-custom_post_type'.
		 * 			'edit-custom_post_type' => array(
		 * 				'' => array( static::class, 'view_posts' ),
		 * 			),
		 * 		),
		 * 		// The post.php base pages
		 * 		'post' => array(
		 * 			// The view post (blank) and add new post screen actions for screen ID 'custom_post_type'.
		 * 			'custom_post_type' => array(
		 * 				'' => array( static::class, 'view_post' ),
		 * 				'add' => array( static::class, 'add_post' ),
		 * 			),
		 * 		),
		 * 	);
		 * 
		 * @return array[] The IDs for which the screen matches.
		 */
		abstract public static function get_valid_screens();

		/**
		 * Iterates through the current screen 'base', 'id', and 'action' sequentially through the valid screens of this class and returns TRUE if they match, otherwise FALSE.
		 * 
		 * @return bool Whether the current screen matches one of the valid screens of this class.
		 */
		final public static function is_current_screen() {
			$screen = get_current_screen();
			$screens = static::get_valid_screens();

			if ( ! $screen ) {
				return false;
			}

			$base_exists = array_key_exists( $screen->base, $screens );

			if ( ! $base_exists ) {
				return false;
			}

			$id_exists = array_key_exists( $screen->id, $screens[ $screen->base ] );

			if ( ! $id_exists ) {
				return false;
			}

			$action_exists = array_key_exists( $screen->action, $screens[ $screen->base ][ $screen->id ] );

			if ( ! $action_exists ) {
				return false;
			}

			return true;
		}

		/**
		 * Returns the callback function associated with the current screen in this class. Otherwise, if not on a valid screen, returns FALSE.
		 * 
		 * @return false|callable The callable function associated with the static class instance or FALSE.
		 */
		final public static function get_current_screen_callback() {
			$screen = get_current_screen();
			$screens = static::get_valid_screens();

			if ( ! $screen ) {
				return false;
			}

			if ( static::is_current_screen() ) {
				return $screens[ $screen->base ][ $screen->id ][ $screen->action ];
			}

			return false;
		}

		/**
		 * The primary entry point for the Admin Module to call when registering this Screen.
		 */
		public static function construct() {
			static::construct_screen();
			add_action( 'current_screen', array( static::class, '_current_screen' ), static::$screen_hook_priority, 1 );
		}

		/**
		 * The WordPress hook 'current_screen' which should be used to construct the screen module since that is when the data is available.
		 */
		final public static function _current_screen( \WP_Screen $current_screen ) {
			if ( static::is_current_screen() ) {
				$screen_callback = static::get_current_screen_callback();
				static::current_screen( $current_screen );

				if ( is_callable( $screen_callback ) ) {
					call_user_func( $screen_callback, $current_screen );
				}
			}
		}

	}

}
