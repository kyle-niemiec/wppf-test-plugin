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

namespace WPPF\v1_2_0\WooCommerce;

defined( 'ABSPATH' ) or exit;

if ( ! class_exists( '\WPPF\v1_2_0\WooCommerce\Email', false ) ) {

	/**
	 * An abstract class for mandating essential parts of the \WC_Email, and implementing email set-up and sending.
	 */
	abstract class Email extends \WC_Email {

		/** @var bool Whether or not the static Email class has been constructed. */
		protected static $_constructed = false;

		/**
		 * Abstract: mandate email ID field.
		 * 
		 * @return string The WC email ID.
		 */
		abstract public static function email_id();

		/**
		 * Abstract: mandate email method title field (appears in WC settings).
		 * 
		 * @return string The email method title.
		 */
		abstract public static function email_title();

		/**
		 * Abstract: mandate email description field (appears in WC settings)
		 * 
		 * @return string The email method description.
		 */
		abstract public static function email_description();

		/**
		 * Abstract: mandate email heading field.
		 * 
		 * @return string The email heading.
		 */
		abstract public static function email_heading();

		/**
		 * Abstract: mandate email subject field.
		 * 
		 * @return string The email subject.
		 */
		abstract public static function email_subject();

		/**
		 * Abstract: mandate email HTML template field.
		 * 
		 * @return string The email HTML template.
		 */
		abstract public static function email_html_template();

		/**
		 * Email contructor. Set mandatory properties.
		 */
		public function __construct() {
			// Set properties
			$this->id = static::email_id();
			$this->title = static::email_title();
			$this->description = static::email_description();
			$this->heading = static::email_heading();
			$this->subject = static::email_subject();
			$this->template_html = static::email_html_template();

			if ( ! $this->is_customer_email() ) {
				$this->recipient = $this->get_option( 'recipient', get_option( 'admin_email' ) );
			}

			parent::__construct();

			$email_action = sprintf( 'send_wc_email_%s', static::email_id() );
			add_action( $email_action, array( $this, 'trigger' ) );
			static::$_constructed = true;
		}

		/**
		 * The WooCommerce 'woocommerce_mail_content' action hook. Supply the find/replace functionality.
		 * 
		 * @param string $content The email text content.
		 * 
		 * @return string The body with all of the placeholders replaced.
		 */
		final public function _woocommerce_mail_content( string $content ) {
			return $this->format_string( $content );
		}

		/**
		 * A function for aliasing the email action hook set in the constructor.
		 * 
		 * @param mixed $object The WC email object.
		 */
		final public static function send_email( $object ) {
			WC()->mailer();
			$email_action = sprintf( 'send_wc_email_%s', static::email_id() );
			do_action( $email_action, $object );
		}

		/**
		 * An empty function for optionally setting up the email placeholders.
		 */
		public function set_placeholders() { }

		/**
		 * A placeholder function for setting the recipient.
		 */
		public function set_recipient() { $this->recipient = ''; }

		/**
		 * The WC email 'trigger' function expected.
		 * 
		 * @param mixed $object The WC email object.
		 */
		final public function trigger( $object ) {
			$this->object = $object;
			add_filter( 'woocommerce_mail_content', array( $this, '_woocommerce_mail_content' ) );

			if ( $this->customer_email ) {
				$this->set_recipient();
			}

			$this->set_placeholders();
			$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
		}

		/**
		 * Get content html using WooCommerce template functions.
		 *
		 * @return string
		 */
		final public function get_content_html() {
			return wc_get_template_html(
				$this->template_html,
				array(
					'object'				=> $this->object,
					'recipient'				=> $this->recipient,
					'email_heading'			=> $this->get_heading(),
					'additional_content'	=> $this->get_additional_content(),
					'sent_to_admin'			=> ! $this->customer_email,
					'plain_text'			=> false,
					'email'					=> $this,
				)
			);
		}

	}

}
