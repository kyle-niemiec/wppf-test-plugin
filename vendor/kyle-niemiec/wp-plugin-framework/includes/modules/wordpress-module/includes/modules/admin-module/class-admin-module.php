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

namespace WPPF\v1_2_0\WordPress;

defined( 'ABSPATH' ) or exit;

use WPPF\v1_2_0\Framework\Module;

if ( ! class_exists( '\WPPF\v1_2_0\WordPress\Admin_Module', false ) ) {

	/**
	 * This Module is not a Module which is necessarily loaded only when TRUE === is_admin(), rather this namespace simply
	 * holds all of the administrative abstractions for WordPress.
	 */
	final class Admin_Module extends Module { }

}
