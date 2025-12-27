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

namespace DesignInk\WordPress\Framework\v1_1_2\WooCommerce;

defined( 'ABSPATH' ) or exit;

use DesignInk\WordPress\Framework\v1_1_2\DesignInk_Framework_Shadow_Plugin;
use DesignInk\WordPress\Framework\v1_1_2\Utility;

if ( ! class_exists( '\DesignInk\WordPress\Framework\v1_1_2\WooCommerce\WC_Tab_Navigator', false ) ) {

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
			DesignInk_Framework_Shadow_Plugin::instance()->get_template( 'woocommerce/wc-tab-navigator', array( 'Navigator' => $this ) );
		}

		/**
		 * Check whether the DesignInk WC Tab Navigator scripts have been enqueued and maybe enqueue them.
		 */
		final public static function enqueue_navigator_script() {
			$script = 'designink-wc-tab-navigator';

			if ( ! wp_script_is( $script, 'enqueued' ) ) {
				DesignInk_Framework_Shadow_Plugin::instance()->enqueue_js( $script );
				DesignInk_Framework_Shadow_Plugin::instance()->enqueue_css( $script );
			}

			if ( ! wp_script_is( 'woocommerce_admin_styles', 'enqueued' ) ) {
				wp_enqueue_style( 'woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css' );
			}

		}

	}

}
