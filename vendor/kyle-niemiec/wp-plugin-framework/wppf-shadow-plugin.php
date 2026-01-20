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

namespace WPPF\v1_2_0;

defined( 'ABSPATH' ) or exit;

use WPPF\v1_2_0\WordPress\Plugin;

if ( ! class_exists( '\WPPF\v1_2_0\WPPF_Shadow_Plugin', false ) ) {

	/**
	 * The 'shadow' plugin for the framework that will control the loading of crucial modules.
	 */
	final class WPPF_Shadow_Plugin extends Plugin { }

	// Start it up
	WPPF_Shadow_Plugin::instance();

}
