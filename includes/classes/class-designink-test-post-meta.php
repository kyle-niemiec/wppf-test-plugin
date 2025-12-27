<?php
/**
 * DesignInk WP Framework Test Plugin
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
 * @author    DesignInk Digital
 * @copyright Copyright (c) 2008-2026, DesignInk, LLC
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

defined( 'ABSPATH' ) or exit;

use DesignInk\WordPress\Framework\v1_1_2\Meta_Schema;
use DesignInk\WordPress\Framework\v1_1_2\Post_Meta;

if ( ! class_exists( 'DesignInk_Test_Post_Meta', false ) ) {

	/**
	 * A class for managing the DesignInk Test Post post type Meta data.
	 */
	final class DesignInk_Test_Post_Meta extends Post_Meta {

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
		 * The required abstraction function meta_key()
		 * 
		 * @return string The meta key.
		 */
		final public static function key() { return '_designink_test_post_data'; }

		/**
		 * Constructs the Post Series Meta.
		 * 
		 * @param \WP_Post $Post The parent DesignInk Test Post the Meta values belong to.
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
