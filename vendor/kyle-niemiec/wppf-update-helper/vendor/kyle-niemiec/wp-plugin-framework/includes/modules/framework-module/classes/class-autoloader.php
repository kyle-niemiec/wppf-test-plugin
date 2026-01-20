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

if ( ! class_exists( '\WPPF\v1_2_0\Framework\Autoloader', false ) ) {

	// Require Singleton abstract first thing since the Autoloader extends it and it won't be autoloaded!
	require_once ( __DIR__ . '/../abstracts/class-singleton.php' );

	/**
	 * Autoloader class for managing multiple SPL autoload directories
	 */
	final class Autoloader extends Singleton {

		/**
		 * @var array List of autoload searchable directories
		 */
		protected $autoload_directories = array();

		/**
		 * Protected constructor to prevent more than one instance of autoload directories from being created
		 */
		final protected function __construct() {

			// Check if Utility is loaded sincle this class requires it, but it also does the autoloading \o/.
			if ( ! class_exists( '\WPPF\v1_2_0\Utility', false ) ) {
				$utility_path = __DIR__ . '/../statics/class-utility.php';
				require_once ( $utility_path );
			}

			self::register_spl_autoload_function();
		}

		/**
		 * Checks if the passed directory exists, then adds the directory to the list of autoload locations if it does not exists there already.
		 * 
		 * @param string $directory The directory to be searched for potential new classes.
		 * @return bool Whether or not the directory was successfully added to the autoload array.
		 */
		final public function add_autoload_directory( string $directory ) {
			$is_directory = is_dir( $directory );

			if ( ! $is_directory ) {
				return false;
			}

			if ( ! in_array( $directory, $this->autoload_directories ) ) {
				$this->autoload_directories[] = trailingslashit( $directory );
				return true;
			}

			return false;
		}

		/**
		 * Tranverse a parent folder's structure and add the folder and all subfolders to the Autoloader
		 * 
		 * @param string $directory The absolute directory path from the plugin folder to search. Trailing slashes are not necessary and are removed.
		 * 
		 * @return array A list of all of the folders that were found.
		 */
		final public function autoload_directory_recursive( string $directory ) {
			$folders_found = array();
			$directory = rtrim( $directory, '/' );

			if ( $this->add_autoload_directory( $directory ) ) {
				$folders_found[] = $directory;
				$sub_folders = Utility::scandir( $directory, 'folders' );

				foreach ( $sub_folders as $sub_folder ) {
					$sub_folder_path = sprintf( '%s/%s', $directory, $sub_folder );
					$folders_found = array_merge( $folders_found, $this->autoload_directory_recursive( $sub_folder_path ) );
				}

			}

			return $folders_found;
		}

		/**
		 * An alias function for linking our class search function to the SPL autoload list.
		 */
		final protected static function register_spl_autoload_function() {
			spl_autoload_register( array( __CLASS__, 'search_for_class_file' ) );
		}

		/**
		 * A function which, given a fully-qualified class, will look for a matching, slugified filename using the standard WordPress "class-{$class_name}.php" structure.
		 * 
		 * @param string $class_name The name-component of a fully-qualified class to search a manicured list of default class directories for.
		 */
		final protected static function search_for_class_file( string $class ) {
			$class_name = Utility::class_basename( $class );
			$class_slug = Utility::slugify( $class_name );
			$filename = sprintf( 'class-%s.php', $class_slug );

			foreach ( self::instance()->autoload_directories as $directory ) {
				$directory = trailingslashit( $directory );
				$file = $directory . $filename;

				if ( file_exists( $file ) ) {
					$namespace = Utility::get_file_namespace( $file );

					if ( ! empty( $namespace ) && false === strpos( $class, $namespace ) ) {
						break;
					}

					require ( $file );
				}
			}
		}

	}

}
