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

use WPPF\v1_2_0\Plugin\Action_Scheduler\Cron_Manager;
use WPPF\v1_2_0\Plugin\Action_Scheduler\Timer_Manager;

$Timers = Timer_Manager::get_timers();
$Now = new \DateTime( 'now', new \DateTimeZone( 'GMT' ) );

?>

<div class="wppf-action-scheduler-viewer wrap">

	<h1>WPPF Action Scheduler Viewer</h1>

	<?php if ( empty( $Timers ) ) : ?>

		<div class="no-timers">No timers are available to view.</div>

	<?php else : ?>

		<table class="widefat striped">
			<thead>
				<tr>
					<th>Timer ID</th>
					<th>Timer Type</th>
					<th>Timer Last Run</th>
					<th>Timer Next Run</th>
					<th>Timer Actions</th>
				</tr>
			</thead>

			<tbody>

				<?php foreach ( $Timers as $id => $Timer ) : ?>
					<tr>
						<td><?php echo $id; ?></td>
						<td><?php echo $Timer->timer_type; ?></td>
						<td>

							<?php if ( $Timer->get_last_run() ) : ?>
								<?php echo $Timer->get_last_run()->format( 'Y-m-d @ H:i:s' ); ?>
							<?php else : ?>
								N/A
							<?php endif; ?>

						</td>
						<td><?php echo $Timer->get_next_run()->format( 'Y-m-d @ H:i:s' ); ?></td>
						<td>

							<?php foreach ( $Timer->get_actions() as $Action ) : ?>

								<?php if ( is_callable( $Action->action ) ) : ?>

									<?php if ( is_array( $Action->action ) ) : ?>
										<pre><?php echo implode( '::', $Action->action ); ?>()</pre>
									<?php else : ?>
										<pre><?php echo $Action->action; ?>()</pre>
									<?php endif; ?>

								<?php else : ?>
									<pre class="uncallable-action" title="Uncallable Action"><?php echo implode( '::', $Action->action ); ?>()</pre>
								<?php endif; ?>

							<?php endforeach; ?>

						</td>
					</tr>
				<?php endforeach; ?>

			</tbody>
		</table>

		<div class="current-time">
			<p>Next Cron Run: <?php echo ( new DateTime( sprintf( '@%s', wp_next_scheduled( Cron_Manager::WP_CRON_SCHEDULE_HOOK ) ) ) )->format( 'Y-m-d @ H:i:s' ); ?></p>
			<p>Current Time: <?php echo $Now->format( 'Y-m-d @ H:i:s' ); ?></p>
			<sup>*All times are in GMT</sup>
		</div>

	<?php endif; ?>

</div>
