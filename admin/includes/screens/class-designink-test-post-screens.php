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

use DesignInk\WordPress\Framework\v1_1_2\Admin\Screens\Post_Screens;

if ( ! class_exists( 'DesignInk_Test_Post_Screens', false ) ) {

	/**
	 * The Test Post screens in the admin.
	 */
	final class DesignInk_Test_Post_Screens extends Post_Screens {

		/**
		 * The post type the screens apply to.
		 * 
		 * @return string The post type.
		 */
		final public static function post_type() { return DesignInk_Test_Post_Type::post_type(); }

		/**
		 * The primary entry function for running code globally in the admin area. This code will run with regular Module constructions.
		 */
		final public static function construct_screen() { }

		/**
		 * The primary entry function for running code locally in the screen. This code will run in the 'current_screen' WordPress hook.
		 * This function will be called when any page from the valid screens is matched.
		 * 
		 * @param \WP_Screen $current_screen The current Screen being viewed.
		 */
		final public static function current_screen( \WP_Screen $current_screen ) { }

		/**
		 * Viewing the single post.
		 * 
		 * @param \WP_Screen The current screen.
		 */
		final public static function view_post( \WP_Screen $current_screen ) {
			DesignInk_Test_Post_Edit_Screen_Meta_Box::instance()->add_meta_box();
		}

		/**
		 * Viewing all posts.
		 * 
		 * @param \WP_Screen The current screen.
		 */
		final public static function view_posts( \WP_Screen $current_screen ) { }

		/**
		 * Adding a post.
		 * 
		 * @param \WP_Screen The current screen.
		 */
		final public static function add_post( \WP_Screen $current_screen ) { }

	}

}
