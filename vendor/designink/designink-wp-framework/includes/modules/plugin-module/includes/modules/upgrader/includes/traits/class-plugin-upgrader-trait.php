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

use DesignInk\WordPress\Framework\v1_1_2\Plugin;
use DesignInk\WordPress\Framework\v1_1_2\Plugin\Upgrader_Schema;
use DesignInk\WordPress\Framework\v1_1_2\Utility;

if ( ! trait_exists( '\DesignInk\WordPress\Framework\v1_1_2\Plugin_Upgrader_Trait', false ) ) {

	/**
	 * A trait to extend the Plugin functionality by providing methods for running tasks on version updates.
	 */
	trait Plugin_Upgrader_Trait {

		/** @var string The default directory for Upgrade Schemas. */
		protected static $upgrades_dir = 'upgrades';

		/**
		 * Check if the Upgraders need to be run, then run them and update the current version if so.
		 */
		final public function init_upgrader() {
			if ( is_subclass_of( $this, Plugin::class ) && $this->requires_version_upgrade() ) {
				$this->run_upgrades();
				$this->update_current_version();
			}
		}

		/**
		 * Check if the Plugin version is newer than the currently stored version.
		 * 
		 * @return bool Whether or not there may be upgrade actions to run.
		 */
		final public function requires_version_upgrade() {
			$plugin_info = get_file_data( $this->get_plugin_file(), array( 'version' => 'Version', 'plugin_name' => 'Plugin Name' ) );
			
			if ( ! empty( $plugin_info['version'] ) && ! empty( $plugin_info['plugin_name'] ) ) {
				$current_version = get_option( sprintf( '%s_version', $this->get_class_reflection()->name ) );

				if ( false === $current_version || version_compare( $plugin_info['version'], $current_version, '>' ) ) {
					return true;
				}
			}

			return false;
		}

		/**
		 * Find all upgrade Schemas and run them.
		 */
		final public function run_upgrades() {
			$reflection = $this->get_class_reflection();
			$upgrades_dir = sprintf( '%s%s/%s/', plugin_dir_path( $reflection->getFileName() ), static::$includes_dir, static::$upgrades_dir );

			if ( is_dir( $upgrades_dir ) ) {
				$files = Utility::scandir( $upgrades_dir, 'files' );
				$upgrade_schemas = array();

				foreach ( $files as $file ) {
					$class_name = $this->load_class_file( sprintf( '%s%s', $upgrades_dir, $file ) );

					if ( false !== $class_name ) {
						$is_upgrader = is_subclass_of( $class_name, Upgrader_Schema::class );

						if ( $is_upgrader ) {
							$Schema = new $class_name();

							if ( ! array_key_exists( $Schema->get_version(), $upgrade_schemas ) ) {
								$upgrade_schemas[ $Schema->get_version() ] = array();
							}

							$upgrade_schemas[ $Schema->get_version() ] = array_merge( $upgrade_schemas[ $Schema->get_version() ], $Schema->get_actions() );
						} else {
							$message = sprintf( "Successfully found class, '%s', but it does not appear to be an Upgrader Schema, make sure you are implementing %s in '%s'.", $class_name, Upgrader_Schema::class, $file );
							Utility::doing_it_wrong( __METHOD__, __( $message ) );
						}

					}

				}

				$upgrade_schemas = self::sort_upgrade_schemas( $upgrade_schemas );
				$current_version = get_option( sprintf( '%s_version', $this->get_class_reflection()->name ) );
				self::execute_schemas( $current_version, $upgrade_schemas );
			}
		}

		/**
		 * Update the current Plugin version in the database.
		 */
		final public function update_current_version() {
			$plugin_info = get_file_data( $this->get_plugin_file(), array( 'version' => 'Version', 'plugin_name' => 'Plugin Name' ) );
			update_option( sprintf( '%s_version', $this->get_class_reflection()->name ), $plugin_info['version'] );
		}

		/**
		 * Sort Schema actions by version.
		 * 
		 * @param array $upgrade_schemas The Upgrade Schemas.
		 * 
		 * @return array The sorted Upgrade Schemas.
		 */
		final public static function sort_upgrade_schemas( array $upgrade_schemas ) {
			uksort(
				$upgrade_schemas,
				function( $version1, $version2 ) {
					return version_compare( $version1, $version2 );
				}
			);

			// Use reference in FOR loop
			foreach ( $upgrade_schemas as &$actions ) {
				usort(
					$actions,
					function( $action1, $action2 ) {
						return version_compare( $action1['priority'], $action2['priority'] );;
					}
				);
			}

			return $upgrade_schemas;
		}

		/**
		 * Loop through and execute all Schema actions.
		 * 
		 * @param string $current_version The current stored version; run all actions newer.
		 * @param array $upgrade_schemas The upgrade Schema actions.
		 */
		final public static function execute_schemas( string $current_version, array $upgrade_schemas ) {
			foreach ( $upgrade_schemas as $version => $actions ) {
				foreach ( $actions as $action ) {
					if ( -1 === version_compare( $current_version, $version ) ) {
						call_user_func( $action['action'] );
					}
				}
			}
		}

	}

}
