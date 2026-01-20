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

use WPPF\v1_2_0\WordPress\Admin\Meta_Box;

if ( ! class_exists( 'WPPF_Test_Post_Edit_Screen_Meta_Box', false ) ) {

	/**
	 * The primary Meta Box for the Post Series Post Type page.
	 */
	final class WPPF_Test_Post_Edit_Screen_Meta_Box extends Meta_Box {

		/** @var string Set context to advanced. */
		public $context = 'side';

		/**
		 * The required meta_key() abstract function.
		 * 
		 * @return string The meta key to use for nonces and inputs in the Meta Box.
		 */
		final public static function meta_key() { return 'edit_screen_test_meta_box'; }

		/**
		 * The required get_title() abstract function.
		 * 
		 * @return string The title of the Meta Box.
		 */
		final public static function get_title() { return 'Edit Screen Meta Box'; }

		/**
		 * The required get_id() abstract function.
		 * 
		 * @return string The ID for the Meta Box.
		 */
		final public static function get_id() { return WPPF_Test_Post_Type::post_type(); }

		/**
		 * The required render() abstract function.
		 */
		final protected static function render() {
			WPPF_Test_Plugin::instance()->get_admin_module()->get_template( 'edit-screen-test-meta-box' );
		}

		/**
		 * The inherited abstract function to attach to the 'save_post' hook.
		 * 
		 * @param int $post_id The Post ID.
		 * @param \WP_Post $Post The Post object.
		 * 
		 * @return int The post ID.
		 */
		final protected static function save_post( int $post_id, ?\WP_Post $Post = null ) { return $post_id; }

	}

}
