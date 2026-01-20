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

namespace WPPF\v1_2_0\Plugin;

use WPPF\v1_2_0\Framework\Module;

defined( 'ABSPATH' ) or exit;

if ( ! class_exists( '\WPPF\v1_2_0\Plugin\Staging_Detection', false ) ) {

	/**
	 * A Module which registers a site host as an expected host and throws a notification if the expected
	 * host differs from the current host and at least one Plugin has registered a notification.
	 */
	final class Staging_Detection extends Module {

		/** @var string The 'option' key to save the expected site URL to. */
		public const OPTION_KEY = 'wppf_staging_detection_expected_domain';

		/** @var boolean The static variable this class uses internally as a staging indicator. */
		private static $staging = false;

		/**
		 * Module entry point.
		 */
		final public static function construct() {
			self::check_site_url();
		}

		/**
		 * Whether or not the site host is detected to be a staging host.
		 * 
		 * @return boolean If the current site is deemed staging.
		 */
		final public static function is_staging() {
			return self::$staging;
		}

		/**
		 * Determine if the host option is set and compare it to the current host. Sets the static indicator.
		 */
		private static function check_site_url() {
			$site_host = parse_url( site_url(), PHP_URL_HOST );
			$expected_host = get_option( self::OPTION_KEY );

			// If the option does not exist, assume current site is the correct host
			if ( ! get_option( self::OPTION_KEY ) ) {
				update_option( self::OPTION_KEY, $site_host, true );
				$expected_host = $site_host;
			}

			// Check that the expected host matches the site host
			if ( $site_host !== $expected_host ) {
				self::$staging = true;
			}
		}

	}

}
