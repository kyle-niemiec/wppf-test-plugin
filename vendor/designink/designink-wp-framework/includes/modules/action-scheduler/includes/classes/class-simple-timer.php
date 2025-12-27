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

use DesignInk\WordPress\Framework\v1_1_2\DesignInk_Framework_Shadow_Plugin;
use DesignInk\WordPress\Framework\v1_1_2\Utility;

if ( ! class_exists( '\DesignInk\WordPress\Framework\v1_1_2\Action_Scheduler\Simple_Timer', false ) ) {

	/**
	 * A Timer instance that bases it's run times off of even intervals from a specified start times. It can also return how many missed intervals a Timer may potentially have.
	 */
	final class Simple_Timer extends Timer {

		/** @var int The amount of seconds/hours/days/weeks to measure by. */
		public $multiplier;

		/** @var string The interval base specifier in seconds for the Timer interval. */
		public $interval;

		/** @var array The default arguments provided to any Action class instance. */
		private static $default_arguments = array(
			'multiplier' => '24',
			'interval' => 'hour'
		);

		/** @var array An associative array linking the interval types to their respective duration in seconds. */
		private static $interval_types = array(
			'minute' =>	( 60 ),
			'hour' =>	( 60 * 60 ),
			'day' =>	( 60 * 60 * 24 ),
			'week' =>	( 60 * 60 * 24 * 7 ),
		);

		/**
		 * Get the interval types.
		 * 
		 * @return array The interval types.
		 */
		final public static function get_interval_types() { return self::$interval_types; }

		/**
		 * Get the Timer name.
		 * 
		 * @return array The Timer name.
		 */
		final public static function timer_label() { return 'Simple Timer'; }

		/**
		 * Get the Timer type ID.
		 * 
		 * @return array The Timer type ID.
		 */
		final public static function timer_type_id() { return 'simple-timer'; }

		/**
		 * Construct the Interval Timer.
		 * 
		 * @param string $timer_id The ID to reference the Timer by.
		 * @param array $options The options to pass into the timer.
		 */
		public function __construct( string $timer_id, array $options ) {
			$inteval_correct = in_array( $options['interval'], array_keys( self::$interval_types ) );
			$multiplier_negative = isset( $options['multiplier'] ) && intval( $options['multiplier'] ) < 0;

			if ( ! $inteval_correct ) {
				$types = implode( ',', array_keys( self::$interval_types ) );
				$message = sprintf( "The provided interval is incorrect. (%s) expected, recieved: %s.", $types, $options['interval'] );
				throw new \Exception( __( $message ) );
				return;
			}

			if ( $multiplier_negative ) {
				$message = sprintf( "The multiplier supplied to a Simple Timer must be a positive integer." );
				throw new \Exception( __( $message ) );
				return;
			}

			$merged_options = Utility::guided_array_merge( $this::$default_arguments, $options );

			foreach ( $merged_options as $property => $value ) {
				if ( property_exists( $this, $property ) ) {
					$this->{ $property } = $value;
				}
			}

			parent::__construct( $timer_id, $options );
		}

		/**
		 * The inherited abstract function for printing the output for the Form Builder.
		 * 
		 * @param string $group The name of the group the Timer will have in the form.
		 */
		final public static function print_form( string $group ) {
			DesignInk_Framework_Shadow_Plugin::instance()->get_template( 'simple-timer-form-builder', array( 'group' => $group ) );
		}

		/**
		 * Return the \DateTime instance that represents the next time the Interval Timer is supposed to be run.
		 * 
		 * @return \DateTime The time of next run.
		 */
		final public function get_next_run() {
			$last = $this->get_last_run();

			if ( ! $last ) {
				$last = new \DateTime( sprintf( '@%s', $this->timer_created ) );
			}

			$interval = intval( $this->multiplier * $this->get_interval_value() );
			$last->add( new \DateInterval( sprintf( 'PT%sS', $interval ) ) );
			return $last;
		}

		/**
		 * Get the value in seconds of the interval type of this Interval Timer.
		 * 
		 * @return int The value in seconds of the given interval type.
		 */
		final public function get_interval_value() {
			return self::$interval_types[ $this->interval ];
		}

		/**
		 * The inherited abstract for exporting the properties created by this class.
		 * 
		 * @return array The exportable properties of this Timer class.
		 */
		final protected function export_array() {
			$timer_export = array();

			foreach ( self::$default_arguments as $property => $value ) {

				if ( property_exists( $this, $property ) ) {
					$timer_export[ $property ] = $this->{ $property };
				} else {
					$timer_export[ $property ] = $value;
				}

			}

			return $timer_export;
		}

		/**
		 * The inherited abstract for printing Timer info.
		 */
		final public function print_info() {
			DesignInk_Framework_Shadow_Plugin::instance()->get_template( 'simple-timer-print-info', array( 'Timer' => $this ) );
		}

	}

}
