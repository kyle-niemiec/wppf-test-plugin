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

$now = new \DateTime( 'now' );
$next_run = $Timer->get_next_run();
$last_run = $Timer->get_last_run();

?>

<div class="series-timer-info">
	<table style="text-align:left; margin:1rem 0;">
		<tr>
			<th scope="row">Timer Type:</th>
			<td><?php echo $Timer->timer_label(); ?></td>
		</tr>

		<tr>
			<th scope="row">Timer Interval:</th>
			<td><?php printf( '%s %s(s)', $Timer->multiplier, $Timer->interval ); ?></td>
		</tr>

		<?php if ( $last_run ) : ?>
			<tr>
				<th scope="row">Last Timer Run:</th>
				<td><?php echo $last_run->format( 'Y-m-d H:i:s' ); ?></td>
			</tr>
		<?php else : ?>
			<tr>
				<th scope="row">Timer Start:</th>
				<td>Immediately</td>
			</tr>
		<?php endif; ?>

		<tr>
			<th scope="row">Next Timer Run:</th>
			<td><?php echo $next_run->format( 'Y-m-d H:i:s' ); ?></td>
		</tr>
	</table>
</div>