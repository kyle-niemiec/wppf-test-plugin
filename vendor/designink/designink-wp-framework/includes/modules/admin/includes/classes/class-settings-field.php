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

namespace DesignInk\WordPress\Framework\v1_1_2\Admin\Pages;

defined( 'ABSPATH' ) or exit;

use DesignInk\WordPress\Framework\v1_1_2\Admin\Pages\Settings_Section;
use DesignInk\WordPress\Framework\v1_1_2\Utility;

if ( ! class_exists( '\DesignInk\WordPress\Framework\v1_1_2\Admin\Pages\Settings_Field', false ) ) {

	/**
	 * A class to automate the process of creating a Settings Field under a Settings Section.
	 */
	final class Settings_Field {

		/** @var \DesignInk\WordPress\Framework\v1_1_2\Admin\Pages\Settings_Section The parent Settings Section. */
		protected $Settings_Section;

		/** @var string The identifier key to use when the Settings Field has multiple values. */
		public $key;

		/** @var string The label for the Settings Field. */
		public $label;

		/** @var string The type of input used by the Settings Field. */
		public $type;

		/** @var string The value of the input used by the Settings Field. */
		public $value;

		/** @var array The default arguments to set as properties for this object. */
		private static $default_arguments = array(
			'label' => 'Default Field Label',
			'type' => 'text',
			'value' => '',
			'key' => null,
		);

		/**
		 * Construct the Settings Field.
		 * 
		 * @param \DesignInk\WordPress\Framework\v1_1_2\Admin\Pages\Settings_Section $Settings_Section The parent Settings Section.
		 * @param array $args The settings passed to this Settings Field.
		 */
		public function __construct( Settings_Section $Settings_Section, array $args ) {
			$args = Utility::guided_array_merge( self::$default_arguments, $args );

			$input_key_set = ! empty( $args['key'] );
			$section_has_multiple_fields = count( $Settings_Section->inputs ) > 1 ? true : false;

			if ( ! $input_key_set && $section_has_multiple_fields ) {
				$message = sprintf( "A %s instance was created without a key, but the parent Section has more than one Field.", self::class );
				throw new \Exception( __( $message ) );
			}

			foreach ( $args as $property => $value ) {
				$this->{$property} = $value;
			}

			$this->Settings_Section = $Settings_Section;
			$this->register();
		}

		/**
		 * Register this Settings Field and get the correct
		 */
		private function register() {
			add_settings_field(
				$this->get_field_name(),
				__( $this->label ),
				array( $this, 'render' ),
				$this->Settings_Section->get_settings_page()->page_option_group(),
				$this->Settings_Section->get_section_name(),
				$this->to_array()
			);
		}

		/**
		 * Create a reusable Field ID.
		 * 
		 * @return string An identifier for this Field.
		 */
		final public function get_field_name() {

			if ( $this->key ) {
				return sprintf( '%s_%s', $this->Settings_Section->get_section_name(), $this->key );
			} else {
				return $this->Settings_Section->get_section_name();
			}

		}

		/**
		 * Create a reusable input ID.
		 * 
		 * @return string An identifier for an input.
		 */
		final public function get_input_name() {

			if ( $this->key ) {
				return sprintf( '_%s[%s]', $this->Settings_Section->get_section_name(), $this->key );
			} else {
				return sprintf( '_%s', $this->Settings_Section->get_section_name() );
			}

		}

		/**
		 * The function which gets called to print the Field's HTML.
		 */
		final public function render() {
			switch ( $this->type ) {
				case 'text':
					printf( '<input type="text" value="%s" name="%s" />', $this->value, $this->get_input_name() );
					break;
				case 'checkbox':
					printf( '<input type="checkbox" value="yes" name="%s" %s />', $this->get_input_name(), checked( $this->value, 'yes', false ) );
					break;
			}
		}

		/**
		 * Export the public properties of this Field to an array.
		 */
		final public function to_array() {
			$export = array();

			foreach ( self::$default_arguments as $property => $value ) {
				if ( property_exists( $this, $property ) ) {
					$export[ $property ] = $value;
				}
			}

			return $export;
		}

	}

}
