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

namespace WPPF\v1_2_0\Plugin;

defined( 'ABSPATH' ) or exit;

use WPPF\v1_2_0\Plugin\Action_Scheduler\Cron_Manager;
use WPPF\v1_2_0\Plugin\Action_Scheduler\Form_Builder;
use WPPF\v1_2_0\Plugin\Action_Scheduler\Interval_Timer;
use WPPF\v1_2_0\Plugin\Action_Scheduler\Simple_Timer;
use WPPF\v1_2_0\Framework\Module;

if ( ! class_exists( '\WPPF\v1_2_0\Plugin\Action_Scheduler', false ) ) {

	/**
	 * A class to manage the WPPF Action Scheduler solution.
	 */
	final class Action_Scheduler extends Module {

		/**
		 * Entry point.
		 */
		public static function construct() {
			self::register_timer_forms();

			add_filter( 'cron_schedules', array( Cron_Manager::class, '_cron_schedules' ) );
			add_action( Cron_Manager::WP_CRON_SCHEDULE_HOOK, array( Cron_Manager::class, '_wppf_action_scheduler_update_hook' ) );

			Cron_Manager::check_cron_timer();
		}

		/**
		 * Register the different types of timers with the Form Builder so it can print their forms.
		 */
		private static function register_timer_forms() {
			Form_Builder::add_timer_class( Simple_Timer::class );
			Form_Builder::add_timer_class( Interval_Timer::class );
		}

	}

}
