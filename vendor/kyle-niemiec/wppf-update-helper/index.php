<?php
/**
 * WPPF Update Helper
 *
 * Copyright (c) 2008–2020 DesignInk, LLC
 * Copyright (c) 2026 Kyle Niemiec
 *
 * This file is licensed under the GNU General Public License v3.0.
 * See the LICENSE file for details.
 *
 * @package WPPF\Update_Helper
 */

namespace WPPF\Update_Helper\v1_0_1;

defined( 'ABSPATH' ) or exit;

// Require the WordPress Plugin Framework
require_once __DIR__ . '/vendor/kyle-niemiec/wp-plugin-framework/index.php';

use WPPF\v1_2_0\Framework\Module;

if ( ! class_exists( 'WPPF\Update_Helper\v1_0_1\Plugin_Update_Helper', false ) ) {

	/**
	 * This helper module helps plugins hosted using the WP Plugin Update Server connect and get the latest information about releases from their servers.
	 */
	final class Plugin_Update_Helper extends Module { }

	// Load helper
	Plugin_Update_Helper::instance();

}
