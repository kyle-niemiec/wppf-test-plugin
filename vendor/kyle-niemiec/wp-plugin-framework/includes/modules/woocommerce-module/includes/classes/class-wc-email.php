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

// This file is to be triggered by the autoloader when the class \WC_Email is not found.

if ( ! class_exists( 'WC_Email', false ) ) {
	require_once WP_PLUGIN_DIR . '/woocommerce/includes/emails/class-wc-email.php';
}
