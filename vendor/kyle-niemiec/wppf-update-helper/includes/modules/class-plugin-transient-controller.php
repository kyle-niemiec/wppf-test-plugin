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

use WPPF\Update_Helper\v1_0_1\Plugin_Update_List;
use WPPF\v1_2_0\Framework\Module;
use WPPF\v1_2_0\Framework\Utility;

if ( ! class_exists( 'WPPF\Update_Helper\v1_0_1\Plugin_Transient_Controller', false ) ) {

	/**
	 * This class controls incoming custom plugin information for plugin transients.
	 */
	final class Plugin_Transient_Controller extends Module {

		/** @var string The API URL to find plugin transient information at. */
		const PLUGIN_TRANSIENT_QUERY_PATH = '/wp-json/wppf/api/plugin-updates/transients';

		/**
		 * Module entry point
		 */
		final public static function construct() {
			if ( is_admin() ) {
				add_filter( 'pre_set_site_transient_update_plugins', array( __CLASS__, '_pre_set_site_transient_update_plugins' ), 11, 1 );
			}
		}

		/**
		 * The WordPress 'pre_set_site_transient_update_plugins' filter hook.
		 * 
		 * @param \stdClass $transient The transient object before it is persisted in cache.
		 * 
		 * @return \stdClass The transient object.
		 */
		final public static function _pre_set_site_transient_update_plugins( \stdClass $transient ) {
			return self::retrieve_remote_plugin_transient( $transient );
		}

		/**
		 * Using the list of sites looking for hosted updates, retrieve all remote information about the plugins, then if the version is newer,
		 * set the plugin in the response section of the local transient.
		 * 
		 * @param \stdClass $transient The transient object before it is persisted in cache.
		 * 
		 * @return \stdClass The transient object.
		 */
		private static function retrieve_remote_plugin_transient( \stdClass $transient ) {
			$versions = array();

			if ( ! empty( $transient->checked ) ) {
				$versions = $transient->checked;
			} else {
				$plugins = Plugin_Update_List::get_list();
				$versions = self::get_local_plugin_versions( $plugins );
			}

			$domain_requests = self::group_custom_plugins_by_domain();

			foreach ( $domain_requests as $domain => $plugin_slugs ) {
				$plugin_info = self::get_remote_plugin_transient_info( $domain, $plugin_slugs );

				if ( is_array( $plugin_info ) ) {
					foreach ( $plugin_info as $plugin ) {
						$do_update = 1 === version_compare( $plugin->new_version, $versions[ $plugin->plugin ] );

						if ( $do_update ) {
							$transient->response[ $plugin->plugin ] = $plugin;
						}
					}
				}
			}

			return $transient;
		}

		/**
		 * Takes an array of plugin slug names to find versions for locally. Expects the plugin slug will be both the folder and primary PHP file name.
		 * 
		 * @param string[] $plugin_slugs The slug-names of the plugins to get version info for.
		 * 
		 * @return array An associative array mapping plugin names with their versions.
		 */
		private static function get_local_plugin_versions( array $plugin_slugs ) {
			$versions = array();

			foreach ( $plugin_slugs as $slug => $url ) {
				$plugin_file_path = sprintf( '%1$s/%2$s/%2$s.php', WP_PLUGIN_DIR, $slug );
				$data = get_plugin_data( $plugin_file_path );
				$versions[ sprintf( '%1$s/%1$s.php', $slug ) ] = $data['Version'];
			}

			return $versions;
		}

		/**
		 * Groups all plugins on similar domains to reduce number of requests.
		 * 
		 * @return array An associative array mapping the domains which have plugins hosted to all plugins to be checked on that domain.
		 */
		private static function group_custom_plugins_by_domain() {
			$plugins = Plugin_Update_List::get_list();
			$domain_requests = array();

			foreach ( $plugins as $plugin_slug => $url ) {
				$url_parts = wp_parse_url( $url );

				$scheme = $url_parts['scheme'] ?: 'http';
				$host = $url_parts['host'];

				$resource_domain = $scheme . '://' . $host;

				$domain_already_set = isset( $domain_requests[ $resource_domain ] ) && is_array( $domain_requests[ $resource_domain ] );

				if ( ! $domain_already_set ) {
					$domain_requests[ $resource_domain ] = array();
				}

				$domain_requests[ $resource_domain ][] = $plugin_slug;
			}

			return $domain_requests;
		}

		/**
		 * Using a provided hosting domain, search the api transient path for transient info about specified plugins.
		 * 
		 * @param string $domain The domain to get plugin transient data from.
		 * @param string[] $plugins The plugin slugs to get data for.
		 * 
		 * @return false|\stdClass Return the remote plugin transient info, or FALSE if failed.
		 */
		private static function get_remote_plugin_transient_info( string $domain, array $plugins ) {
			$url = sprintf( '%s%s?plugins=%s', $domain, self::PLUGIN_TRANSIENT_QUERY_PATH, implode( ',', $plugins ) );
			$request = wp_remote_get( $url, array( 'timeout' => 12 ) );
			$plugin_info = json_decode( wp_remote_retrieve_body( $request ) );

			// Decoding the JSON returns nested objects. Keep the returned info as an object, but convert all inner data to associative arrays.
			if ( 200 === wp_remote_retrieve_response_code( $request ) ) {
				foreach ( $plugin_info as $plugin ) {
					foreach ( get_object_vars( $plugin ) as $property => $value ) {
						if ( 'object' === gettype( $value ) ) {
							$plugin->{ $property } = Utility::object_to_assoc_array( $value );
						}
					}
				}
			} else {
				return false;
			}

			return $plugin_info;
		}

	}

}
