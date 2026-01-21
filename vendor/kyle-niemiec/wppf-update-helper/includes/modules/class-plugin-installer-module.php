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

if ( ! class_exists( 'WPPF\Update_Helper\v1_0_1\Plugin_Installer_Module', false ) ) {

	/**
	 * This module is to help the installer process by attaching private tokens to requests and renaming unzipped install locations.
	 */
	final class Plugin_Installer_Module extends Module {

		/**
		 * Module entry point.
		 */
		final public static function construct() {
			if ( is_admin() ) {
				add_filter( 'upgrader_pre_download', array( __CLASS__, '_upgrader_pre_download' ), 10, 3 );
				add_filter( 'upgrader_post_install', array( __CLASS__, '_upgrader_post_install' ), 10, 3 );
			}
		}

		/**
		 * The WordPress 'upgrader_pre_download' filter hook.
		 * 
		 * @param mixed $reply The default reply. Default is passed as FALSE from WP_Upgrader->download_package().
		 * @param string $package_url The URL looking to be downloaded (may or may not need an access token appended).
		 * @param \WP_Upgrader $instance The WP_Upgrader.
		 * 
		 * @return mixed The URL expected by the WP_Upgrader when calling download_package. Returning FALSE runs the default WP_Upgrader code.
		 */
		final public static function _upgrader_pre_download( bool $reply, string $package_url, \WP_Upgrader $instance ) {
			return self::add_access_token_to_download_url( $package_url );
		}

		/**
		 * The WordPress 'upgrader_post_install' filter hook.
		 * 
		 * @param bool $response The default response. Default is passed as TRUE from WP_Upgrader->install_package().
		 * @param array $hook_extra Extra arguments passed to hooked filters.
		 * @param array $result Installation result data.
		 * 
		 * @return WP_Error|array Return either an error or the results of the install.
		 */
		final public static function _upgrader_post_install( bool $response, array $hook_extra, array $result ) {
			return self::rename_folder_post_install( $hook_extra, $result );
		}

		/**
		 * In case a custom plugin has a private GitHub download link that requires an access token, this function finds the download url
		 * for the plugin looking to be downloaded and decrypts and adds it to the URL.
		 * 
		 * @param string $package_url The URL looking to be downloaded (may or may not need an access token appended).
		 * 
		 * @return mixed The URL expected by the WP_Upgrader when calling download_package. Returning FALSE runs the default WP_Upgrader code.
		 */
		private static function add_access_token_to_download_url( string $package_url ) {
			$current_transient = get_site_transient( 'update_plugins' );

			if ( is_array( $current_transient->response ) ) {
				foreach ( $current_transient->response as $options ) {
					if ( isset( $options->token ) && $options->package === $package_url ) {
						$ssl = Settings_Module::get_ssl_key();
						$token = base64_decode( $options->token );
						$decode = openssl_decrypt( $token, 'aes-256-cbc', $ssl, OPENSSL_RAW_DATA );
						$temp_file = sprintf( '%stmp-plugin-%s.zip', get_temp_dir(), $options->slug );

						$request = wp_remote_request(
							$package_url,
							array(
								'headers' => array(
									'Authorization' => sprintf( 'Bearer %s', $decode )
								)
							)
						);

						$file = wp_remote_retrieve_body( $request );
						file_put_contents( $temp_file, $file );

						return $temp_file;
						// This is the proper WordPress way to do things below, but it seems to corrupt the ZIP file.
						// return download_url( $url, 300, true );
					}
				}
			}

			return false;
		}

		/**
		 * Because GitHub zip files have the short commit hash appended to the folder name inside, rename the folder after installation to the correct plugin
		 * folder name after extraction.
		 * 
		 * @param array $hook_extra Extra arguments passed to hooked filters.
		 * @param array $result Installation result data.
		 * 
		 * @return WP_Error|array Return either an error or the results of the install.
		 */
		private static function rename_folder_post_install( array $hook_extra, array $result ) {
			if ( isset( $hook_extra['plugin'] ) ) {
				global $wp_filesystem;
				$plugin_name = $hook_extra['plugin'];
				$slug = self::parse_slug_from_plugin_name( $plugin_name );
				$plugin_folder = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $slug;

				$wp_filesystem->move( $result['destination'], $plugin_folder );
				$result['destination'] = $plugin_folder;

				return $result;
			}
		}

		/**
		 * Return the slug from a properly formatted plugin name, or FALSE.
		 * 
		 * @param string $name The plugin namespace name to find a slug from. Expected to fit the format {{ $slug/$slug.php }}.
		 * 
		 * @return false|string The slug found in the plugin name, or FALSE if not found.
		 */
		private static function parse_slug_from_plugin_name( string $name ) {

			if ( preg_match( '/^(?:[a-zA-Z0-9-]+)\/([a-zA-Z0-9-]+)\.php$/', $name, $matches ) ) {
				return $matches[1];
			} else {
				return false;
			}

		}

	}

}