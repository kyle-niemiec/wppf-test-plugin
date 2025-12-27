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

use DesignInk\WordPress\Framework\v1_1_2\Utility;

if ( ! class_exists( '\DesignInk\WordPress\Framework\v1_1_2\Meta', false ) ) {

	/**
	 * An abstract class for other abstract classes to inherit regarding saving meta values in standard form in the WordPress database.
	 * User Meta and Post Meta abstract classes for example will inherently extend this class.
	 */
	abstract class Meta {

		/** @var \DesignInk\WordPress\Framework\v1_1_2\Meta_Schema The Schema for the Meta. */
		protected $Schema;

		/** @var string Whether the meta data is being updated as a single key or with multiple keys. */
		protected $single = true;

		/** @var mixed The current array of multiple-key meta values, or the current, unseriealized value of a single key. */
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
		 * @return \DesignInk\WordPress\Framework\v1_1_2\Meta_Schema The Meta schema.
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
		 * @param \DesignInk\WordPress\Framework\v1_1_2\Meta_Schema $Schema The Meta Value to use for the schema.
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
