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

use DesignInk\WordPress\Framework\v1_1_2\Action_Scheduler\Form_Builder;

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