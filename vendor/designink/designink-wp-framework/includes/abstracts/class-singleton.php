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

if ( ! class_exists( '\DesignInk\WordPress\Framework\v1_1_2\Singleton', false ) ) {

	/**
	 * An abstraction to apply to singleton instances for uniform initialization.
	 */
	abstract class Singleton {

		/**
		 * @var self[] List of singleton instances to prevent multiple SPL autoload functions from being registered. DO NOT TOUCH WITH YOUR INHERITANCE!
		 */
		protected static $_instances = array();

		/**
		 * Require a protected constructor so existing instances can be checked against.
		 */
		abstract protected function __construct();

		/**
		 * Maybe initialize singleton instance and return the instance.
		 * 
		 * @param mixed $args The arguments to pass to the singleton constructor if constructing.
		 * 
		 * @return static This instance.
		 */
		final public static function instance( $args = null ) {
			$class_name = static::class;

			if ( ! array_key_exists( $class_name, self::$_instances ) ) {

				if ( isset( $args ) ) {
					self::$_instances[ $class_name ] = new static( $args );
				} else {
					self::$_instances[ $class_name ] = new static();
				}

			}

			return self::$_instances[ $class_name ];
		}

		/**
		 * Return a copy of the instances array.
		 * 
		 * @return array A list of Singleton instances that have been instantiated.
		 */
		final public static function get_instances() {
			return self::$_instances;
		}

		/**
		 * A short one-liner tool to get a ReflectionClass so we can get runtime info for the static class instance and not this abstract.
		 * 
		 * @return \ReflectionClass The runtime instance of this class being evalutated.
		 */
		final protected function get_class_reflection() {
			return new \ReflectionClass( $this );
		}

	}

}
