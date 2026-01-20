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

use WPPF\v1_2_0\Framework\Module;

if ( ! class_exists( '\WPPF\v1_2_0\Plugin_Module', false ) ) {

	/**
	 * This Module holds specific Plugin-related functionality that doesn't belong with WordPress native abstractions.
	 */
	final class Plugin_Module extends Module { }

}
