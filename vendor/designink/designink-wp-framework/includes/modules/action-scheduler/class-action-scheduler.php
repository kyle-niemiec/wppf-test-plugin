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

namespace DesignInk\WordPress\Framework\v1_1_2;

defined( 'ABSPATH' ) or exit;

use DesignInk\WordPress\Framework\v1_1_2\Action_Scheduler\Cron_Manager;
use DesignInk\WordPress\Framework\v1_1_2\Action_Scheduler\Form_Builder;
use DesignInk\WordPress\Framework\v1_1_2\Action_Scheduler\Interval_Timer;
use DesignInk\WordPress\Framework\v1_1_2\Action_Scheduler\Simple_Timer;
use DesignInk\WordPress\Framework\v1_1_2\Module;

if ( ! class_exists( '\DesignInk\WordPress\Framework\v1_1_2\Action_Scheduler', false ) ) {

	/**
	 * A class to manage the DesignInk custom Action Scheduler solution for WordPress.
	 */
	final class Action_Scheduler extends Module {

		/**
		 * Entry point.
		 */
		public static function construct() {
			self::register_timer_forms();

			add_filter( 'cron_schedules', array( Cron_Manager::class, '_cron_schedules' ) );
			add_action( Cron_Manager::WP_CRON_SCHEDULE_HOOK, array( Cron_Manager::class, '_di_action_scheduler_update_hook' ) );

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
