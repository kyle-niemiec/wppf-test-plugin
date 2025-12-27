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

use DesignInk\WordPress\Framework\v1_1_2\Admin\Admin_Notice;
use DesignInk\WordPress\Framework\v1_1_2\Admin\Admin_Notice_Queue;
use DesignInk\WordPress\Framework\v1_1_2\Plugin;
use DesignInk\WordPress\Framework\v1_1_2\WooCommerce\Email_Traits;

if ( ! class_exists( '\DesignInk\WordPress\Framework\v1_1_2\WooCommerce_Plugin', false ) ) {

	/**
	 * A super class of the Plugin that includes WooCommerce-specific functionality.
	 */
	abstract class WooCommerce_Plugin extends Plugin {

		// Use email traits
		use Email_Traits;

		/** @var string The WooCommerce templates folder path. */
		private static $template_folder = 'woocommerce';

		/**
		 * The constructor. Run the trait functions.
		 */
		public function __construct() {
			array_push( self::$includes, 'emails' );
			parent::__construct();

			add_action( 'plugins_loaded', function() {
				if ( $this->woocommerce_check() ) {
					add_filter( 'wc_get_template', array( static::class, '_wc_get_template' ), 10, 2 );
					add_filter( 'wc_get_template_part', array( static::class, '_wc_get_template_part' ), 10, 3 );
					add_filter( 'woocommerce_locate_core_template', array( static::class, '_woocommerce_locate_core_template' ), 10, 4 );
					$this->load_emails();
				}
			} );
		}

		/**
		 * The WooCommerce 'wc_get_template' filter hook.
		 * 
		 * @param string $template The currently found template in the filter chain.
		 * @param string $slug The template slug.
		 * 
		 * @return string The found template or the chain-passed template.
		 */
		final public static function _wc_get_template( string $template, string $template_name ) {
			$file = sprintf(
				'%s/%s/%s',
				dirname( static::instance()->get_plugin_file() ),
				static::$template_folder,
				$template_name
			);

			if ( file_exists( $file ) ) {
				$template = $file;
			}

			return $template;
		}

		/**
		 * The WooCommerce 'wc_get_template_part' filter hook.
		 * 
		 * @param string $template The currently found template in the filter chain.
		 * @param string $slug The template part slug.
		 * @param string $name The template part name.
		 * 
		 * @return string The found template or the chain-passed template.
		 */
		final public static function _wc_get_template_part( string $template, string $slug, string $name ) {
			$file = sprintf(
				'%s/%s/%s-%s.php',
				dirname( static::instance()->get_plugin_file() ),
				static::$template_folder,
				$slug,
				$name
			);

			if ( file_exists( $file ) ) {
				$template = $file;
			}

			return $template;
		}

		/**
		 * 
		 * @param string $core_file The full path to the expected file. Return this value.
		 * @param string $template The relative template path (e.g. 'emails/new-order.php').
		 * @param string $template_base The path the the templates directory in the WooCommerce plugin folder.
		 * @param string $email_id The ID of the WooCommerce email looking for the template.
		 */
		final public static function _woocommerce_locate_core_template( $core_file, $template, $template_base, $email_id ) {
			$file = sprintf(
				'%s/%s/%s',
				dirname( static::instance()->get_plugin_file() ),
				static::$template_folder,
				$template
			);

			if ( file_exists( $file ) ) {
				$core_file = $file;
			}

			return $core_file;
		}

		/**
		 * Check whether WooCommerce is activated and throw an error message if it is not.
		 * 
		 * @return bool Whether or not the WC check was passed.
		 */
		final public function woocommerce_check() {
			if ( ! function_exists( 'WC' ) ) {
				$plugin_info = get_file_data( $this->get_plugin_file(), array( 'plugin_name' => 'Plugin Name' ) );

				if ( defined( 'WP_SANDBOX_SCRAPING' ) && true === WP_SANDBOX_SCRAPING ) {
					$message = sprintf( "WooCommerce must be enabled in order to activate %s.", $plugin_info['plugin_name'] );
					die( __( $message ) );
				} else {
					add_action( 'plugins_loaded', function() use ( $plugin_info ) {
						if ( ! function_exists( 'WC' ) ) {
							$message = __( sprintf( 'WooCommerce must be enabled in order to use %s. The plugin will be deactivated for safety.', $plugin_info['plugin_name'] ) );
							$Notice = new Admin_Notice( 'error', $message );
							Admin_Notice_Queue::add_notice( $Notice );
							$this->deactivate();
						}
					} );
				}

				return false;
			}
	
			return true;
		}

	}

}
