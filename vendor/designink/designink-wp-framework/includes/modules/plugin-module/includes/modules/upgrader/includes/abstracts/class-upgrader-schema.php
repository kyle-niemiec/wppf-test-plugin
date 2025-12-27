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

use DesignInk\WordPress\Framework\v1_1_2\Utility;

if ( ! class_exists( '\DesignInk\WordPress\Framework\v1_1_2\Plugin\Upgrader_Schema', false ) ) {

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
