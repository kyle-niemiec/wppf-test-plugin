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

use DesignInk\WordPress\Framework\v1_1_2\Plugin\Post;

if ( ! class_exists( 'DesignInk_Test_Post', false ) ) {

	/**
	 * A class wrapper for dealing with Test Post functionality.
	 */
	final class DesignInk_Test_Post extends Post {

		/**
		 * The required abstract function for the expected post type.
		 * 
		 * @return string The post type.
		 */
		final public static function post_type() { return DesignInk_Test_Post_Type::post_type(); }

		/**
		 * A more readable function to access the Meta with.
		 * 
		 * @return \DesignInk_Test_Post_Meta The Test Post Meta.
		 */
		final public function get_test_meta() {
			return $this->get_meta( DesignInk_Test_Post_Meta::meta_key() );
		}

		/**
		 * Test Post constructor.
		 *
		 * @param int|string|\WP_Post $id The ID of the parent \WP_Post, or the parent class itself.
		 */
		public function __construct( $id ) {
			parent::__construct( $id );
			$this->add_meta( new DesignInk_Test_Post_Meta( $this ) );
		}

	}

}
