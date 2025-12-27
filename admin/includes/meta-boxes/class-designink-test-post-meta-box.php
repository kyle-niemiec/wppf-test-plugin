<?php
/**
 * DesignInk WP Framework Test Plugin
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
 * @author    DesignInk Digital
 * @copyright Copyright (c) 2008-2026, DesignInk, LLC
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

defined( 'ABSPATH' ) or exit;

use DesignInk\WordPress\Framework\v1_1_2\Admin\Admin_Notices;
use DesignInk\WordPress\Framework\v1_1_2\Admin\Meta_Box;
use DesignInk\WordPress\Framework\v1_1_2\Meta_Schema;

if ( ! class_exists( 'DesignInk_Test_Post_Meta_Box', false ) ) {

	/**
	 * The primary Meta Box for the Post Series Post Type page.
	 */
	final class DesignInk_Test_Post_Meta_Box extends Meta_Box {

		/**
		 * The required meta_key() abstract function.
		 * 
		 * @return string The meta key to use for nonces and inputs in the Meta Box.
		 */
		final public static function meta_key() { return 'test_meta'; }

		/**
		 * The required get_title() abstract function.
		 * 
		 * @return string The title of the Meta Box.
		 */
		final public static function get_title() { return 'Test Meta Box'; }

		/**
		 * The required get_id() abstract function.
		 * 
		 * @return string The ID for the Meta Box.
		 */
		final public static function get_id() { return sprintf( '%s_edit_screen', DesignInk_Test_Post_Type::post_type() ); }

		/**
		 * The required render() abstract function.
		 */
		final protected static function render() {
			DesignInk_WP_Framework_Test_Plugin::instance()->get_admin_module()->get_template( 'test-post-meta-box' );
		}

		/**
		 * The inherited abstract function to attach to the 'save_post' hook.
		 * 
		 * @param int $post_id The Post ID.
		 * @param \WP_Post $Post The Post object.
		 * 
		 * @return int The post ID.
		 */
		final protected static function save_post( int $post_id, ?\WP_Post $Post = null ) {
			$is_custom_post = $Post->post_type === DesignInk_Test_Post_Type::post_type();

			if ( ! $is_custom_post ) {
				return $post_id;
			}

			$Meta = new DesignInk_Test_Post_Meta( $Post );
			$data = self::prepare_data( $Meta->times_saved );
			$Meta->import( $data );
			$validation = $Meta->validate();

			if ( is_wp_error( $validation ) ) {
				Admin_Notices::error( Meta_Schema::create_error_message( $validation ) );
			} else {
				$Meta->save();
			}

			return $post_id;
		}

		/**
		 * A private function for modifying POSTed data to prepare it for saving.
		 * 
		 * @param array $data The 'times saved' array from the original Meta to modify.
		 * 
		 * @return array The data to save to the meta.
		 */
		private static function prepare_data( array $times_saved ) {
			$data = self::get_post_data();
			$data['times_saved'] = $times_saved;

			if ( isset( $data['is_toggle_active'] ) && 'yes' === $data['is_toggle_active'] ) {
				$data['is_toggle_active'] = true;
			} else {
				$data['is_toggle_active'] = false;
			}

			if ( isset( $data['clear_saves'] ) && 'yes' === $data['clear_saves'] ) {
				$data['times_saved'] = array();
			}

			array_push( $data['times_saved'], time() );
			return $data;
		}

	}

}
