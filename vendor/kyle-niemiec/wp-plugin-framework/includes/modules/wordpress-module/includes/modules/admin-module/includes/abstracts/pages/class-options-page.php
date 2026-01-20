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

use WPPF\v1_2_0\WordPress\Admin\Pages\Page;
use WPPF\v1_2_0\WordPress\Admin\Pages\Settings_Section;

if ( ! class_exists( '\WPPF\v1_2_0\WordPress\Admin\Pages\Options_Page', false ) ) {

	/**
	 * A class to abstract and automate the process of creating a page under the 'settings' menu item.
	 */
	abstract class Options_Page extends Page {

		/** @var \WPPF\v1_2_0\WordPress\Admin\Pages\Settings_Section[] The list of Sections attached to this Page. */
		private $Sections = array();

		/**
		 * The inherited function from the abstract returning the submenu ID.
		 * 
		 * @return string The ID of the submenu from the WordPress global $submenu.
		 */
		final public static function submenu_id() { return 'options-general.php'; }

		/**
		 * Construct the parent settings page.
		 */
		public function __construct() {
			parent::__construct();
		}

		/**
		 * The inherited, abstract menu item function.
		 */
		final protected static function add_menu_item() {
			add_options_page(
				__( static::page_title() ),
				__( static::menu_title() ),
				static::page_capability(),
				static::page_option_group(),
				array( static::class, 'render')
			);
		}

		/**
		 * Get all of the settings for this page and display them.
		 */
		final public static function render() {
			?>

				<form action="options.php" method="POST">
					<!-- Display nonce and hidden inputs for the Page -->
					<?php settings_fields( static::page_option_group() ); ?>
					<!-- Render the sections -->
					<?php do_settings_sections( static::page_option_group() ); ?>
					<!-- Create submit button -->
					<?php submit_button( 'Save Settings' ); ?>
				</form>

			<?php
		}

		/**
		 * Register a section with this Page.
		 * 
		 * @param \WPPF\v1_2_0\WordPress\Admin\Pages\Settings_Section $Settings_Section The Section to add to this Page.
		 */
		final public function add_section( Settings_Section $Settings_Section ) {
			$this->Sections[] = $Settings_Section;
		}

	}

}
