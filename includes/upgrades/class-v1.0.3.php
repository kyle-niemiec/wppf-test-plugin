<?php
/**
 * WPPF Test Plugin
 *
 * Copyright (c) 2008–2026 DesignInk, LLC
 * Copyright (c) 2026 Kyle Niemiec
 *
 * This file is licensed under the GNU General Public License v3.0.
 * See the LICENSE file for details.
 */

namespace DFTP\Upgrades;

defined( 'ABSPATH' ) or exit;

use WPPF\v1_2_0\Framework\Utility;
use WPPF\v1_2_0\Plugin\Upgrader_Schema;

if ( ! class_exists( 'v1_0_3', false ) ) {

	/**
	 * A class to control the basic functionality for an Upgrader Schema (super descriptive, ty me).
	 */
	final class v1_0_3 extends Upgrader_Schema {

		/**
		 * Required abstract: return version.
		 */
		final public function get_version() { return '1.0.3'; }

		/**
		 * The Schema contructor.
		 */
		final public function __construct() {
			$this->add_action( array( __CLASS__, 'echo_upgrade_task' ) );
		}

		/**
		 * Echo that an upgrade task is running to the page.
		 */
		final public static function echo_upgrade_task() {
			Utility::print_debug( 'An upgrade task would be running right now.', false );
		}

	}

}
