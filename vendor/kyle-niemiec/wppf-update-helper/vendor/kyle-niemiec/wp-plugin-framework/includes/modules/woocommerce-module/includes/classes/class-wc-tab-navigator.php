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

namespace WPPF\v1_2_0\WooCommerce;

defined( 'ABSPATH' ) or exit;

use WPPF\v1_2_0\WPPF_Shadow_Plugin;
use WPPF\v1_2_0\Framework\Utility;

if ( ! class_exists( '\WPPF\v1_2_0\WooCommerce\WC_Tab_Navigator', false ) ) {

	/**
	 * A class to encompass the creation and script handling of wht WooCommerce Tabs UI component.
	 */
	final class WC_Tab_Navigator {

		/** @var array The private array holding the Tabs information. */
		private $tabs = array();

		/**
		 * Get the tabs belonging to the Navigator instance.
		 * 
		 * @return array The tabs.
		 */
		final public function get_tabs() { return $this->tabs; }

		/**
		 * Construct an instance of the Navigator.
		 * 
		 * @param array $args The arguments to create the Navigator with (i.e. the initial 'tabs').
		 */
		public function __construct( array $args ) {
			if ( array_key_exists( 'tabs', $args ) && is_array( $args ) ) {
				foreach ( $args['tabs'] as $index => $tab ) {

					if ( is_array( $tab ) && ! empty( $tab['id'] ) ) {
						$this->add_tab( $tab['id'], $tab );
					} else {
						unset( $args['tabs'][ $index ] );
						Utility::doing_it_wrong( __METHOD__, sprintf( "An invalid tab has been passed in the arguments to %s, make sure an ID has been defined. The tab has been removed.", self::class ) );
					}

				}
			}
		}

		/**
		 * Add a tab to the navigator.
		 * 
		 * @param string $id The ID of the tab.
		 * @param array $tab The tab information.
		 */
		final public function add_tab( string $id, array $tab ) {
			$default_options = array(
				'active' => false,
				'id' => '',
				'label' => '',
				'render' => array( self::class, 'tab_null_render' ),
				'render_args' => array(),
			);

			$tab = Utility::guided_array_merge( $default_options, $tab );
			$this->tabs[ $id ] = $tab;
		}

		/**
		 * An empty function to call in case a default render was not provided.
		 */
		final public function tab_null_render() { }

		/**
		 * Print the Navigator HTML.
		 */
		final public function print_navigator() {
			WPPF_Shadow_Plugin::instance()->get_template( 'woocommerce/wc-tab-navigator', array( 'Navigator' => $this ) );
		}

		/**
		 * Check whether the WPPF WC Tab Navigator scripts have been enqueued and maybe enqueue them.
		 */
		final public static function enqueue_navigator_script() {
			$script = 'wppf-wc-tab-navigator';

			if ( ! wp_script_is( $script, 'enqueued' ) ) {
				WPPF_Shadow_Plugin::instance()->enqueue_js( $script );
				WPPF_Shadow_Plugin::instance()->enqueue_css( $script );
			}

			if ( ! wp_script_is( 'woocommerce_admin_styles', 'enqueued' ) ) {
				wp_enqueue_style( 'woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css' );
			}

		}

	}

}
