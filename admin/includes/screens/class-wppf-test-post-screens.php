<?php
/**
 * WPPF Test Plugin
 *
 * Copyright (c) 2008–2026 DesignInk, LLC
 * Copyright (c) 2026 Kyle Niemiec
 *
 * This file is licensed under the GNU General Public License v3.0.
 * See the LICENSE file for details.
 */

defined( 'ABSPATH' ) or exit;

use WPPF\v1_2_0\WordPress\Admin\Screens\Post_Screens;

if ( ! class_exists( 'WPPF_Test_Post_Screens', false ) ) {

	/**
	 * The Test Post screens in the admin.
	 */
	final class WPPF_Test_Post_Screens extends Post_Screens {

		/**
		 * The post type the screens apply to.
		 * 
		 * @return string The post type.
		 */
		final public static function post_type() { return WPPF_Test_Post_Type::post_type(); }

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
