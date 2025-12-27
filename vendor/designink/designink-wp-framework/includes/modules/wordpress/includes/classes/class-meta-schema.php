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

if ( ! class_exists( '\DesignInk\WordPress\Framework\v1_1_2\Meta_Schema', false ) ) {

	/**
	 * A class for validating input patterns and types for Meta stored in the database.
	 */
	final class Meta_Schema {

		/** @var string The Meta Schema variable type expected. */
		public $type;

		/** @var mixed The regular expression pattern to validate the Meta value(s) with. */
		public $pattern;

		/** @var array Arguments passed to the Schema dealing with default values, pattern information, and allowing NULL values. */
		public $args;

		/** @var string[] The list of accepted types for validation. */
		private static $accepted_types = array(
			'array',
			'boolean',
			'float',
			'integer',
			'string',
		);

		/**
		 * Construct the Meta Schema with expressions, types, and defaults.
		 * 
		 * @param string $type		The variable type that the Meta Schema should have.
		 * @param mixed  $pattern	The regular expression to validate the Meta value by. Only the 'string',
		 * 							type is required to have a pattern. Arrays types can accept a regular
		 *	 						expressions, associative array, or Meta Schema class for the pattern.
		 * @param array  $args		Extra Arguments passed to the Schema. Can include a default value,
		 * 							extra information about the pattern, and allow NULL values.
		 */
		final public function __construct( string $type, $pattern = null, $args = array() ) {
			// Only use accepted types
			if ( ! in_array( $type, self::$accepted_types ) ) {
				$message = sprintf( "Invalid Meta value type was passed to %s. Type passed: '%s'.", self::class, $type );
				throw new \Exception( $message );
			}

			// 'String' type required to have a pattern
			if ( 'string' === $type && ! self::validate_pattern( $pattern ) ) {
				$message = sprintf( "The 'string' Meta type should have a valid regular expression passed with it in %s.", self::class );
				throw new \Exception( $message );
			}

			if ( 'array' === $type ) {

				if ( is_array( $pattern ) ) {
					foreach ( $pattern as $key => $Schema ) {
						// Throw exception if fields aren't type \DesignInk\WordPress\Framework\v1_1_2\Meta_Schema
						if ( ! ( $Schema instanceof Meta_Schema ) ) {
							$message = "When 'array' type Meta Schema has an array passed as the pattern, all pattern elements must be of type %s. %s passed to field %s";
							$format = sprintf( $message, Meta_Schema::class, gettype( $Schema ), $key );
							throw new \Exception( __( $format ) );
						}
					}
				} else if ( ! ( $pattern instanceof Meta_Schema ) && ! self::validate_pattern( $pattern ) ) {
					$message = "When a Meta Schema has an 'array' type, the pattern must be an array of Meta Schemas, a Meta Schema, or a regular expression.";
					$format = sprintf( $message,  );
					throw new \Exception( __( $format ) );
				}

			}

			$this->type = $type;
			$this->pattern = $pattern;
			$this->args = $args;
		}

		/**
		 * Validate whether or not a passed string is a valid RegExp.
		 * Credit to https://stackoverflow.com/questions/10778318/test-if-a-string-is-regex
		 * 
		 * @param string $pattern The pattern to test for RegExp similarities.
		 * 
		 * @return boolean Whether or not the pattern is a valid regular expression.
		 */
		final public static function validate_pattern( $pattern ) {
			return preg_match( '/^\/[\s\S]+\/$/', $pattern );
		}

		/**
		 * Validate the data based on this Schema.
		 * 
		 * @param mixed $data The data passed from the Meta (usually) to validate against this Schema.
		 * @param string[] $trace A list of $keys for providing recursive information.
		 * 
		 * @return \WP_Error|true Return TRUE if the data passes validation, else return a WP Error with details.
		 */
		final public function validate( $data, $trace = array() ) {
			switch ( $this->type ) {
				case 'array': return $this->validate_array( $data, $trace );
				case 'boolean': return $this->validate_boolean( $data, $trace );
				case 'float': return $this->validate_float( $data, $trace );
				case 'integer': return $this->validate_integer( $data, $trace );
				case 'string': return $this->validate_string( $data, $trace );
			}
		}

		/**
		 * Generate a default set of data based of of the Schema.
		 * 
		 * @return mixed The default data to use.
		 */
		final public function generate_default() {

			if ( isset( $this->args['default'] ) ) {
				return $this->args['default'];
			} else {
				switch ( $this->type ) {
					case 'array':
						
						if ( is_array( $this->pattern ) ) {
							$defaults = array();

							foreach ( $this->pattern as $key => $Schema ) {
								$defaults[ $key ] = $Schema->generate_default();
							}

							return $defaults;
						} else {
							return array();
						}
					
					case 'boolean': return false;
					case 'float': return 0.0;
					case 'integer': return 0;
					case 'string': return '';
					default: return null;
				}
			}

		}

		/**
		 * Private function to internally evaluate an array.
		 * 
		 * @var mixed $data The maybe array to validate using this schema.
		 * @param string[] $trace A list of $keys for providing recursive information.
		 * 
		 * @return \WP_Error|true Return TRUE if the array is valid, else return a WP Error with details.
		 */
		private function validate_array( $data, $trace ) {
			if ( ! is_array( $data ) ) {
				$message = sprintf( "Array expected for %s, %s recieved.", self::trace_string( $trace ), gettype( $data ) );
				return new \WP_Error( __( $message ) );
			}

			// NULL passed for an array returns TRUE.
			if ( $this->is_null_allow_null( $data ) ) {
				return true;
			}

			$Validation_Errors = new \WP_Error();

			if ( is_array( $this->pattern ) ) {
				// Search and verify data from fields in the pattern
				foreach ( $this->pattern as $key => $Schema ) {

					// Error if pattern $key doesn't exist in data
					if ( ! array_key_exists( $key, $data ) ) {
						$message = sprintf( 'Data missing for %s->%s.', self::trace_string( $trace ), $key );
						$Validation_Errors->add( 'validation', $message );
						continue;
					}

					$result = $Schema->validate( $data[ $key ], array_merge( $trace, array( $key ) ) );

					// Validate the data in the field.
					if ( is_wp_error( $result ) ) {
						$Validation_Errors->errors = array_merge_recursive( $Validation_Errors->errors, $result->errors );
					}
				}
			} else if ( $this->pattern instanceof Meta_Schema ) {
				// Verify all values in the array by the Meta Schema
				foreach ( $data as $key => $meta_data ) {
					$result = $this->pattern->validate( $meta_data, array_merge( $trace, array( $key ) ) );

					if ( is_wp_error( $result ) ) {
						$Validation_Errors->errors = array_merge_recursive( $Validation_Errors->errors, $result->errors );
					}
				}
			} else {
				// Verify the data directly
				foreach ( $data as $key => $meta_data ) {
					if ( ! preg_match( $this->pattern, $meta_data ) ) {
						$message = sprintf( 'Field data for %s did not match the expected pattern. "%s" recieved.', self::trace_string( $trace ), $meta_data );
						$Validation_Errors->add( 'validation', $message );
					}
				}
			}

			// Finally return
			if ( $Validation_Errors->has_errors() ) {
				return $Validation_Errors;
			} else {
				return true;
			}

		}

		/**
		 * Private function to internally evaluate a boolean.
		 * 
		 * @var mixed $data The maybe boolean to validate using this schema.
		 * @param string[] $trace A list of $keys for providing recursive information.
		 * 
		 * @return \WP_Error|true Return TRUE if the boolean is valid, else return a WP Error with details.
		 */
		private function validate_boolean( $data, $trace ) {

			if ( is_bool( $data ) || $this->is_null_allow_null( $data ) ) {
				return true;
			} else {
				$message = sprintf( 'Boolean value expected for %s, found %s.', self::trace_string( $trace ), gettype( $trace ) );
				return new \WP_Error( 'validation', $message );
			}

		}

		/**
		 * Private function to internally evaluate a float.
		 * 
		 * @var mixed $data The maybe float to validate using this schema.
		 * @param string[] $trace A list of $keys for providing recursive information.
		 * 
		 * @return \WP_Error|true Return TRUE if the float is valid, else return a WP Error with details.
		 */
		private function validate_float( $data, $trace ) {

			if ( is_float( $data ) || $this->is_null_allow_null( $data ) ) {
				return true;
			} else {
				$message = sprintf( 'Float type expected for %s, found %s.', self::trace_string( $trace ), gettype( $data ) );
				return new \WP_Error( 'validation', $message );
			}

		}

		/**
		 * Private function to internally evaluate an integer.
		 * 
		 * @var mixed $data The maybe integer to validate using this schema.
		 * @param string[] $trace A list of $keys for providing recursive information.
		 * 
		 * @return \WP_Error|true Return TRUE if the integer is valid, else return a WP Error with details.
		 */
		private function validate_integer( $data, $trace ) {
			
			if ( is_int( $data ) || $this->is_null_allow_null( $data ) ) {
				return true;
			} else {
				$message = sprintf( 'Integer type expected for %s, found %s.', self::trace_string( $trace ), gettype( $data ) );
				return new \WP_Error( 'validation', $message );
			}

		}

		/**
		 * Private function to internally evaluate a string.
		 * 
		 * @var mixed $data The maybe string to validate using this schema.
		 * @param string[] $trace A list of $keys for providing recursive information.
		 * 
		 * @return \WP_Error|true Return TRUE if the string is valid, else return a WP Error with details.
		 */
		private function validate_string( $data, $trace ) {
			// If data type is not a string
			if ( ! is_string( $data ) && ! $this->is_null_allow_null( $data ) ) {
				$message = sprintf( 'String type expected for %s, found %s.', self::trace_string( $trace ), gettype( $data ) );
				return new \WP_Error( 'validation', $message );
			}

			// If the pattern does not match
			if ( ! preg_match( $this->pattern, $data ) ) {
				$message = sprintf( 'String data for %s does not match the required pattern.', self::trace_string( $trace ) );

				if ( ! empty( $this->args['pattern_hint'] ) ) {
					$message = sprintf( '%s (%s)', $message, $this->args['pattern_hint'] );
				}

				return new \WP_Error( 'validation', $message );
			}

			return true;
		}

		/**
		 * Check if the passed $data === NULL, and if this Schema allows NULL values;
		 *
		 * @var mixed $data The maybe NULL value to check.
		 * 
		 * @return boolean Return TRUE if the $data is NULL and the NULL value is allowed.
		 */
		private function is_null_allow_null( $data ) {

			if ( array_key_exists( 'allow_null', $this->args ) ) {
				return null === $data && true === $this->args['allow_null'];
			} else {
				return false;
			}

		}

		/**
		 * Return an HTML list of validation errors.
		 * 
		 * @param \WP_Error $Error The error to pull validation error messages from.
		 * 
		 * @return string An HTML list of validation errors.
		 */
		final public static function create_error_message( \WP_Error $Error ) {
			$messages = $Error->get_error_messages( 'validation' );

			if ( empty( $messages ) ) {
				return __( 'No validation errors found.' );
			} else {
				$list_string = '';

				foreach ( $messages as $message ) {
					$list_string .= sprintf( '<li>%s</li>', $message );
				}

				$info = __( "The following validation errors were found:" );
				return sprintf( '<div><strong>%s</strong></div><ul class="errors">%s</ul>', $info, $list_string );
			}
			
		}

		/**
		 * Return a readable string from a trace array.
		 * 
		 * @param array $trace The trace array to stringify.
		 * 
		 * @return string The readable form of the trace array.
		 */
		private static function trace_string( array $trace ) {

			if ( empty( $trace ) ) {
				return 'meta';
			} else {
				return implode( '->', $trace );
			}

		}

	}

}
