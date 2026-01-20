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

if ( ! class_exists( '\WPPF\v1_2_0\Plugin\Action_Scheduler\Cron_Manager', false ) ) {

	/**
	 * A class to manage the WP Cron associated with the Action Scheduler and how ofter to check for timers to run.
	 */
	final class Cron_Manager {

		/** @var string The identifier for the cron schedule interval */
		const WP_CRON_SCHEDULE = 'wppf_action_scheduler_cron_interval';

		/** @var int The time (in seconds) between checks for timers */
		const WP_CRON_SCHEDULE_INTERVAL = ( 60 * 5 );

		/** @var string The name of the hook to run when firing the cron */
		const WP_CRON_SCHEDULE_HOOK = 'wppf_action_scheduler_update_hook';

		/**
		 * The WordPress hook for 'cron_schedules'.
		 * 
		 * @param string[] $schedules The cron schedule types (minute, hour, etc).
		 */
		final public static function _cron_schedules( array $schedules ) {
			$schedules[ self::WP_CRON_SCHEDULE ] = array(
				'interval' => self::WP_CRON_SCHEDULE_INTERVAL,
				'display' => __( sprintf( 'Every %s Seconds', self::WP_CRON_SCHEDULE_INTERVAL ) ),
			);

			return $schedules;
		}

		/**
		 * Function name matches that of the constant stored in this class, function is the hook function run.
		 */
		final public static function _wppf_action_scheduler_update_hook() {
			Timer_Manager::run_timer_queue();
		}

		/**
		 * Checks is the cron timer is created and creates it if it is not.
		 */
		final public static function check_cron_timer() {
			$schedule = wp_get_schedule( self::WP_CRON_SCHEDULE_HOOK );

			if ( false === $schedule ) {
				self::reset_cron_timer();
			}
		}

		/**
		 * Unschedule the Action Scheduler cron if it is set and recreate it.
		 */
		private static function reset_cron_timer() {
			$schedule = wp_get_schedule( self::WP_CRON_SCHEDULE_HOOK );

			if ( false !== $schedule ) {
				$next_scheduled = wp_next_scheduled( self::WP_CRON_SCHEDULE_HOOK );
				wp_unschedule_event( $next_scheduled, self::WP_CRON_SCHEDULE_HOOK );
			}

			wp_schedule_event( time(), self::WP_CRON_SCHEDULE, self::WP_CRON_SCHEDULE_HOOK );
		}

	}

}
