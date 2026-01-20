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

namespace WPPF\v1_2_0\Plugin\Action_Scheduler;

defined( 'ABSPATH' ) or exit;

use WPPF\v1_2_0\Plugin\Action_Scheduler\Timer;
use WPPF\v1_2_0\WPPF_Shadow_Plugin;

if ( ! class_exists( '\WPPF\v1_2_0\Plugin\Action_Scheduler\Form_Builder', false ) ) {

	/**
	 * A class to automate the creation of timers through form submissions.
	 */
	final class Form_Builder {

		/** @var string The name of the form. This is used for input names. */
		const FORM_BASE_NAME = 'wppf_action_scheduler';

		/** @var string The nonce that gets created with each form. */
		const FORM_NONCE_NAME = 'wppf_action_scheduler_form_builder_nonce';

		/** @var string The nonce action. */
		const FORM_NONCE_ACTION = 'wppf_action_scheduler_form_builder_update';

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
			WPPF_Shadow_Plugin::instance()->enqueue_css( 'action-scheduler-form-builder' );
			WPPF_Shadow_Plugin::instance()->enqueue_js( 'action-scheduler-form-builder' );
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
			WPPF_Shadow_Plugin::instance()->get_template( 'timer-form-builder-base', array( 'group' => $group ) );
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
		 * @return null|\WP_Error|\WPPF\v1_2_0\Plugin\Action_Scheduler\Timer The newly created/updated Timer instance.
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
