<?php
/**
 * Plugin Name: {{plugin_name}}
 * Plugin URI: {{plugin_uri}}
 * Description: {{description}}
 * Version: 1.0.0
 * Author: {{author}}
 * Author URI: {{author_uri}}
 */

defined( 'ABSPATH' ) or exit;

use WPPF\v1_2_0\WordPress\Plugin;

// Include the framework
require_once __DIR__ . '/vendor/kyle-niemiec/wp-plugin-framework/index.php';

if ( ! class_exists( '{{plugin_class_name}}', false ) ) {

	/**
	 * The wrapper class for this plugin.
	 */
	final class {{plugin_class_name}} extends Plugin { }

	// Fire it up
	{{plugin_class_name}}::instance();

}