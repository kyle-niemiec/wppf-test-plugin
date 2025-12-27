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

namespace DesignInk\WordPress\Framework\v1_1_2;

defined( 'ABSPATH' ) or exit;

if ( ! class_exists( '\DesignInk\WordPress\Framework\v1_1_2\Staging_Detection', false ) ) {

	/**
	 * A Module which registers a site host as an expected host and throws a notification if the expected
	 * host differs from the current host and at least one Plugin has registered a notification.
	 */
	final class Staging_Detection extends Module {

		/** @var string The 'option' key to save the expected site URL to. */
		public const OPTION_KEY = 'designink_staging_detection_expected_domain';

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
