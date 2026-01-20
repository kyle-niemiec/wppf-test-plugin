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

use WPPF\v1_2_0\Framework\Module;
use WPPF\v1_2_0\Framework\Utility;

if ( ! class_exists( 'WPPF\Update_Helper\v1_0_1\Plugins_Api_Controller', false ) ) {

	/**
	 * This class controls incoming data for custom plugins API information.
	 */
	final class Plugins_Api_Controller extends Module {

		/** @var string The API URL to find 'plugins_api' information at. */
		const PLUGINS_API_QUERY_PATH = '/wp-json/wppf/api/plugin-updates/plugins-api';

		/**
		 * Module entry point
		 */
		final public static function construct() {
			if ( is_admin() ) {
				add_filter( 'plugins_api', array( __CLASS__, '_plugins_api' ), 10, 3 );
			}
		}

		/**
		 * The WordPress 'plugins_api' filter hook.
		 * 
		 * @param bool $result The default return value for the filter. Defaults to FALSE in wp-admin/includes/plugin-install.php for plugins_api().
		 * @param string $action The action 'plugins_api' is trying to take.
		 * @param \stdClass $args The arguments passed to plugins_api(), filtered through the 'plugins_api_args' filter.
		 * 
		 * @return false|\stdClass Return FALSE to search the WordPress.org API for info, or return an object with plugin info (external property will be marked TRUE).
		 */
		final public static function _plugins_api( bool $result, string $action, \stdClass $args ) {
			return self::retrieve_plugins_api_info( $action, $args );
		}

		/**
		 * This function hooks into the 'plugins_api' filter, but only listens for the 'plugin_information' action. This is the information
		 * which displays about the plugin in the admin panel for updates and general info.
		 * 
		 * @param string $action The action 'plugins_api' is trying to take.
		 * @param \stdClass $args The arguments passed to plugins_api(), filtered through the 'plugins_api_args' filter.
		 * 
		 * @return false|\stdClass Return FALSE to search the WordPress.org API for info, or return an object with plugin info (external property will be marked TRUE).
		 */
		private static function retrieve_plugins_api_info( string $action, \stdClass $args ) {
			if ( 'plugin_information' === $action ) {
				$slugs = Plugin_Update_List::get_list();
				$slug = $args->slug;

				if ( key_exists( $slug, $slugs ) ) {
					$domain = $slugs[ $slug ];
					$plugin_info = self::get_remote_plugins_api_info( $domain, $slug );
					return $plugin_info;
				}
			}

			return false;
		}

		/**
		 * Using a provided hosting domain, search the api information path for 'plugins_api' info on a specified plugin.
		 * 
		 * @param string $domain The domain to get hosted plugin API information for.
		 * @param string $plugin The plugin to get information about.
		 * 
		 * @return false|\stdClass The plugin information or FALSE if not found.
		 */
		private static function get_remote_plugins_api_info( string $domain, string $plugin ) {
			$url = sprintf( '%s%s?plugin=%s', $domain, self::PLUGINS_API_QUERY_PATH, $plugin );
			$request = wp_remote_get( $url, array( 'timeout' => 12 ) );
			$plugin_info = json_decode( wp_remote_retrieve_body( $request ) );

			// Decoding the JSON returns nested objects. Keep the returned info as an object, but convert all inner data to associative arrays.
			if ( 200 === wp_remote_retrieve_response_code( $request ) ) {
				foreach ( get_object_vars( $plugin_info ) as $property => $value ) {
					if ( 'object' === gettype( $value ) ) {
						$plugin_info->{ $property } = Utility::object_to_assoc_array( $value );
					}
				}
			} else {
				return false;
			}

			return $plugin_info;
		}

	}

}
