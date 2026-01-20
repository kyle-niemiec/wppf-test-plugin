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

if ( ! class_exists( '\WPPF\v1_2_0\WordPress\User_Meta', false ) ) {

	/**
	 * An abstract for dealing with User Meta.
	 */
	abstract class User_Meta extends Meta {

		/** @var \WP_User The User that this Meta belongs to. */
		protected $User;

		/**
		 * Construct construct the User Meta, instantiate User if necessary, call parent constructor.
		 * 
		 * @param \WP_User|int $user The User ID or class object.
		 */
		public function __construct( $user ) {

			// If no User given.
			if ( empty( $user ) ) {
				$message = sprintf( "Specified User passed to %s constructor was empty.", self::class );
				throw new \Exception( __( $message ) );
			}

			if ( is_numeric( $user ) ) {
				// Find User
				$User = get_user_by( 'ID', $user );

				if ( ! $User ) {
					$message = sprintf( "Could not find User specified by ID passed %s constructor.", self::class );
					throw new \Exception( __( $message ) );
				}

				$this->User = $User;
			} else if ( $user instanceof \WP_User ) {
				// Else User was given
				$this->User = $user;
			}

			// Construct parent
			parent::__construct();

		}

		/**
		 * The required abstract loading function.
		 * 
		 * @return mixed Will be an array if $this->single is false. Will be value of meta data field if $this->single is true.
		 */
		final public function load() {
			return get_user_meta( $this->User->ID, static::key(), $this->single );
		}

		/**
		 * Save the instance data to the database.
		 * 
		 * @return bool Whether or not the User data was saved.
		 */
		final public function save() {
			return update_user_meta( $this->User->ID, static::key(), $this->export() );
		}

	}

}
