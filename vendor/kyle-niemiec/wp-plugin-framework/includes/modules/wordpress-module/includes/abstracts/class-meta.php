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

namespace WPPF\v1_2_0\WordPress;

defined( 'ABSPATH' ) or exit;

use WPPF\v1_2_0\Framework\Utility;

if ( ! class_exists( '\WPPF\v1_2_0\WordPress\Meta', false ) ) {

	/**
	 * An abstract class for other abstract classes to inherit regarding saving meta values in standard form in the WordPress database.
	 * User Meta and Post Meta abstract classes for example will inherently extend this class.
	 */
	abstract class Meta {

		/** @var \WPPF\v1_2_0\Meta_Schema The Schema for the Meta. */
		protected $Schema;

		/** @var string Whether the meta data is being updated as a single key or with multiple keys. */
		protected $single = true;

		/** @var mixed The current array of multiple-key meta values, or the current, unserialized value of a single key. */
		protected $data;

		/**
		 * The meta key used in the database.
		 * 
		 * @return string The meta database key.
		 */
		abstract public static function key();

		/**
		 * The function used to load meta data from the database.
		 * 
		 * @return mixed The meta data from the database.
		 */
		abstract public function load();

		/**
		 * The function used to save meta data.
		 * 
		 * @return bool The result of saving the meta to the database. Nota bene: With WordPress functions, the result may return FALSE if the data was the same.
		 */
		abstract public function save();

		/**
		 * Create a data structure that should be saved in it's own format in the database.
		 * 
		 * @return mixed The data as it should be saved into the database.
		 */
		abstract public function export();

		/**
		 * Return the Meta schema.
		 * 
		 * @return \WPPF\v1_2_0\Meta_Schema The Meta schema.
		 */
		final public function get_schema() { return $this->Schema; }

		/**
		 * Get the meta data of this instance.
		 * 
		 * @return mixed The meta data.
		 */
		final protected function get_data() { return $this->data; }

		/**
		 * Construct the Meta. Load the data from the database.
		 */
		public function __construct() {
			$this->data = $this->load();

			if ( is_array( $this->data ) ) {
				$this->import( $this->data );
			}
		}

		/**
		 * Set the meta data of this instance.
		 * 
		 * @param mixed $data The meta data.
		 */
		final protected function set_data( $data ) {

			if ( $data instanceof object ) {
				$message = "It is not recommended to serialize objects into WordPress meta values.";
				Utility::doing_it_wrong( __METHOD__, __( $message ) );
			}

			$this->data = $data;
		}

		/**
		 * Set the schema for the Meta data.
		 * 
		 * @param \WPPF\v1_2_0\Meta_Schema $Schema The Meta Value to use for the schema.
		 */
		final protected function set_schema( Meta_Schema $Schema ) {
			$this->Schema = $Schema;
		}

		/**
		 * Set properties of the class based on an associative array.
		 * 
		 * @param array The associative array with key/value pairs to import to the Meta class.
		 */
		final public function import( array $data ) {
			foreach ( $data as $property => $value ) {
				if ( property_exists( $this, $property ) ) {
					$this->{ $property } = $value;
				}
			}
		}

		/**
		 * Validate the Meta export values based on the current Schema.
		 * 
		 * @return \WP_Error|boolean Returns TRUE of the Meta is valid, otherwise returns validation errors.
		 */
		final public function validate() {
			if ( $this->Schema instanceof Meta_Schema ) {
				return $this->Schema->validate( $this->export() );
			}

			return true;
		}

	}

}
