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

use DesignInk\WordPress\Framework\v1_1_2\Post_Type;

if ( ! class_exists( 'DesignInk_Test_Post_Type', false ) ) {

	/**
	 * A class to represent and help deal with common plugin functionality.
	 */
	final class DesignInk_Test_Post_Type extends Post_Type {

		/**
		 * The required name from the abstract.
		 * 
		 * @return string The Post Type name.
		 */
		final public static function post_type() { return 'designink_test_posts'; }

		/**
		 * Add Meta Box and call parent.
		 */
		final public function __construct() {
			parent::__construct();
			$this->add_meta_box( DesignInk_Test_Post_Meta_Box::instance() );
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
