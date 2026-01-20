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

namespace WPPF\v1_2_0\Plugin;

defined( 'ABSPATH' ) or exit;

use WPPF\v1_2_0\Framework\Utility;

if ( ! class_exists( '\WPPF\v1_2_0\Plugin\Upgrader_Schema', false ) ) {

	/**
	 * A class to control the basic functionality for an Upgrader Schema (super descriptive, ty me).
	 */
	abstract class Upgrader_Schema {

		/** @var array The actions stored in this upgrade Schema. */
		protected $actions = array();

		/**
		 * Abstract function to return the version coresponding to the upgrade schema.
		 */
		abstract public function get_version();

		/**
		 * Return the Schema actions.
		 * 
		 * @return array The Schema actions.
		 */
		final public function get_actions() { return $this->actions; }

		/**
		 * Add an action to the Schema.
		 * 
		 * @param mixed $action A callable argument.
		 * @param int $priority The priority of the action. Default: 10.
		 * 
		 * @return bool Whether or not the action was added.
		 */
		final public function add_action( $action, int $priority = 10 ) {

			if ( is_callable( $action ) ) {
				array_push( $this->actions, array( 'action' => $action, 'priority' => $priority ) );
			} else {
				Utility::doing_it_wrong( __METHOD__, __( "The provided action is not callable." ) );
			}

		}

	}

}
