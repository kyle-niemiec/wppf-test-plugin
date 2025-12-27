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
use DesignInk\WordPress\Framework\v1_1_2\Action_Scheduler\Simple_Timer;

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
