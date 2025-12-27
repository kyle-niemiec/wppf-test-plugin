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

namespace DesignInk\WordPress\Framework\v1_1_2\Action_Scheduler;

defined( 'ABSPATH' ) or exit;

use DesignInk\WordPress\Framework\v1_1_2\Utility;

if ( ! class_exists( '\DesignInk\WordPress\Framework\v1_1_2\Action_Scheduler\Action', false ) ) {

	/**
	 * A class to represent a callable Action assigned to a Timer and all of it's properties.
	 */
	final class Action {

		/**
		 * @var callable	$action			The action function which will be called.
		 * @var array		$callable_args	The arguments which will be passed to the callable.
		 * @var string		$id				The ID of the Action, unique to the timer.
		 */
		public $action, $callable_args, $id;

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
