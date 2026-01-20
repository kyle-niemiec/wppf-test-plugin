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

namespace WPPF\v1_2_0\WordPress\Admin\Pages;

defined( 'ABSPATH' ) or exit;

use WPPF\v1_2_0\Framework\Utility;
use WPPF\v1_2_0\WordPress\Admin\Pages\Settings_Field;

if ( ! class_exists( '\WPPF\v1_2_0\WordPress\Admin\Pages\Settings_Section', false ) ) {

	/**
	 * A class to automate the process of creating a section for a page under the 'settings' menu item.
	 */
	final class Settings_Section {

		/** @var \WPPF\v1_2_0\WordPress\Admin\Pages\Options_Page The settings page parent instance */
		protected $Settings_Page;

		/** @var \WPPF\v1_2_0\WordPress\Admin\Pages\Settings_Field[] Fields registered to this Section. */
		protected $Settings_Fields;

		/** @var string The section identifier for the settings section. */
		public $section;

		/** @var string The label for the settings section. */
		public $label;

		/** @var string The description for the settings section. */
		public $description;

		/** @var array The inputs for the settings section. */
		public $inputs;

		/**
		 * Return the Settings Page instance.
		 * 
		 * @return \WPPF\v1_2_0\WordPress\Admin\Pages\Options_Page $Settings_Page The Settings Page instance.
		 */
		final public function get_settings_page() { return $this->Settings_Page; }

		/**
		 * Return the Settings Field instances.
		 * 
		 * @return \WPPF\v1_2_0\WordPress\Admin\Pages\Settings_Field[] $Settings_Fields The Settings Fields in this Section instance.
		 */
		final public function get_settings_fields() { return $this->Settings_Fields; }

		/** @var array The default arguments to set as properties of this object. Overridable by inherited classes. */
		private static $default_arguments = array(
			'label' => 'Default Section Label',
			'description' => 'Default section description.',
			'inputs' => array(),
		);

		/**
		 * Set the initial values for the Settings Section.
		 * 
		 * @param \WPPF\v1_2_0\WordPress\Admin\Pages\Options_Page $Settings_Page The parent Settings Page that these settings belong to.
		 * @param string $section_name The section identifier.
		 * @param array $args The optional arguments to pass to the Section.
		 */
		public function __construct( Options_Page &$Settings_Page, string $section_name, array $args ) {

			if ( empty( $section_name ) ) {
				$message = sprintf( "No section identifier was specified to the %s constructor.", self::class );
				throw new \Exception( __( $message ) );
			}

			$args = Utility::guided_array_merge( static::$default_arguments, $args );

			foreach ( $args as $property => $value ) {
				$this->{$property} = $value;
			}

			$this->Settings_Page = $Settings_Page;
			$this->section = $section_name;
			$this->register();
			$this->load_input_options();
		}

		/**
		 * Register the section settings and add the section to the page.
		 */
		private function register() {
			// N.B. This is where the option name is registered in the database.
			register_setting( $this->Settings_Page->page_option_group(), $this->get_section_option_name() );

			add_settings_section(
				$this->get_section_name(),
				__( $this->label ),
				array( $this, 'render' ),
				$this->Settings_Page->page_option_group()
			);
		}

		/**
		 * Load option data from the database and set the values of the inputs.
		 */
		private function load_input_options() {
			$options = $this->load_option();

			foreach ( $this->inputs as $input ) {

				if ( is_array( $options ) && key_exists( $input['key'], $options ) ) {
					// Load input value if options is an array
					$input['value'] = $options[ $input['key'] ];
				} else if ( 'string' === gettype( $options ) ) {
					// Load input value if options is a single string
					$input['value'] = $options;
				}

				$this->Settings_Fields[] = new Settings_Field( $this, $input );
			}

		}

		/**
		 * Create a reusable section ID.
		 * 
		 * @return string An identifier for this section.
		 */
		final public function get_section_name() {
			return sprintf( '%s_%s', $this->Settings_Page->page_option_group(), $this->section );
		}

		/**
		 * Generate the key to be used in the options table in the database.
		 * 
		 * @return string An identifier for this section.
		 */
		final public function get_section_option_name() {
			return sprintf( '_%s', $this->get_section_name() );
		}

		/**
		 * Load and return the option associated with this section.
		 * 
		 * @return mixed The option data. Returns false if the data does not exist.
		 */
		final public function load_option() {
			return get_option( $this->get_section_option_name(), false );
		}

		/**
		 * The template for displaying data about the section.
		 * 
		 * @param array $args Arguments passed to the render function.
		 */
		final public function render( array $args ) {
			?>

				<p id="<?php echo $args['id']; ?>"><?php echo $this->description ?></p>

			<?php
		}

	}

}
