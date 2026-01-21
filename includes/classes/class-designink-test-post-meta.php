<?php
/**
 * WPPF Test Plugin
 *
 * Copyright (c) 2008–2026 DesignInk, LLC
 * Copyright (c) 2026 Kyle Niemiec
 *
 * This file is licensed under the GNU General Public License v3.0.
 * See the LICENSE file for details.
 */

defined( 'ABSPATH' ) or exit;

use WPPF\v1_2_0\WordPress\Meta_Schema;
use WPPF\v1_2_0\WordPress\Post_Meta;

if ( ! class_exists( 'WPPF_Test_Post_Meta', false ) ) {

	/**
	 * A class for managing the WPPF Test Post post type Meta data.
	 */
	final class WPPF_Test_Post_Meta extends Post_Meta {

		/** @var string A string value of the Post Meta. */
		public $current_string;

		/** @var string A boolean value of the Post Meta. */
		public $is_toggle_active;

		/** @var int[] A list of timestamps the Post Meta was saved. */
		public $times_saved;

		/** @var array Default Meta values. */
		private static $default_values = array(
			'current_string' => '',
			'is_toggle_active' => false,
			'times_saved' => array(),
		);

		/**
		 * The required abstraction function key()
		 * 
		 * @return string The meta key.
		 */
		final public static function key() { return '_wppf_test_post_data'; }

		/**
		 * Constructs the Post Series Meta.
		 * 
		 * @param \WP_Post $Post The parent WPPF Test Post the Meta values belong to.
		 */
		public function __construct( \WP_Post $Post ) {

			// Set Meta schema
			$this->set_schema( new Meta_Schema( 'array', array(
					'current_string'	=> new Meta_Schema(
						'string', '/^[a-zA-Z0-9 ]+$/', array( 'pattern_hint' => __( "Only letters, numbers, and spaces are allowed." ) )
					),
					'is_toggle_active'	=> new Meta_Schema( 'boolean' ),
					'times_saved'		=> new Meta_Schema( 'array', new Meta_Schema( 'integer' ) ),
				) )
			);

			if ( ! $Post ) {
				$message = sprintf( "No valid Post was passed to the %s constructor.", self::class );
				throw new \Exception( $message );
			}

			foreach ( self::$default_values as $property => $value ) {
				if ( property_exists( $this, $property ) ) {
					$this->{ $property } = $value;
				}
			}

			parent::__construct( $Post );
		}

		/**
		 * The required abstract called when saving the Meta. This function returns what is saved.
		 * 
		 * @return array The array representation of the Meta.
		 */
		final public function export() {
			$export = array();

			foreach ( self::$default_values as $property => $default_value ) {
				if ( isset( $this->{ $property } ) ) {
					$export[ $property ] = $this->{ $property };
				}

				else {
					$export[ $property ] = $default_value;
				}
			}

			return $export;
		}

	}

}
