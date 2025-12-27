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

namespace DesignInk\WordPress\Framework\v1_1_2;

defined( 'ABSPATH' ) or exit;

use DesignInk\WordPress\Framework\v1_1_2\Meta;

if ( ! class_exists( '\DesignInk\WordPress\Framework\v1_1_2\User_Meta', false ) ) {

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
