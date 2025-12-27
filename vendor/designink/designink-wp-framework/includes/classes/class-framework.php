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

use DesignInk\WordPress\Framework\v1_1_2\Autoloader;
use DesignInk\WordPress\Framework\v1_1_2\Singleton;

if ( ! class_exists( '\DesignInk\WordPress\Framework\v1_1_2\Framework', false ) ) {

	/**
	 * The wrappper class for a proprietary set of code which seeks to facilitate WordPress development and encourage use of the documented coding standards.
	 * (https://make.wordpress.org/core/handbook/best-practices/coding-standards/php/)
	 */
	final class Framework extends Singleton {

		/**
		 * @var string VERSION constant for compatibility.
		 */
		const VERSION = '1.1.2';

		/**
		 * @var \DesignInk\WordPress\Framework\v1_1_2\Autoloader Class autoloader instance.
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
		 * Return the DesignInk\WordPress\Framework\v1_1_2\Autoloader instance.
		 * 
		 * @return \DesignInk\WordPress\Framework\v1_1_2\Autoloader The instance.
		 */
		final public function get_autoloader() {
			return $this->autoloader;
		}

		/**
		 * Add a plugin instance to the list of registered plugins.
		 * 
		 * @param \DesignInk\WordPress\Framework\v1_1_2 $plugin The plugin to register.
		 */
		final public function register_plugin( Plugin $Plugin ) {
			$class_name = $Plugin->get_class_reflection()->getName();

			if ( ! array_search( $Plugin, $this->plugins ) ) {
				$this->plugins[ $class_name ] = $Plugin;
			} else {
				$message = sprintf( "Tring to register plugin to the DesignInk Framework that has already been registered. (Tried to register: %s)", $class_name );
				Utility::doing_it_wrong( __METHOD__, $message );
			}

		}

	}

}
