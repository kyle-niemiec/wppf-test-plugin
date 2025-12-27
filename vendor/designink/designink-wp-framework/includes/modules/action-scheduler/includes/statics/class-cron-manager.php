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

if ( ! class_exists( '\DesignInk\WordPress\Framework\v1_1_2\Action_Scheduler\Cron_Manager', false ) ) {

	/**
	 * A class to manage the WP Cron associated with the Action Scheduler and how ofter to check for timers to run.
	 */
	final class Cron_Manager {

		/** @var string The identifier for the cron schedule interval */
		const WP_CRON_SCHEDULE = 'di_action_scheduler_cron_interval';

		/** @var int The time (in seconds) between checks for timers */
		const WP_CRON_SCHEDULE_INTERVAL = ( 60 * 5 );

		/** @var string The name of the hook to run when firing the cron */
		const WP_CRON_SCHEDULE_HOOK = 'di_action_scheduler_update_hook';

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
		final public static function _di_action_scheduler_update_hook() {
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
