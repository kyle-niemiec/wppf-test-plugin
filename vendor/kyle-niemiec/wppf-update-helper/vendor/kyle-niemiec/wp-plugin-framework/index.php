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

/**
 * This file holds initialization code for loading the framework and making it globally accessible.
 */

defined( 'ABSPATH' ) or exit;

use WPPF\v1_2_0\Framework\Framework;
use WPPF\v1_2_0\Framework\Autoloader;

global $WPPF_FRAMEWORKS;

/**
 * The Autoloader is really all we need to start calling things up, so fire it up if it hasn't been.
 */
if ( ! class_exists( '\WPPF\v1_2_0\Autoloader', false ) ) {
	require_once ( plugin_dir_path( __FILE__ ) . 'includes/modules/framework-module/classes/class-autoloader.php' );
	Autoloader::instance()->autoload_directory_recursive( __DIR__ . '/includes' );
}

/**
 * Set global function for accessing Framework instances.
 * 
 * @return \WPPF\v1_2_0\Framework[] The WordPress plugin frameworks by version.
 */
if ( ! function_exists( 'wppf_frameworks' ) ) {

	$WPPF_FRAMEWORKS = array();

	function wppf_frameworks() {
		global $WPPF_FRAMEWORKS;
		return $WPPF_FRAMEWORKS;
	}

}

/**
 * Instantiate the current Framework version and add to the Frameworks list.
 */
if ( ! class_exists( '\WPPF\v1_2_0\Framework', false ) ) {
	$WPPF_FRAMEWORKS[ Framework::get_version() ] = Framework::instance();
}

/**
 * Initialize the shadow plugin
 */
if ( ! class_exists( '\WPPF\v1_2_0\WPPF_Shadow_Plugin', false ) ) {
	require_once( __DIR__ . '/wppf-shadow-plugin.php' );
}
