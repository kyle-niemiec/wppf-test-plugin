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

use WPPF\v1_2_0\Framework\Utility;
use WPPF\v1_2_0\WooCommerce\Email;

if ( ! trait_exists( '\WPPF\v1_2_0\WooCommerce\Email_Traits', false ) ) {

	/**
	 * A trait to expand the capabilities of the WooCommerce_Plugin to include email-related functionality.
	 */
	trait Email_Traits {

		/**
		 * The WooCommerce 'woocommerce_email_classes' action hook. Add the registered Emails to the list of WooCommerce Emails.
		 * 
		 * @param array The list of registered WooCommerce Emails.
		 * 
		 * @return array The list of registered WooCommerce Emails.
		 */
		final public function _woocommerce_email_classes( array $emails ) {
			$emails_dir = sprintf( '%s/%s/emails', dirname( $this->get_plugin_file() ), static::$includes_dir );
			$files = Utility::scandir( $emails_dir, 'files' );

			foreach ( $files as $file ) {
				$path = sprintf( '%s/%s', $emails_dir, $file );
				$email_class = $this->load_class_file( $path );

				if ( false !== $email_class ) {

					if ( is_subclass_of( $email_class, Email::class ) ) {
						$Email = new $email_class();
						$emails[ get_class( $Email ) ] = $Email;
					} else {
						$message = sprintf( "Successfully found class, '%s', but it does not appear to be a WooCommerce Email, make sure you are implementing %s in '%s'.", $email_class, Email::class, $path );
						Utility::doing_it_wrong( __METHOD__, __( $message ) );
					}

				}
			}

			return $emails;
		}

		/**
		 * Load the WC Email files.
		 */
		final public function load_emails() {
			$emails_dir = sprintf( '%s/%s/emails', dirname( $this->get_plugin_file() ), static::$includes_dir );

			if ( is_dir( $emails_dir ) ) {
				add_action( 'woocommerce_email_classes', array( $this, '_woocommerce_email_classes' ) );
			}
		}

	}

}
