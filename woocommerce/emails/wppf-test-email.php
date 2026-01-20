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

?>

<?php do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<h2>A Test Email for a great purpose!</h2>

<p>Yes, today you have subscribed to SPAM!</p>

<?php do_action( 'woocommerce_email_footer', $email ); ?>
