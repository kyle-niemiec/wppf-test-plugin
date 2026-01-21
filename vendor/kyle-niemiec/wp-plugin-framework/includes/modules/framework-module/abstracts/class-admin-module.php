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

use WPPF\v1_2_0\WordPress\Plugin;
use WPPF\v1_2_0\Framework\Utility;

if ( ! class_exists( '\WPPF\v1_2_0\Framework\Admin_Module', false ) ) {

	/**
	 * A class to represent and help deal with common plugin admin functionality.
	 */
	abstract class Admin_Module extends Plugin {

		/** @var string The default directory for loading screens. (Will be a subfolder of includes) */
		protected static $screens_dir = 'screens';

		/**
		 * @var array An overridden list of subdirectories under static::$includes_dir to automatically search for autoloading.
		 */
		protected static $includes = array( 'statics', 'abstractions', 'classes', 'meta-boxes' );

		/**
		 * @var array A list of screens which are initialized for the admin panel.
		 */
		protected $loaded_screens = array();

		/**
		 * A protected constructor to ensure only singleton instances exist.
		 */
		final protected function __construct() {
			// Code print sugar since admin modules don't have admin modules and this will always be NULL.
			unset( $this->admin_module );
			// TRUE because an admin module is always a submodule.
			parent::__construct( true );
			// Register and construct screens after Modules have been registered and constructed.
			$this->register_available_screens();
		}

		/**
		 * Search for {@see \WPPF\v1_2_0\WordPress\Post_Type} classes in the Plugin { static::$post_types_dir } and register them.
		 */
		private function register_available_screens() {
			$reflection = $this->get_class_reflection();
			$screens_dir = sprintf( '%s%s/%s/', plugin_dir_path( $reflection->getFileName() ), static::$includes_dir, static::$screens_dir );

			if ( is_dir( $screens_dir ) ) {
				$folder_files = Utility::scandir( $screens_dir, 'files' );

				foreach ( $folder_files as $file ) {
					if ( preg_match( '/class-([a-z-]+)\.php/i', $file, $matches ) ) {
						require_once ( $screens_dir . $file );
						$namespace = Utility::get_file_namespace( $screens_dir . $file );

						if ( $namespace ) {
							$screen_name = sprintf(
								'\%s\%s',
								$namespace,
								Utility::pascal_underscorify( $matches[1] )
							);
						} else {
							$screen_name = Utility::pascal_underscorify( $matches[1] );
						}

						if ( class_exists( $screen_name ) && is_subclass_of( $screen_name, 'WPPF\v1_2_0\WordPress\Admin\Screens' ) ) {
							$screen_name::construct();
							$this->loaded_screens[] = $screen_name;
						}
					}
				}
			}
		}

	}

}
