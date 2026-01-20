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

defined( 'ABSPATH' ) or exit;

?>

<div class="notice notice-<?php echo $Notice->type ?> is-dismissible">
	<?php // Optional notice heading ?>
	<?php if ( ! empty( $Notice->options['header'] ) ) : ?>
		<h3><?php _e( $Notice->options['header'] ); ?></h3>
	<?php endif; ?>

	<?php // Notice message ?>
	<p><?php _e( $Notice->message ); ?></p>

	<?php // Optional status code ?>
	<?php if ( ! empty( $Notice->options['status_code'] ) ) : ?>
		<div>
			<sub>Status code: <?php echo $Notice->options['status_code']; ?></sub>
		</div>
	<?php endif; ?>

	<?php // Optional notice hint ?>
	<?php if ( ! empty( $Notice->options['hint'] ) ) : ?>
		<div>
			<sup>Hint: <?php _e( $Notice->options['hint'] ); ?></sup>
		</div>
	<?php endif; ?>
</div>
