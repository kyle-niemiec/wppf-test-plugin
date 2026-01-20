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

if ( ! class_exists( 'WPPF\Update_Helper\v1_0_1\Plugin_Update_List', false ) ) {

	/**
	 * A class to control the filter containing the plugins registering to be checked.
	 */
	final class Plugin_Update_List {

		/** @var string The name of the filter used to store hosted plugin information. */
		const PLUGIN_UPDATE_LIST_FILTER = 'wppf_plugin_update_list';

		/**
		 * Return the filter list holding all registered plugins.
		 */
		final public static function get_list() {
			return apply_filters( self::PLUGIN_UPDATE_LIST_FILTER, array() );
		}

		/**
		 * Add a plugin to the list of plugins checking for updates.
		 * 
		 * @param string $slug The slug of the plugin checking for updates.
		 * @param string $url The URL the plugin should check for updates at.
		 */
		final public static function add_plugin( string $slug, string $url ) {
			add_filter( self::PLUGIN_UPDATE_LIST_FILTER, function( array $plugins ) use( $slug, $url ) {
				if ( ! array_key_exists( $slug, $plugins ) ) {
					$plugins[ $slug ] = $url;
				}

				return $plugins;
			} );
		}

	}

}