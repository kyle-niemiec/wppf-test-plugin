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

use WPPF\v1_2_0\WordPress\Post;

if ( ! class_exists( 'WPPF_Test_Post', false ) ) {

	/**
	 * A class wrapper for dealing with Test Post functionality.
	 */
	final class WPPF_Test_Post extends Post {

		/**
		 * The required abstract function for the expected post type.
		 * 
		 * @return string The post type.
		 */
		final public static function post_type() { return WPPF_Test_Post_Type::post_type(); }

		/**
		 * A more readable function to access the Meta with.
		 * 
		 * @return \WPPF_Test_Post_Meta The Test Post Meta.
		 */
		final public function get_test_meta() {
			return $this->get_meta( WPPF_Test_Post_Meta::meta_key() );
		}

		/**
		 * Test Post constructor.
		 *
		 * @param int|string|\WP_Post $id The ID of the parent \WP_Post, or the parent class itself.
		 */
		public function __construct( $id ) {
			parent::__construct( $id );
			$this->add_meta( new WPPF_Test_Post_Meta( $this ) );
		}

	}

}
