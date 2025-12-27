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

namespace DesignInk\WordPress\Framework\v1_1_2\WooCommerce;

defined( 'ABSPATH' ) or exit;

use DesignInk\WordPress\Framework\v1_1_2\Utility;
use DesignInk\WordPress\Framework\v1_1_2\WooCommerce\Email;

if ( ! trait_exists( '\DesignInk\WordPress\Framework\v1_1_2\WooCommerce\Email_Traits', false ) ) {

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
