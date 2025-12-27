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


/**
 * This file holds initialization code for loading the framework and making it globally accessible.
 */

defined( 'ABSPATH' ) or exit;

use DesignInk\WordPress\Framework\v1_1_2\Framework;
use DesignInk\WordPress\Framework\v1_1_2\Autoloader;

global $DESIGNINK_FRAMEWORKS;

/**
 * The Autoloader is really all we need to start calling things up, so fire it up if it hasn't been.
 */
if ( ! class_exists( '\DesignInk\WordPress\Framework\v1_1_2\Autoloader', false ) ) {
	require_once ( plugin_dir_path( __FILE__ ) . 'includes/classes/class-autoloader.php' );
	Autoloader::instance()->autoload_directory_recursive( __DIR__ . '/includes' );
}

/**
 * Set global function for accessing Framework instances.
 * 
 * @return \DesignInk\WordPress\Framework\v1_1_2\Framework[] The DesignInk WordPress frameworks by version.
 */
if ( ! function_exists( 'designink_frameworks' ) ) {

	$DESIGNINK_FRAMEWORKS = array();

	function designink_frameworks() {
		global $DESIGNINK_FRAMEWORKS;
		return $DESIGNINK_FRAMEWORKS;
	}

}

/**
 * Instantiate the current Framework version and add to the Frameworks list.
 */
if ( ! class_exists( '\DesignInk\WordPress\Framework\v1_1_2\Framework', false ) ) {
	$DESIGNINK_FRAMEWORKS[ Framework::get_version() ] = Framework::instance();
}

/**
 * Initialize the shadow plugin
 */
if ( ! class_exists( '\DesignInk\WordPress\Framework\v1_1_2\DesignInk_Framework_Shadow_Plugin', false ) ) {
	require_once( __DIR__ . '/designink-framework-shadow-plugin.php' );
}
