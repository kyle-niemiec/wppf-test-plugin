<?php
/**
 * WPPF Test Plugin
 *
 * Copyright (c) 2008–2026 DesignInk, LLC
 * Copyright (c) 2026 Kyle Niemiec
 *
 * This file is licensed under the GNU General Public License v3.0.
 * See the LICENSE file for details.
 */

defined( 'ABSPATH' ) or exit;

use WPPF\v1_2_0\WooCommerce\Email;

if ( ! class_exists( 'WPPF_Test_Email', false ) ) {

	/**
	 * A class for managing a test email to be sent out.
	 */
	final class WPPF_Test_Email extends Email {

		/**
		 * Required abstract: mandate email ID field.
		 * 
		 * @return string The WC email ID.
		 */
		final public static function email_id() { return 'wppf-test-email'; }

		/**
		 * Required abstract: mandate email method title field (appears in WC settings).
		 * 
		 * @return string The email method title.
		 */
		final public static function email_title() { return 'WPPF Test Email'; }

		/**
		 * Required abstract: mandate email description field (appears in WC settings)
		 * 
		 * @return string The email method description.
		 */
		final public static function email_description() { return 'Send a test email on demand.'; }

		/**
		 * Required abstract: mandate email heading field.
		 * 
		 * @return string The email heading.
		 */
		final public static function email_heading() { return 'A Test Email from WPPF'; }

		/**
		 * Required abstract: mandate email subject field.
		 * 
		 * @return string The email subject.
		 */
		final public static function email_subject() { return 'WPPF Test Mail'; }

		/**
		 * Required abstract: mandate email HTML template field.
		 * 
		 * @return string The email HTML template.
		 */
		final public static function email_html_template() { return 'emails/wppf-test-email.php'; }

	}

}
