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

use WPPF\v1_2_0\Plugin\Action_Scheduler\Form_Builder;

$now = new \DateTime( 'now', new \DateTimeZone( 'GMT' ) );

?>

<div class="action-scheduler-timer-builder">
	<?php wp_nonce_field( Form_Builder::FORM_NONCE_ACTION, Form_Builder::FORM_NONCE_NAME ); ?>

	<h3>Action Timer</h3>

	<p>The current GMT time is: <strong><?php echo $now->format( 'Y-m-d H:i' ); ?></strong></p>

	<div class="nav-tab-wrapper woo-nav-tab-wrapper">
		<?php foreach ( Form_Builder::get_timer_classes() as $timer_class ) : ?>
			<div class="nav-tab" data-timer-type="<?php echo $timer_class::timer_type_id(); ?>">
				<?php echo $timer_class::timer_label(); ?>
			</div>
		<?php endforeach; ?>
	</div>

	<div class="timer-forms">
		<?php foreach ( Form_Builder::get_timer_classes() as $timer_class ) : ?>
			<div class="timer-form" data-timer-type="<?php echo $timer_class::timer_type_id(); ?>">
				<?php $timer_class::print_form( $group ); ?>
			</div>
		<?php endforeach; ?>
	</div>

	<input type="hidden" name="<?php echo Form_Builder::generate_form_input_name( $group, 'timer_type' ); ?>" value="" class="timer-type" />

	<hr />
</div>