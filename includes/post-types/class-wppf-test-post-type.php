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

use WPPF\v1_2_0\WordPress\Post_Type;

if ( ! class_exists( 'WPPF_Test_Post_Type', false ) ) {

	/**
	 * A class to represent and help deal with common plugin functionality.
	 */
	final class WPPF_Test_Post_Type extends Post_Type {

		/**
		 * The required name from the abstract.
		 * 
		 * @return string The Post Type name.
		 */
		final public static function post_type() { return 'wppf_test_posts'; }

		/**
		 * Add Meta Box and call parent.
		 */
		final public function __construct() {
			parent::__construct();
			$this->add_meta_box( WPPF_Test_Post_Meta_Box::instance() );
		}

		/**
		 * The required options from the abstract.
		 * 
		 * @return array The Post Type options.
		 */
		final protected function post_type_options() {
			return array(
				'labels' => array(
					'menu_name' => __( 'Test Posts' ),
				),
				'singular_name'	=> __( 'Test Post' ),
				'plural_name'	=> __( 'Test Posts' ),
				'public'		=> true,
				'show_in_menu'	=> true,
				'show_ui'		=> true,
				'has_archive'	=> true,
				'supports'		=> array( 'title', 'thumbnail' ),
			);
		}

	}

}
