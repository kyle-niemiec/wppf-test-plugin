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

namespace DFTP\Upgrades;

defined( 'ABSPATH' ) or exit;

use DesignInk\WordPress\Framework\v1_1_2\Utility;
use DesignInk\WordPress\Framework\v1_1_2\Plugin\Upgrader_Schema;

if ( ! class_exists( 'v1_0_2', false ) ) {

	/**
	 * A class to control the basic functionality for an Upgrader Schema (super descriptive, ty me).
	 */
	final class v1_0_2 extends Upgrader_Schema {

		/**
		 * Required abstract: return version.
		 */
		final public function get_version() { return '1.0.2'; }

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
