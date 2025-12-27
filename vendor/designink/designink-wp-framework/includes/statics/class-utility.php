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

if ( ! class_exists( '\DesignInk\WordPress\Framework\v1_1_2\Utility', false ) ) {

	/**
	 * Utility functions class to hold useful chunks of code we find ourselves often reusing.
	 */
	final class Utility {

		/**
		 * The basename function, except for classes; returns the name component of a fully-qualified class with namespace. Credit to markus-perl at https://coderwall.com/p/cpxxxw/php-get-class-name-without-namespace
		 * 
		 * @param string $class The fully-qualified class with path and name components.
		 * 
		 * @return string The name component of the passed class.
		 */
		final public static function class_basename( string $class ) {
			$path = explode( '\\', $class );
			return array_pop( $path );
		}

		/**
		 * A function to export object/associative array values to an associative array with matching key/value pairs and alias/value pairs.
		 * 
		 * @param object|array $instance The instance from which to export values from.
		 * @param array $properties An associative array with keys dictating which values are from $instance, optionally the values can be an array or string to export aliases of the properties.
		 * 
		 * @return array The exported property/value pairs and their respective alias/value pairs.
		 */
		final public static function export_alias_object_properties( $instance, array $properties ) {

			$export = array();

			foreach ( $properties as $property => $aliases ) {
				$current_value = '';

				if ( 'object' === gettype( $instance ) ) {
					$current_value = $instance->{ $property };
				} else if ( is_array( $instance ) ) {
					$current_value = $instance [ $property ];
				}

				if ( ! empty( $current_value ) ) {
					$export[ $property ] = $current_value;

					if ( is_array( $aliases ) ) {
						foreach ( $aliases as $alias ) {
							$export[ $alias ] = $current_value;
						}
					} else if ( 'string' === gettype( $aliases ) ) {
						$export[ $aliases ] = $current_value;
					}

				}
			}

			return $export;
		}

		/**
		 * A function which replaces word boundaries with dashes and returns the lowercase string.
		 * 
		 * @param string $subject The string to slugify.
		 * 
		 * @return string The slugified string.
		 */
		final public static function slugify( string $subject ) {
			return strtolower( self::boundary_replace( '-', $subject ) );
		}

		/**
		 * A function which converts the subject to Pascal-case then replaces word boundaries with underscores and returns the resulting string.
		 * 
		 * @param string $subject The string to convert to pascal-underscore case.
		 * 
		 * @return string The pascal-underscorified string.
		 */
		final public static function pascal_underscorify( string $subject ) {
			return ucwords( self::boundary_replace( '_', $subject ) , '_' );
		}

		/**
		 * Replace all word boundaries with a single character.
		 * 
		 * @param string $replacement The replacement boundary.
		 * @param string $subject The string to replace boundaries in.
		 */
		final public static function boundary_replace( string $replacement, string $subject ) {
			return preg_replace( '/[^a-zA-Z\d]/', $replacement, $subject );
		}

		/**
		 * A more verbose and syntactical way to convert an instantiated object's properties to an associative array.
		 * 
		 * @param object $instance The object instance to be converted.
		 * 
		 * @return array The representational associative array of the initial objects' properties.
		 */
		final public static function object_to_assoc_array( \stdClass $instance ) {
			return json_decode( json_encode( $instance ), true );
		}

		/**
		 * Splices a given $input using normal array_splice functionality, but preserving associative array key values in $replacments.
		 * 
		 * @param array &$input The array to be directly modified.
		 * @param int $index Index at where to start the array modifications.
		 * @param int $to_remove The number of elements to remove from $input strarting at $index.
		 * @param array $replacments An associative array which will be spliced into $input at $index, the keys of which will be preserved, even if numeric.
		 */
		final public static function assoc_array_splice( array &$input, int $index, int $to_remove, array $replacments = array() ) {
			$beginning = array_slice( $input, 0, $index, true );
			$end = array_slice( $input, ( $index + $to_remove ), ( count( $input ) - 1 ) );
			$input = $beginning + $replacments + $end;
		}

		/**
		 * Takes a 'guide' array, the keys of which are used to search subsequent arrays for values to merge in.
		 * 
		 * @param array $guide The associative array whose keys are used to search subsequent arrays.
		 * @param array $arrays The passed arrays with values to merge into $guide.
		 * 
		 * @return array|bool Will return the merged arrays or false if an error was encountered.
		 */
		final public static function guided_array_merge( array $guide, ...$arrays ) {
			foreach ( $arrays as $array ) {

				if ( is_array( $array ) ) {
					foreach ( array_keys( $guide ) as $key ) {
						if ( array_key_exists( $key, $array ) ) {
							$guide[ $key ] = $array[ $key ];
						}
					}
				} else {
					$message = sprintf( "Only arrays should be passed to %s. %s given.", __METHOD__, gettype( $array ) );
					throw new \Exception( __( $message ) );
				}

			}

			return $guide;
		}

		/**
		 * Open a file and read out the namespace. Credit to https://stackoverflow.com/questions/4512398/php-get-namespace-of-included-file.
		 * I could not find any information on if backslashes returned by fgets() need to be escaped, but I don't think this function is binary-safe so I'll go with "no" for now.
		 * 
		 * @param string $file The file name with path to find the php namespace in.
		 * 
		 * @return string The declared namespace or an empty string if none is declared.
		 */
		final public static function get_file_namespace( string $file ) {
			$namespace = '';
			$handle = fopen( $file, "r" );

			if ( $handle ) {
				while ( ( $line = fgets( $handle ) ) !== false ) {
					if ( strpos( $line, 'namespace' ) === 0 ) {
						$parts = explode( ' ', $line );
						$namespace = rtrim( trim( $parts[1] ), ';' );
						break;
					}
				}

				fclose( $handle );
			}

			return $namespace;
		}

		/**
		 * Print a minimized debug backtrace.
		 * 
		 * @param bool $die Whether to kill the script after printing debug.
		 */
		final public static function debug_backtrace( bool $die = true ) {
			$backtrace = array_map( function( $trace ) {

				if ( isset( $trace['class'] ) && isset( $trace['function'] ) ) {
					$function = sprintf( '%s::%s()', $trace['class'], $trace['function'] );
				} else {
					$function = $trace['function'] . '()';
				}

				return array(
					'file' => $trace['file'],
					'line' => $trace['line'],
					'function' => $function,
				);

			}, debug_backtrace() );

			array_shift( $backtrace );
			self::print_debug( $backtrace, $die );
		}

		/**
		 * One of my preferred functions for quickly displaying object debuggging information in a <PRE> tag and optionally killing the script execution.
		 * 
		 * @param mixed $subject Any variable to display in the browser.
		 * @param bool $die Whether to kill the script after printing debug.
		 */
		final public static function print_debug( $subject, bool $die = true ) {
			$debug_info = debug_backtrace()[0];
			$debug = sprintf( "<!-- %s: Line %s -->", basename( $debug_info['file'] ), $debug_info['line'] );
			printf( '<pre class="ds-print-debug">%s%s</pre>', $debug, @print_r( $subject, true ) );
			if ( $die ) { die(); }
		}

		/**
		 * A wrapper for scandir which removes . and .. as values from the return array, and can optionally return only files or folders.
		 * 
		 * scandir takes a folder path and returns an array with all of the file names inside of that folder, without the path. scandir
		 * includes . and .. as results.
		 * 
		 * @param string $dir The directory to scan for items.
		 * @param string $items Which items to look for in the directory. Set this option to 'files', 'folders', or 'both'. Default value is 'both'
		 * 
		 * @return array The list of all the files and/or folders found in the directory. Will return an empty array and trigger an error if $dir is not found.
		 */
		final public static function scandir( string $dir, string $type = 'both' ) {

			if ( ! is_dir( $dir ) ) {
				$message = sprintf( "The path provided to %s is not a valid directory. Recieved (%s)", __METHOD__, $dir );
				Utility::doing_it_wrong( __METHOD__, $message );
				return array();
			}

			$files = scandir( $dir );
			
			// Find the . and .. listings if they exist and remove them.
			$current_directory_index = array_search( '.', $files );

			if ( $current_directory_index !== false ) {
				array_splice( $files, $current_directory_index, 1 );
			}

			$parent_directory_index = array_search( '..', $files );

			if ( $parent_directory_index !== false ) {
				array_splice( $files, $parent_directory_index, 1 );
			}

			// Then maybe only filter for files for folders.
			if ( 'files' === $type || 'folders' === $type ) {
				self::scandir_filter( $files, $dir, $type );
			}

			return $files;
			
		}

		/**
		 * Take in an array of potential files and folders and filter them accordingly.
		 * 
		 * @param array $files The array of files and folders to filter.
		 * @param string $path The path where one may find these files provided.
		 * @param string $type The type of filtering to do. Can be 'files' or 'folders'. Actually, it can be anything, but the function won't work if it isn't, and the compiler will throw an error if you don't pass in a string so... your loss.
		 */
		final public static function scandir_filter( array &$files, string $path, string $type ) {
			$path = trailingslashit( $path );
			$filter_function = null;

			// Select filter
			if ( 'files' === $type ) {
				$filter_function = 'is_file';
			} else if ( 'folders' === $type ) {
				$filter_function = 'is_dir';
			} else {
				return $files;
			}

			// Loop files
			foreach ( $files as $index => $file ) {
				if ( ! call_user_func( $filter_function, $path.$file ) ) {
					unset( $files[ $index ] );
				}
			}

			$files = array_values( $files );
		}

		/**
		 * Our own, non-private version of _doing_it_wrong(), credit to https://wordpress.stackexchange.com/questions/238672/what-is-an-alternative-method-to-the-wordpress-private-doing-it-wrong-functio
		 * 
		 * @param string $method	The function or method currently executing.
		 * @param string $message	The error message to display.
		 */
		final public static function doing_it_wrong( string $method, string $message ) {
			if ( WP_DEBUG && apply_filters( 'doing_it_wrong_trigger_error', true ) ) {
				trigger_error( sprintf( '<pre>An error occurred in %1$s. <br /> %2$s <br /> Framework Version: %3$s</pre>', $method, $message, Framework::VERSION ), E_USER_WARNING );
			}
		}

	}

}
