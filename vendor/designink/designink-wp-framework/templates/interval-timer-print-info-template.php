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
				<td><?php echo $last_run->format( 'Y-m-d H:i' ); ?></td>
			</tr>
		<?php else : ?>
			<tr>
				<th scope="row">Timer Start:</th>
				<td><?php echo $Timer->get_start_datetime()->format( 'Y-m-d H:i' ); ?></td>
			</tr>
		<?php endif; ?>

		<tr>
			<th scope="row">Next Timer Run:</th>
			<td><?php echo $next_run->format( 'Y-m-d H:i' ); ?></td>
		</tr>
	</table>
</div>