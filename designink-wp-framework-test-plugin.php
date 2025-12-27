<?php
/**
 * Plugin Name: DesignInk WP Framework Test Plugin
 * Plugin URI: https://designinkdigital.com/
 * Description: A demo plugin for the DesignInk WP Framework.
 * Version: 1.0.2
 * Author: DesignInk Digital
 * Author URI: https://designinkdigital.com/
 * Text Domain: wporg
 * Domain Path: /languages
 * 
 * Copyright: (c) 2008-2026, DesignInk, LLC (answers@designinkdigital.com)
 * 
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * 
 * @author    DesignInk Digital
 * @copyright Copyright (c) 2008-2026, DesignInk, LLC
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 * 
 */

defined( 'ABSPATH' ) or exit;

use DesignInk\WordPress\Framework\v1_1_2\Staging_Detection\Staging_Detection_Notice;
use DesignInk\WordPress\Framework\v1_1_2\WooCommerce_Plugin;

// Include DesignInk's framework
require_once __DIR__ . '/vendor/designink/designink-wp-framework/index.php';

if ( ! class_exists( 'DesignInk_WP_Framework_Test_Plugin', false ) ) {

	/**
	 * The wrapper class for this plugin.
	 */
	final class DesignInk_WP_Framework_Test_Plugin extends WooCommerce_Plugin {

		/**
		 * Module entry point.
		 */
		final public static function construct() {
			Staging_Detection_Notice::add_notice( __FILE__, "This message will show up if staging conditions are detected." );
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
	DesignInk_WP_Framework_Test_Plugin::instance();

}
