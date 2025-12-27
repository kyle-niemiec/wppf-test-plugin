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

defined( 'ABSPATH' ) or exit;

use DesignInk\WordPress\Framework\v1_1_2\Action_Scheduler\Cron_Manager;
use DesignInk\WordPress\Framework\v1_1_2\Action_Scheduler\Timer_Manager;

$Timers = Timer_Manager::get_timers();
$Now = new \DateTime( 'now', new \DateTimeZone( 'GMT' ) );

?>

<div class="designink-action-scheduler-viewer wrap">

	<h1>DesignInk Action Scheduler Viewer</h1>

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
