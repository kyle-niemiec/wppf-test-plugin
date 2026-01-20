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

namespace WPPF\v1_2_0\Action_Scheduler;

defined( 'ABSPATH' ) or exit;

use WPPF\v1_2_0\Plugin\Action_Scheduler\Form_Builder;
use WPPF\v1_2_0\Plugin\Action_Scheduler\Simple_Timer;

?>

<div class="timer-options">
	<label>
		Interval:
		<input type="number" min="0" max="99" pattern="([0-9]{2})" name="<?php echo Form_Builder::generate_form_input_name( $group, 'multiplier' ); ?>" value="1" required />
	</label>

	<select name="<?php echo Form_Builder::generate_form_input_name( $group, 'interval' ); ?>">

		<?php foreach ( array_keys( Simple_Timer::get_interval_types() ) as $interval ) : ?>
			<option value="<?php echo $interval; ?>"><?php echo ucwords( $interval ); ?>(s)</option>
		<?php endforeach; ?>

	</select>
</div>
