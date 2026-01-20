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

namespace WPPF\v1_2_0\WordPress\Admin;

defined( 'ABSPATH' ) or exit;

use WPPF\v1_2_0\Framework\Singleton;

if ( ! class_exists( '\WPPF\v1_2_0\WordPress\Admin\Meta_Box', false ) ) {

	/**
	 * A manager for Meta Boxes that appear on admin edit pages. Manages the form rendering and post save hooking processes.
	 */
	abstract class Meta_Box extends Singleton {

		/** @var string $context The placement of the meta box in the page, e.g. 'normal', 'side', or 'advanced'. */
		public $context;

		/** @var string $priority The priority of the context, e.g. 'normal', 'low', or 'high'. */
		public $priority;

		/** @var string|array $screen The ID(s) of the screen(s) that the Meta Box should appear on. */
		public $screen;

		/** @var string $permissions The permissions to assess the current user by. */
		public $permissions;

		/** @var array The default arguments. */
		private static $default_arguments = array(
			'context'		=> 'normal',
			'permissions'	=> 'edit_post',
			'priority'		=> 'default',
			'screen'		=> array(),
		);

		/**
		 * The function that will output the Meta Box HTML. Hooked by { $this->add_meta_box() } through { self::_render() }.
		 */
		abstract protected static function render();

		/**
		 * The code to be run after the _save_post hook checks.
		 * 
		 * @param int $post_id The ID of the Post being saved.
		 * @param \WP_Post $Post A copy of the Post instance being saved.
		 */
		abstract protected static function save_post( int $post_id, ?\WP_Post $Post = null );

		/**
		 * A meta key to use when saving the Post Meta to the database. Should be lowercase and underscored.
		 * 
		 * @return string The lowercase, underscored meta key.
		 */
		abstract public static function meta_key();

		/**
		 * Return the ID of the Meta Box (used in the "id" attribute, says WordPress docs, i.e. the "id" attribute in the HTML DOM for Javascript events).
		 * The ID should be lowercase and underscored to be consistent with the { $this->meta_key() } value.
		 * 
		 * @return string The slug ID of the Meta Box.
		 */
		abstract public static function get_id();

		/**
		 * The title of the Meta Box.
		 * 
		 * @return string The Meta Box title.
		 */
		abstract public static function get_title();

		/**
		 * A generated nonce for super class forms.
		 * 
		 * @return string The nonce.
		 */
		final public static function get_nonce() { return sprintf( '%s_%s', static::get_id(), static::meta_key() ); }

		/**
		 * A generated nonce action for super class forms.
		 * 
		 * @return string The nonce action.
		 */
		final public static function get_nonce_action() { return sprintf( '%s_%s-action', static::get_id(), static::meta_key() ); }

		/**
		 * Get the HTML name attribute prefix for grouping data inputs in this meta box.
		 * 
		 * @param string $input_name The desired key you would like the input value to be submitted with.
		 * 
		 * @return string The name attribute prefix for inputs.
		 */
		final public static function create_input_name( string $input_name ) { return sprintf( '%s[%s][%s]', static::get_id(), static::meta_key(), $input_name ); }

		/**
		 * Construct the Meta_Box. Set the default properties if they are not set.
		 */
		public function __construct() {
			foreach ( self::$default_arguments as $propety => $value ) {
				if ( ! isset( $this->{ $propety } ) ) {
					$this->{ $propety } = $value;
				}
			}

			add_action( 'save_post', array( $this, '_save_post' ) );
		}

		/**
		 * WordPress hook for 'save_post', verify autosaving and Meta Box nonce.
		 * 
		 * @param int $post_id The ID of the Post being saved.
		 * @param \WP_Post $Post A copy of the Post instance being saved.
		 */
		final public static function _save_post( int $post_id, ?\WP_Post $Post = null ) {

			if ( ! isset( $_POST[ static::get_nonce() ] ) || ! wp_verify_nonce( $_POST[ static::get_nonce() ], self::get_nonce_action() ) ) {
				return;
			}

			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}

			$user_can_edit_post = current_user_can( self::instance()->permissions, $post_id );

			if ( ! $user_can_edit_post ) {
				return $post_id;
			}

			if ( ! $Post ) {
				$Post = get_post( $post_id );
			}

			// Call Meta Box super class save_post
			static::save_post( $post_id, $Post );
		}

		/**
		 * The hook to use when adding the meta box to the WordPress environment. The gets called in { $this->add_meta_box() }.
		 */
		final public static function _render() {
			wp_nonce_field( static::get_nonce_action(), static::get_nonce() );
			static::render();
		}

		/**
		 * Add the Meta Box to the WordPress environment.
		 */
		final public function add_meta_box() {
			add_meta_box(
				sprintf( '%s_%s', $this->get_id(), $this->meta_key() ),
				$this->get_title(),
				array( static::class, '_render' ),
				$this->screen,
				$this->context,
				$this->priority
			);
		}

		/**
		 * Return the expected input data from the POST data.
		 * 
		 * @return mixed The data expected from the post or NULL if not found.
		 */
		final public static function get_post_data() {

			if ( array_key_exists( static::get_id(), $_POST ) && array_key_exists( static::meta_key(), $_POST[ static::get_id() ] ) ) {
				return $_POST[ static::get_id() ][ static::meta_key() ];
			} else {
				return null;
			}

		}

	}

}
