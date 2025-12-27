<?php
/**
 * DesignInk WP Framework Test Plugin
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
 * @author    DesignInk Digital
 * @copyright Copyright (c) 2008-2026, DesignInk, LLC
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

defined( 'ABSPATH' ) or exit;

use DesignInk\WordPress\Framework\v1_1_2\WooCommerce\Email;

if ( ! class_exists( 'DesignInk_Test_Email', false ) ) {

	/**
	 * A class for managing a test email to be sent out.
	 */
	final class DesignInk_Test_Email extends Email {

		/**
		 * Required abstract: mandate email ID field.
		 * 
		 * @return string The WC email ID.
		 */
		final public static function email_id() { return 'designink-test-email'; }

		/**
		 * Required abstract: mandate email method title field (appears in WC settings).
		 * 
		 * @return string The email method title.
		 */
		final public static function email_title() { return 'DesignInk Test Email'; }

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
		final public static function email_heading() { return 'A Test Email from DesignInk'; }

		/**
		 * Required abstract: mandate email subject field.
		 * 
		 * @return string The email subject.
		 */
		final public static function email_subject() { return 'DesignInk Test Mail'; }

		/**
		 * Required abstract: mandate email HTML template field.
		 * 
		 * @return string The email HTML template.
		 */
		final public static function email_html_template() { return 'emails/designink-test-email.php'; }

	}

}
