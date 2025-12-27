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

use DesignInk\WordPress\Framework\v1_1_2\Framework;
use DesignInk\WordPress\Framework\v1_1_2\Singleton;
use DesignInk\WordPress\Framework\v1_1_2\Utility;

if ( ! class_exists( '\DesignInk\WordPress\Framework\v1_1_2\Module', false ) ) {

	/**
	 * A class to represent crucial project file system structures and bind their PHP functionalities to WordPress.
	 */
	abstract class Module extends Singleton {

		/**
		 * @var bool Whether or not the module is being included under another module.
		 */
		protected $is_submodule = false;

		/**
		 * @var bool Whether or not to load the current module.
		 */
		protected $enabled = true;

		/**
		 * @var string The default directory to locate class files under.
		 */
		protected static $includes_dir = 'includes';

		/**
		 * @var string The default directory for loading modules. (Must be separate from the rest of the includes since it must necessarily be used.).
		 */
		protected static $modules_dir = 'modules';

		/**
		 * @var array A list of subdirectories under static::$includes_dir to automatically search for autoloading.
		 */
		protected static $includes = array( 'abstracts', 'classes', 'statics', 'traits' );

		/**
		 * @var array A list of modules which have been successfully imported via the current module.
		 */
		protected $loaded_modules = array();

		/**
		 * @var array A list of autoload include folders which have been successfully registered via the current module.
		 */
		protected $loaded_includes = array();

		/**
		 * A protected constructor to ensure only singleton instances of plugins exist.
		 */
		protected function __construct( bool $is_submodule = false ) {
			$this->is_submodule = $is_submodule;

			if ( $this->enabled ) {
				$this->register_submodules();
				$this->autoload_includes();

				if ( ! $this->is_submodule ) {
					$this::construct();
					$this->construct_submodules();
				}
			}
		}
		
		/**
		 * An empty, placeholder function for overriding, this is where the module "starts" it's WordPress code.
		 */
		public static function construct() { }

		/**
		 * The function which gets called when a module is being constructed. This ensures the submodule construct sequences don't get fired when a submodule is first initialized.
		 */
		public static function submodule_construct() {
			if ( static::instance()->enabled ) {
				$instance = static::instance();
				$instance::construct();
				$instance->construct_submodules();
			}
		}

		/**
		 * The function which gets called if this module is being imported as a submodule.
		 */
		final public static function submodule_instance() {
			return static::instance( true );
		}

		/**
		 * Our special function for searching for submodule folders, loading them into the current module and PHP environment.
		 */
		final protected function register_submodules() {
			$modules_directory = sprintf( '%s%s/%s', plugin_dir_path( $this->get_class_reflection()->getFileName() ), static::$includes_dir, static::$modules_dir );

			if ( is_dir( $modules_directory ) ) {
				$files = Utility::scandir( $modules_directory );

				foreach ( $files as $file ) {
					$file_path = sprintf( '%s/%s', $modules_directory, $file );

					if ( is_dir( $file_path ) ) {
						// If loading a Module from a directory
						// Folder name must be the same as the class name
						$module_file = sprintf( 'class-%s.php', Utility::slugify( $file ) );
						$module_path = sprintf( '%s/%s', $file_path, $module_file );
						$this->import_module( $module_path );
					} else if ( is_file( $file_path ) ) {
						// If loading a Module from a single file
						$this->import_module( $file_path );
					}

				}
			}
		}

		/**
		 * Loop through all loaded modules and run their initialization functions.
		 */
		final protected function construct_submodules() {
			foreach ( $this->loaded_modules as $Module ) {
				if ( $Module instanceof Module ) {
					$Module::submodule_construct();
				}
			}
		}

		/**
		 * Given a Module class name and a file path, loads the file into the PHP environment, then checks to make sure the given class extends Module and adds it to { $this->loaded_modules }
		 * 
		 * @param string $module_path The full path/file combination pointing to the module file.
		 */
		final protected function import_module( string $module_path ) {
			$module_name = $this->load_class_file( $module_path );

			if ( false !== $module_name ) {
				$is_module = is_subclass_of( $module_name, Module::class );

				if ( $is_module ) {
					$Module = $module_name::submodule_instance();
					$this->loaded_modules[ $module_name ] = $Module;
				} else {
					$message = sprintf( "Successfully found class, '%s', but it does not appear to be a Module, make sure you are implementing %s in '%s'.", $module_name, self::class, $module_path );
					Utility::doing_it_wrong( __METHOD__, __( $message ) );
				}

			}
		}

		/**
		 * Load a class from a file and return the full class name.
		 * 
		 * @param string $file_path The path to the file containing the class.
		 * 
		 * @return string|false The fully-qualified class name or FALSE on failure.
		 */
		final protected function load_class_file( string $file_path ) {

			if ( preg_match( '/class-([a-z-\.0-9]+)\.php$/i', $file_path, $matches ) ) {
				$class_name = Utility::pascal_underscorify( $matches[1] );

				if ( is_file( $file_path ) ) {

					require_once ( $file_path );
					$namespace = Utility::get_file_namespace( $file_path );

					if ( empty( $namespace ) ) {
						$qualified_name = sprintf( '%s', $class_name );
					} else {
						$qualified_name = sprintf( '%s\%s', $namespace, $class_name );
					}

					if ( class_exists( $qualified_name ) ) {
						return $qualified_name;
					} else {
						$message = sprintf( "Loaded class file '%s', but could not locate the class %s.", $file_path, $qualified_name );
						Utility::doing_it_wrong( __METHOD__, __( $message ) );
					}

				} else {
					$message = sprintf( "Could not find class file %s.", $file_path );
					Utility::doing_it_wrong( __METHOD__, __( $message ) );
				}

			} else {
				$message = sprintf( "Given class file name does not use the correct naming conventions. Skipping file: %s", $file_path );
				Utility::doing_it_wrong( __METHOD__, __( $message ) );
			}

			return false;
		}

		/**
		 * Will iterate through the static class instance's includes and autoload any directories it finds.
		 */
		final protected function autoload_includes() {
			if ( is_array( static::$includes ) ) {
				foreach ( static::$includes as $include ) {
					$__DIR__ = dirname( $this->get_class_reflection()->getFileName() );
					$includes_path = sprintf( '%s/%s/%s', $__DIR__, static::$includes_dir, $include );
					$loaded_directories = Framework::instance()->get_autoloader()->autoload_directory_recursive( $includes_path );
					$this->loaded_includes = array_merge( $this->loaded_includes, $loaded_directories );
				}
			}
		}

		/**
		 * An alias for DesignInk\WordPress\Framework\v1_1_2\Autoloader::add_autoload_directory()
		 * 
		 * @param string $directory The directory to be searched for potential new classes.
		 * @return bool Whether or not the directory was successfully added to the autoload array.
		 */
		final protected function add_autoload_directory( string $directory ) {
			$this->loaded_includes[] = $directory;
			return designink_framework()->get_autoloader()->add_autoload_directory( $directory );
		}

		/**
		 * Get the currently loaded modules.
		 * 
		 * @return array The modules currently loaded.
		 */
		final public function get_loaded_modules() {
			return $this->loaded_modules;
		}

	}

}
