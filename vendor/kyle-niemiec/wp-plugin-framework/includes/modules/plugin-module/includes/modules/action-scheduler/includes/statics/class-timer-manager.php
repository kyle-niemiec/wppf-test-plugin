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

if ( ! class_exists( '\WPPF\v1_2_0\Plugin\Action_Scheduler\Timer_Manager', false ) ) {

	/**
	 * A static class to manage the saving and loading of Timers from the database.
	 */
	final class Timer_Manager {

		/** @var string The option name that Timers get saved under in the database. */
		const TIMERS_OPTION = 'wppf_action_scheduler_timers';

		/**
		 * Return all Timers instances from the database in an associative list of their IDs.
		 * 
		 * @return Timer[] The Timers.
		 */
		final public static function get_timers() {
			$results = get_option( self::TIMERS_OPTION, array() );
			$Timers = array();

			foreach ( $results as $timer_id => $timer_options ) {
				$Timers[ $timer_id ] = Timer::instantiate_timer( $timer_id, $timer_options );
			}

			return $Timers;
		}

		/**
		 * Check all Timers to maybe be run.
		 */
		final public static function run_timer_queue() {
			$Timers = self::get_timers();

			foreach ( $Timers as $Timer ) {
				$Timer->maybe_run_timer();
			}
		}

		/**
		 * Find a Timer instance and return it, or NULL if not found.
		 * 
		 * @param string $timer_id The Timer ID.
		 * 
		 * @return null|\WPPF\v1_2_0\Plugin\Action_Scheduler\Timer The Timer instance or NULL.
		 */
		final public static function get_timer( string $timer_id ) {
			$Timers = self::get_timers();

			if ( array_key_exists( $timer_id, $Timers ) ) {
				return $Timers[ $timer_id ];
			}

			return null;
		}

		/**
		 * Update/add a Timer, optionally merge Actions with a previously existing Timer being overwritten.
		 * 
		 * @param \WPPF\v1_2_0\Plugin\Action_Scheduler\Timer $Timer The Timer instance to update.
		 * @param bool $merge_actions Whether or not to merge action with a previously existing Timer. (default FALSE)
		 * 
		 * @return bool Whether or not the Timers were persisted to the database.
		 */
		final public static function update_timer( Timer $Timer, bool $merge_actions = false ) {
			$Timers = self::get_timers();

			// Optionally merge Actions if the Timer exists.
			if ( array_key_exists( $Timer->id, $Timers ) && $merge_actions ) {
				$Timer->merge_actions( $Timers[ $Timer->id ] );
			}

			$Timers[ $Timer->id ] = $Timer;
			return self::set_timers( $Timers );
		}

		/**
		 * Given an array of Timers, update all Timers in the database to reflect the data in the array.
		 * 
		 * @param \WPPF\v1_2_0\Plugin\Action_Scheduler\Timer[] The Timers to save.
		 * 
		 * @return bool Whether or not the option was persisted to the database.
		 */
		final public static function set_timers( array $Timers ) {
			if ( self::are_all_elements_timers( $Timers ) ) {
				$timers_export = array();

				foreach ( $Timers as $Timer ) {
					$timers_export[ $Timer->id ] = $Timer->to_array();
				}

				// Don't autoload option, it will be loaded with a WP Cron process in the background
				return update_option( self::TIMERS_OPTION, $timers_export, 'no' );
			}

			return false;
		}

		/**
		 * Attempt to delete a timer from the database.
		 * 
		 * @param string $timer_id The ID of the Timer to remove.
		 * 
		 * @return bool Whether or not the Timer was successfully removed.
		 */
		final public static function delete_timer( string $timer_id ) {
			$Timers = self::get_timers();

			foreach ( $Timers as $Timer ) {
				if ( $timer_id === $Timer->id ) {
					unset( $Timers[ $Timer->id ] );
					return self::set_timers( $Timers );
				}
			}

			return false;
		}

		/**
		 * A short utility function to check whether an array consists of only Timer elements.
		 * 
		 * @param array $Timers An array of possible Timer elements.
		 * 
		 * @return bool Whether the array is only of Timer elements or not.
		 */
		private static function are_all_elements_timers( array $Timers ) {
			foreach ( $Timers as $Timer ) {
				if ( ! ( $Timer instanceof Timer ) ) {
					return false;
				}
			}

			return true;
		}

	}

}
