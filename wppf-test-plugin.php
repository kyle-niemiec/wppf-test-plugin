<?php
/**
 * Plugin Name: WPPF Test Plugin
 * Plugin URI: https://github.com/kyle-niemiec/wppf-test-plugin
 * Description: A demo plugin for the WordPress Plugin Framework.
 * Version: 1.0.4
 * Author: Kyle Niemiec
 * Author URI: https://codeflower.io/
 * Text Domain: wporg
 * Domain Path: /languages
 * 
 * Copyright: (c) 2008-2026, DesignInk, LLC (answers@designinkdigital.com)
 * Copyright: (c) 2026, Kyle Niemiec (kyle@codeflower.io)
 * 
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

defined( 'ABSPATH' ) or exit;

use WPPF\Update_Helper\v1_0_2\Plugin_Update_List;
use WPPF\v1_2_0\Plugin\Staging_Detection\Staging_Detection_Notice;
use WPPF\v1_2_0\WooCommerce\WooCommerce_Plugin;

// Require the WordPress Plugin Framework
require_once __DIR__ . '/vendor/kyle-niemiec/wp-plugin-framework/index.php';
require_once __DIR__ . '/vendor/kyle-niemiec/wppf-update-helper/index.php';

if ( ! class_exists( 'WPPF_Test_Plugin', false ) ) {

	/**
	 * The wrapper class for this plugin.
	 */
	final class WPPF_Test_Plugin extends WooCommerce_Plugin {

		/**
		 * Module entry point.
		 */
		final public static function construct() {
			Staging_Detection_Notice::add_notice( __FILE__, "This message will show up if staging conditions are detected." );
			Plugin_Update_List::add_plugin( 'wppf-test-plugin', 'https://codeflower.io/' );
		}

		/**
		 * The inherited function called on plugin activation.
		 */
		final public static function activation() { }

		/**
		 * The inherited function called on plugin deactivation.
		 */
		final public static function deactivation() { }
		
	}

	// Fire it up
	WPPF_Test_Plugin::instance();

}
