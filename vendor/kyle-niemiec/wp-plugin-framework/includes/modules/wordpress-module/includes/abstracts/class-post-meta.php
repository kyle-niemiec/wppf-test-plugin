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

namespace WPPF\v1_2_0\WordPress;

defined( 'ABSPATH' ) or exit;

use WPPF\v1_2_0\WordPress\Meta;

if ( ! class_exists( '\WPPF\v1_2_0\WordPress\Post_Meta', false ) ) {

	/**
	 * An abstract for dealing with Post Meta.
	 */
	abstract class Post_Meta extends Meta {

		/** @var \WP_Post The Post that this Meta belongs to. */
		protected $Post;

		/**
		 * Return the Post.
		 * 
		 * @return \WP_Post The Post the Meta belongs to.
		 */
		final public function get_post() { return $this->Post; }

		/**
		 * Construct construct the Post Meta, instantiate Post if necessary, call parent constructor.
		 * 
		 * @param \WP_Post|int $post The Post ID or class object.
		 */
		public function __construct( $post ) {

			// If no Post given.
			if ( empty( $post ) ) {
				$message = sprintf( "Specified post passed to %s constructor was empty.", self::class );
				throw new \Exception( __( $message ) );
			}

			if ( is_numeric( $post ) ) {
				// Find Post
				$Post = get_post( $post );

				if ( empty( $Post ) ) {
					$message = sprintf( "Could not find Post specified by ID passed %s constructor.", self::class );
					throw new \Exception( __( $message ) );
				}

				$this->Post = $Post;
			} else if ( $post instanceof \WP_Post ) {
				// Else Post was given
				$this->Post = $post;
			}

			// Construct parent
			parent::__construct();

		}

		/**
		 * The required abstract loading function.
		 * 
		 * @return mixed The Post Meta data.
		 */
		final public function load() {
			return get_post_meta( $this->Post->ID, static::key(), $this->single );
		}

		/**
		 * Save the instance data to the database.
		 * 
		 * @return bool Whether or not the Post data was saved.
		 */
		final public function save() {
			return update_post_meta( $this->Post->ID, static::key(), $this->export() );
		}

	}

}
