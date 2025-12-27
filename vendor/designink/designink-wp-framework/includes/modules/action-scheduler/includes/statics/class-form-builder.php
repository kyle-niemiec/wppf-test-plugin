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

namespace DesignInk\WordPress\Framework\v1_1_2\Action_Scheduler;

defined( 'ABSPATH' ) or exit;

use DesignInk\WordPress\Framework\v1_1_2\Action_Scheduler\Timer;
use DesignInk\WordPress\Framework\v1_1_2\DesignInk_Framework_Shadow_Plugin;

if ( ! class_exists( '\DesignInk\WordPress\Framework\v1_1_2\Action_Scheduler\Form_Builder', false ) ) {

	/**
	 * A class to automate the creation of timers through form submissions.
	 */
	final class Form_Builder {

		/** @var string The name of the form. This is used for input names. */
		const FORM_BASE_NAME = 'di_action_scheduler';

		/** @var string The nonce that gets created with each form. */
		const FORM_NONCE_NAME = 'di_action_scheduler_form_builder_nonce';

		/** @var string The nonce action. */
		const FORM_NONCE_ACTION = 'di_action_scheduler_form_builder_update';

		/** @var string[] A list of timer instances to have forms for. */
		private static $timer_classes = array();

		/**
		 * Return the registered Timer classes.
		 * 
		 * @return string[] The Timer classes registered.
		 */
		final public static function get_timer_classes() {
			return self::$timer_classes;
		}
		
		/**
		 * Enqueue the scripts and styles for the form builder.
		 */
		final public static function enqueue_form_builder_scripts() {
			DesignInk_Framework_Shadow_Plugin::instance()->enqueue_css( 'action-scheduler-form-builder' );
			DesignInk_Framework_Shadow_Plugin::instance()->enqueue_js( 'action-scheduler-form-builder' );
		}

		/**
		 * Register a Timer with the Form Builder.
		 * 
		 * @param string The qualified class name of the Timer.
		 */
		final public static function add_timer_class( string $class_name ) {
			$class_exists = class_exists( $class_name );
			$class_already_added = in_array( $class_name, self::$timer_classes );

			if ( $class_exists && ! $class_already_added ) {
				$instance_is_timer = is_a( $class_name, Timer::class, true );

				if ( $instance_is_timer ) {
					self::$timer_classes[] = $class_name;
				}
			}
		}

		/**
		 * Print the Timer builder form.
		 * 
		 * @param string $group What name to categorize this form under.
		 */
		final public static function print_form( string $group ) {
			DesignInk_Framework_Shadow_Plugin::instance()->get_template( 'timer-form-builder-base', array( 'group' => $group ) );
		}

		/**
		 * Get a submitted Timer form or return NULL if it does not exist.
		 * 
		 * @param string $group The group name of the form.
		 * 
		 * @return null|array The posted form or NULL.
		 */
		final public static function get_form( string $group ) {
			if ( isset( $_POST[ self::FORM_BASE_NAME ][ $group ] ) ) {
				return $_POST[ self::FORM_BASE_NAME ][ $group ];
			}

			return null;
		}

		/**
		 * Takes the name of a timer form submitted and the desired timer ID and creates a new Timer instance from it.
		 * 
		 * @param string $group The name of the form group with was submitted.
		 * @param string $id The unique string identifier you want to have attached to your timer.
		 * @param array $option The Timer options passed to the Timer on creation, these values will be overwritten by any corresponding form submission options.
		 * 
		 * @return null|\WP_Error|\DesignInk\WordPress\Framework\v1_1_2\Action_Scheduler\Timer The newly created/updated Timer instance.
		 */
		final public static function generate_timer_from_form( string $group, string $id, array $options ) {
			$Timer = null;
			$timer_type_set = isset( $_POST[ self::FORM_BASE_NAME ][ $group ]['timer_type'] );

			if ( $timer_type_set ) {
				$form = $_POST[ self::FORM_BASE_NAME ][ $group ];
				$options = array_merge( $options, $form );

				// Create new Timer instance
				try {
					$Timer = Timer::instantiate_timer( $id, $options );
				} catch ( \Exception $e ) {
					$message = sprintf( "An error occured while creating a Timer instance: %s", $e->getMessage() );
					return new \WP_Error( 'timer', __( $message ) );
				}

			}

			return $Timer;
		}

		/**
		 * A function to generate a name for form inputs.
		 * 
		 * @param string $group The primary group to submit the form under..
		 * @param string|array $input Either a single key or an array of nested keys to create the input name under.
		 * 
		 * @return string The generated input name, or an empty string if it could not be generated.
		 */
		final public static function generate_form_input_name( string $group, $input ) {
			if ( ! $input ) {
				return '';
			}

			if ( 'string' === gettype( $input ) ) {
				return sprintf( '%s[%s][%s]', self::FORM_BASE_NAME, $group, $input );
			} else if ( is_array( $input ) && ! empty( $input ) ) {
				return sprintf( '%s[%s][%s]', self::FORM_BASE_NAME, $group, implode( '][', $input ) );
			}

			return '';
		}

	}

}
