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

namespace DesignInk\WordPress\Framework\v1_1_2\Action_Scheduler;

defined( 'ABSPATH' ) or exit;

use DesignInk\WordPress\Framework\v1_1_2\Action_Scheduler\Form_Builder;

?>

<div class="timer-options">
	<label>
		Interval:
		<input type="number" min="0" max="99" pattern="([0-9]{2})" name="<?php echo Form_Builder::generate_form_input_name( $group, 'multiplier' ); ?>" value="1" required />
	</label>

	<select name="<?php echo Form_Builder::generate_form_input_name( $group, 'interval' ); ?>">

		<?php foreach ( array_keys( Interval_Timer::get_interval_types() ) as $interval ) : ?>
			<option value="<?php echo $interval; ?>"><?php echo ucwords( $interval ); ?>(s)</option>
		<?php endforeach; ?>

	</select>
</div>

<div class="interval-options">

	<?php
		woocommerce_form_field(
			Form_Builder::generate_form_input_name( $group, array( 'start', 'date' ) ),
			array(
				'label' => __( 'Day to start action: ' ),
				'id' => 'ds-recurring-action-form-' . $group . '-start-date',
				'options' => array(),
				'placeholder' => __( 'Select Date' ),
				'required' => false,
				'type' => 'date',
			)
		);
	?>

	<?php
		woocommerce_form_field(
			Form_Builder::generate_form_input_name( $group, array( 'start', 'time' ) ),
			array(
				'label' => __( 'Time to start action: ' ),
				'id' => 'ds-recurring-action-form-' . $group . '-start-time',
				'options' => array(),
				'placeholder' => __( 'Select Time' ),
				'required' => true,
				'type' => 'time',
			)
		);
	?>
	
</div>