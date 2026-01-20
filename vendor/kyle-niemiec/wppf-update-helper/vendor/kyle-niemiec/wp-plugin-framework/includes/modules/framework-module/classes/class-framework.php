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

namespace WPPF\v1_2_0\Framework;

defined( 'ABSPATH' ) or exit;

use WPPF\v1_2_0\Framework\Autoloader;
use WPPF\v1_2_0\Framework\Singleton;
use WPPF\v1_2_0\WordPress\Plugin;

if ( ! class_exists( '\WPPF\v1_2_0\Framework\Framework', false ) ) {

	/**
	 * The wrappper class for a proprietary set of code which seeks to facilitate WordPress development and encourage use of the documented coding standards.
	 * (https://make.wordpress.org/core/handbook/best-practices/coding-standards/php/)
	 */
	final class Framework extends Singleton {

		/**
		 * @var string VERSION constant for compatibility.
		 */
		const VERSION = '1.2.0';

		/**
		 * @var \WPPF\v1_2_0\Autoloader Class autoloader instance.
		 */
		protected $autoloader;

		/**
		 * @var array List of initialized plugins using the framework.
		 */
		protected $plugins = array();

		/**
		 * Return the current framework verion.
		 * 
		 * @return string Framework version.
		 */
		final public static function get_version() { return self::VERSION; }

		/**
		 * Protected constructor to prevent multiple Framework instances from being created. Instantiate the Shadow Plugin.
		 */
		final protected function __construct() {
			$this->autoloader = Autoloader::instance();
		}

		/**
		 * Return the WPPF\v1_2_0\Autoloader instance.
		 * 
		 * @return \WPPF\v1_2_0\Autoloader The instance.
		 */
		final public function get_autoloader() {
			return $this->autoloader;
		}

		/**
		 * Add a plugin instance to the list of registered plugins.
		 * 
		 * @param \WPPF\v1_2_0 $plugin The plugin to register.
		 */
		final public function register_plugin( Plugin $Plugin ) {
			$class_name = $Plugin->get_class_reflection()->getName();

			if ( ! array_search( $Plugin, $this->plugins ) ) {
				$this->plugins[ $class_name ] = $Plugin;
			} else {
				$message = sprintf( "Tring to register plugin to WPPF that has already been registered. (Tried to register: %s)", $class_name );
				Utility::doing_it_wrong( __METHOD__, $message );
			}

		}

	}

}
