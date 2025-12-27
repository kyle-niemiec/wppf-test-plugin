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

namespace DesignInk\WordPress\Framework\v1_1_2\Plugin;

defined( 'ABSPATH' ) or exit;

use DesignInk\WordPress\Framework\v1_1_2\Post_Meta;

if ( ! class_exists( '\DesignInk\WordPress\Framework\v1_1_2\Plugin\Post', false ) ) {

	/**
	 * A class to represent and help deal with common custom post type functionality.
	 */
	abstract class Post {

		/** @var \WP_Post The parent Post object instance. */
		protected $Post;

		/** @var array Meta data associated with the Post. */
		protected $Meta = array();

		/**
		 * Get the post type of the Post.
		 */
		abstract public static function post_type();

		/**
		 * Returns the parent WP_Post object.
		 *
		 * @return \WP_Post The parent Post object.
		 */
		final public function get_post() { return $this->Post; }

		/**
		 * Returns the ID of the parent WP_Post.
		 *
		 * @return int The ID of the parent WP_Post object.
		 */
		final public function get_ID() { return $this->Post->ID; }

		/**
		 * Returns the title of the parent WP_Post.
		 *
		 * @return string The title of the parent WP_Post object.
		 */
		final public function get_title() { return $this->Post->post_title; }

		/**
		 * Returns the Post meta.
		 * 
		 * @param string $meta_key The meta key of the Meta to return.
		 *
		 * @return null|\DesignInk\WordPress\Framework\v1_1_2\Post_Meta The Post meta if found or NULL.
		 */
		final public function get_meta( string $meta_key = '' ) {

			if ( 1 === count( $this->Meta ) && empty( $meta_key ) ) {
				// Return the only Meta if there's only one and the $meta_key is empty.
				return $this->Meta[ array_keys( $this->Meta )[0] ];
			} else if ( empty( $meta_key ) ) {
				return $this->Meta;
			} else if ( array_key_exists( $meta_key, $this->Meta ) ) {
				// Or try to find and return the Meta
				return $this->Meta[ $meta_key ];
			}

			// Else if not found
			return null;
		}

		/**
		 * Construct the Post. Load Post and Post Meta data.
		 * 
		 * @param int|\WP_Post $id The \WP_Post object, or the ID to one.
		 */
		public function __construct( $id ) {

			// Fail if ID is empty
			if ( empty( $id ) ) {
				$message = sprintf( "Empty ID passed to %s constructor.", static::class );
				throw new \Exception( __( $message ) );
			}

			$Post = null;

			// Load the Post
			if ( is_numeric( $id ) ) {
				// Find post by ID
				$Post = get_post( $id );

				if ( ! $Post ) {
					$message = sprintf( "Could not find post by ID passed to %s constructor.", static::class );
					throw new \Exception( __( $message ) );
				}
			} else if ( $id instanceof \WP_Post ) {
				// Post directly passed in
				$Post = $id;
			}

			// Check the post type. Will fail if not loaded above.
			if ( static::post_type() !== $Post->post_type ) {
				$message = sprintf( "Post found by ID passed to %s constructor is not of post type %s.", static::class, static::post_type() );
				throw new \Exception( __( $message ) );
			}

			$this->Post = $Post;
		}

		/**
		 * Add a Post Meta to this Post.
		 * 
		 * @param \DesignInk\WordPress\Framework\v1_1_2\Post_Meta
		 */
		final protected function add_meta( Post_Meta $Meta ) {
			if ( ! array_key_exists( $Meta->key(), $this->Meta ) ) {
				$this->Meta[ $Meta->key() ] = $Meta;
			}
		}

	}

}
