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

namespace WPPF\v1_2_0\Plugin\Action_Scheduler;

defined( 'ABSPATH' ) or exit;

use WPPF\v1_2_0\Framework\Utility;

if ( ! class_exists( '\WPPF\v1_2_0\Plugin\Action_Scheduler\Action', false ) ) {

	/**
	 * A class to represent a callable Action assigned to a Timer and all of it's properties.
	 */
	final class Action {

		/** @var callable $action The action function which will be called. */
		public $action;

		/** @var array $callable_args The arguments which will be passed to the callable. */
		public $callable_args;

		/** @var string $id The ID of the Action, unique to the timer. */
		public $id;

		/** @var array The default properties that belong to Actions. */
		private static $default_arguments = array(
			'action' => array(),
			'callable_args' => array(),
			'id' => null,
		);

		/**
		 * Construct this action using the provided and default arguments.
		 */
		public function __construct( string $action_id, array $action ) {

			if ( empty( $action_id ) ) {
				$message = sprintf( "Tried to create a %s without an ID.", self::class );
				throw new \Exception( __( $message ) );
			}

			if ( ! isset( $action['action'] ) ) {
				$message = sprintf( "Tried to create a %s without an action.", self::class );
				throw new \Exception( __( $message ) );
			}

			$action = Utility::guided_array_merge( self::$default_arguments, $action );

			foreach ( $action as $property => $value ) {
				$this->{ $property } = $value;
			}

			$this->id = $action_id;
		}

		/**
		 * Do the action callable. Do the roar.
		 * 
		 * @return mixed Returns the result of the function called.
		 */
		final public function do() {
			if ( is_callable( $this->action ) ) {
				return call_user_func_array( $this->action, $this->callable_args );
			}
		}

		/**
		 * Return the action as an associative array.
		 * 
		 * @return array This action represented as an associative array.
		 */
		final public function to_array() {
			$action_export = array();

			foreach ( self::$default_arguments as $property => $value ) {

				if ( property_exists( $this, $property ) ) {
					$action_export[ $property ] = $this->{ $property };
				} else {
					$action_export[ $property ] = $value;
				}

			}

			return $action_export;
		}

	}

}
