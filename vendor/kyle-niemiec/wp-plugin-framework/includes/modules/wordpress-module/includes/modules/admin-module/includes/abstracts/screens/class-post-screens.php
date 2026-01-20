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

namespace WPPF\v1_2_0\WordPress\Admin\Screens;

defined( 'ABSPATH' ) or exit;

use WPPF\v1_2_0\WordPress\Admin\Screens;

if ( ! class_exists( '\WPPF\v1_2_0\WordPress\Admin\Screens\Post_Screens', false ) ) {

	/**
	 * A manager for screens that belong to a post type.
	 */
	abstract class Post_Screens extends Screens {

		/**
		 * Get the post type of the posts that this screen is associated with.
		 */
		abstract public static function post_type();

		/**
		 * The function which hooks when viewing multiple Posts of the static post type.
		 */
		abstract public static function view_posts( \WP_Screen $current_screen );

		/**
		 * The function which hooks when viewing a single Post of the static post type.
		 */
		abstract public static function view_post( \WP_Screen $current_screen );

		/**
		 * The function that hooks when adding a new Post of the static post type.
		 */
		abstract public static function add_post( \WP_Screen $current_screen );

		/**
		 * The required abstract inherited function for returning screen actions.
		 * 
		 * @return string[] The screen actions.
		 */
		final public static function get_valid_screens() {
			return array(
				'edit' => array(
					sprintf( 'edit-%s', static::post_type() ) => array(
						'' => array( static::class, 'view_posts' ),
					),
				),
				'post' => array(
					static::post_type() => array(
						'' => array( static::class, 'view_post' ),
						'add' => array( static::class, 'add_post' ),
					),
				),
			);
		}

	}

}
