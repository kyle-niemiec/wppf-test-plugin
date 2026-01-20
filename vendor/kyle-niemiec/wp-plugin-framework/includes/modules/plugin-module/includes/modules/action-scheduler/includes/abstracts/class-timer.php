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

use WPPF\v1_2_0\Plugin\Action_Scheduler\Action;
use WPPF\v1_2_0\Framework\Utility;

if ( ! class_exists( '\WPPF\v1_2_0\Plugin\Action_Scheduler\Timer', false ) ) {

	/**
	 * A Timer template for the Action Scheduler system that other timer classes can extend and implement their own settings for.
	 */
	abstract class Timer {

		/** @var Action[] $Actions The instantiated list of actions to fire for this timer. */
		protected $Actions = array();

		/** @var array $actions_data The bare array representation of Action instances. */
		protected $actions_data;

		/** @var string $id The unique timer string ID. */
		public $id;

		/** @var int The timestamp for when the Timer last ran its Actions. */
		public $last_run;

		/** @var array The timestamp of the time when the Timer was created. */
		public $timer_created;

		/** @var array The type ID name of the Timer super-class that this instance refers to. */
		public $timer_type;

		/** @var array The default arguments for this class. Should match the exportable properties. */
		private static $default_arguments = array(
			'actions_data'	=> array(),
			'id'			=> null,
			'last_run'		=> null,
			'timer_created'	=> null,
			'timer_type'	=> null,
		);

		/**
		 * Abstract function to print form options for the form builder.
		 * 
		 * @param string $group The form name to use when building the form.
		 */
		abstract public static function print_form( string $group );

		/**
		 * Abstract function which returns a timestamp of when the last run of Actions is/was supposed to be. This allows timers to implement their own system for deciding the timer sequence without being bound to a static interval.
		 * 
		 * @return \DateTime The next run time.
		 */
		abstract public function get_next_run();

		/**
		 * Abstract function for holding a printable timer name for the Form Builder.
		 * 
		 * @return string The formal name of the Timer.
		 */
		abstract public static function timer_label();

		/**
		 * Abstract function that returns a slug which represents the timer class, this way timers can be reinstantiated from the database across Framework versions.
		 * 
		 * @return string A slug that represents the timer class.
		 */
		abstract public static function timer_type_id();

		/**
		 * Abstract function to cover the exporting of properties of super classes in { $this->to_array() }
		 * 
		 * @return array The exportable properties and values of the super class.
		 */
		abstract protected function export_array();

		/**
		 * Abstract function to print a table of the Timer's useful information.
		 */
		abstract public function print_info();

		/**
		 * Get the creation DateTime in GMT.
		 * 
		 * @return \DateTime The creation DateTime.
		 */
		final public function get_created() {
			return new \DateTime( sprintf( '@%s', $this->timer_created ), new \DateTimeZone( 'GMT' )  );
		}

		/**
		 * Get the last run time. Returns in GMT.
		 * 
		 * @return null|\DateTime The last run time.
		 */
		final public function get_last_run() {

			if ( null === $this->last_run ) {
				return null;
			} else {
				return new \DateTime( sprintf( '@%s', $this->last_run ), new \DateTimeZone( 'GMT' ) );
			}

		}

		/**
		 * Get the Actions.
		 * 
		 * @return \WPPF\v1_2_0\Plugin\Action_Scheduler\Action[] The Actions.
		 */
		final public function get_actions() { return $this->Actions; }

		/**
		 * Construct a Timer given an ID and a set of options.
		 * 
		 * @param string $timer_id The ID of the Timer to construct.
		 * @param array $options A list of options to provide the Timer and it's super-class constructors.
		 */
		public function __construct( string $timer_id, array $options ) {

			$options = Utility::guided_array_merge(
				self::$default_arguments,
				array(
					'timer_created'	=> time(),
				),
				$options,
				array(
					'timer_type'	=> static::timer_type_id()
				)
			);

			if ( empty( $timer_id ) ) {
				$message = sprintf( "A valid id must be passed to the %s constructor.", static::class );
				throw new \Exception( __( $message ) );
			}

			foreach ( $options as $property => $value ) {
				if ( property_exists( $this, $property ) ) {
					$this->{ $property } = $value;
				}
			}

			$this->id = $timer_id;
			$this->timer_class = ( new \ReflectionClass( $this ) )->getName();
			$this->create_action_instances();
		}

		/**
		 * Loop through the initial array of actions and load their instances.
		 */
		private function create_action_instances() {
			foreach ( $this->actions_data as $action_id => $action ) {
				$Action = new Action( $action_id, $action );
				$this->Actions[] = $Action;
			}
		}

		/**
		 * Check if the Timers next run time is past and fire the run function if it is.
		 */
		final public function maybe_run_timer() {
			$next = $this->get_next_run();
			$run = $next->getTimestamp() <= time();

			if ( $run ) {
				$this->run();
			}
		}

		/**
		 * Run each action individually, set the last run time, and save the Timer.
		 */
		private function run() {
			do_action( sprintf( 'wppf_action_scheduler_before_timer_run_%s', $this->id ), $this->id );

			foreach ( $this->Actions as $Action ) {
				$Action->do();
				$this->last_run = time();
				$this->save();
			}

			do_action( sprintf( 'wppf_action_scheduler_after_timer_run_%s', $this->id ), $this->id );
		}

		/**
		 * Add an Action to the Timer instance, optionally update the Action and { $this->action_data } if it already exists.
		 * 
		 * @param \WPPF\v1_2_0\Plugin\Action_Scheduler\Action $Action The Action to try and add.
		 * @param bool $update Whether or not to replace the Action if it already exists by ID (default FALSE)
		 * 
		 * @return bool Whether or not the action was added.
		 */
		final public function add_action( Action $Action, bool $update = false ) {
			if ( true === $update || ! $this->has_action( $Action->id ) ) {
				$this->Actions[ $Action->id ] = $Action;
				$this->actions_data[ $Action->id ] = $Action->to_array();
				return true;
			}

			return false;
		}

		/**
		 * Return an Action instance from this Timer, if it exists, else return NULL.
		 * 
		 * @param string $action_id The ID of the Action to look for.
		 * 
		 * @return null|\WPPF\v1_2_0\Plugin\Action_Scheduler\Action The Action instance or NULL.
		 */
		final public function get_action( string $action_id ) {
			if ( $this->Actions[ $action_id ] ) {
				return $this->Actions[ $action_id ];
			}

			return null;
		}

		/**
		 * Check whether an action exists in { $this->action_data }.
		 * 
		 * @param string $action_id The ID of the Action to search for.
		 * 
		 * @return bool Whether the Action exists or not.
		 */
		final public function has_action( $action_id ) {
			return array_key_exists( $action_id, $this->Actions );
		}

		/**
		 * A wrapper function for \WPPF\v1_2_0\Plugin\Action_Scheduler\Timer_Manager::update_timer().
		 * 
		 * @param bool $merge Whether or not to merge existing Actions if the Timer already exists.
		 * 
		 * @return bool Whether or not the option meta was persisted.
		 */
		final public function save( bool $merge = false ) {
			$result = Timer_Manager::update_timer( $this, $merge );

			if ( false === $result ) {
				if ( Timer_Manager::get_timer( $this->id ) ) {
					return true;
				}
			} else {
				return true;
			}

			return false;
		}

		/**
		 * Merge Actions from another Timer instance into this instance.
		 * 
		 * @param \WPPF\v1_2_0\Plugin\Action_Scheduler\Timer $Timer The Timer instance to merge Actions from.
		 */
		final public function merge_actions( Timer $Timer ) {
			foreach ( $Timer->get_actions() as $Action ) {
				$this->add_action( $Action, true );
			}
		}

		/**
		 * Convert this object into an array for export/import.
		 * 
		 * @return array The array representation of this Timer.
		 */
		final public function to_array() {
			$timer_export = array();

			foreach ( self::$default_arguments as $property => $value ) {
				if ( property_exists( $this, $property ) ) {
					$timer_export[ $property ] = $this->{ $property };
				} else {
					$timer_export[ $property ] = $value;
				}
			}

			// Call sub class exports
			$timer_export = array_merge( $timer_export, $this->export_array() );

			return $timer_export;
		}

		/**
		 * Attempt to create a Timer super class instance using the 'timer_class' property associated with the Timer.
		 * 
		 * @param string $timer_id The ID of the Timer.
		 * 
		 * @return null|\WPPF\v1_2_0\Plugin\Action_Scheduler\Timer The timer that was instantiated or FALSE.
		 */
		final public static function instantiate_timer( string $timer_id, array $timer_options ) {

			if ( ! isset( $timer_options['timer_type'] ) ) {
				$message = "Tried to instantiate a Timer super class without providing the 'timer_type' property.";
				throw new \Exception( __( $message ) );
			}

			$timer_class = self::timer_class_from_type( $timer_options['timer_type'] );

			if ( '' === $timer_class ) {
				$message = sprintf( "Tried to instantiate a Timer super class using a class that could not be found. Tried to instantiate Timer by ID: %s", $timer_options['timer_type'] );
				throw new \Exception( __( $message ) );
			}

			return new $timer_class( $timer_id, $timer_options );
		}

		/**
		 * Map Timer types to class names, that way Timer data can be used by all Framework versions.
		 * 
		 * @param string $type The timer type to find a class name for.
		 * 
		 * @return string The classs name of the Timer type, or an empty string if not found.
		 */
		private static function timer_class_from_type( string $type ) {
			switch( $type ) {
				case 'interval-timer':
					return Interval_Timer::class;
				case 'simple-timer':
					return Simple_Timer::class;
				default:
					return '';
			}
		}

	}

}
