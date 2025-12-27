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

namespace DesignInk\WordPress\Framework\v1_1_2\Admin\Screens;

defined( 'ABSPATH' ) or exit;

use DesignInk\WordPress\Framework\v1_1_2\Admin\Screens;

if ( ! class_exists( '\DesignInk\WordPress\Framework\v1_1_2\Admin\Screens\Post_Screens', false ) ) {

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
