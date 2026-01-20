<?php
/**
 * WPPF Test Plugin
 *
 * Copyright (c) 2008–2026 DesignInk, LLC
 * Copyright (c) 2026 Kyle Niemiec
 *
 * This file is licensed under the GNU General Public License v3.0.
 * See the LICENSE file for details.
 */

defined( 'ABSPATH' ) or exit;

global $post;
$Test_Meta = new WPPF_Test_Post_Meta( $post );

?>

<div class="test-post-meta-box">
	<div class="times-saved">
		<h3>Times this Test Post was saved:</h3>

		<ul>

			<?php foreach ( $Test_Meta->times_saved as $time ) : $Time = new \DateTime( sprintf( '@%s', $time ) ); ?>

				<li><?php echo $Time->format( 'd/m/Y H:i:s' ); ?></li>

			<?php endforeach; ?>
		
		</ul>

		<div>
			Clear previous saves?
			<input
				name="<?php echo WPPF_Test_Post_Meta_Box::create_input_name( 'clear_saves' ); ?>"
				type="checkbox"
				value="yes"
			/>
		</div>
	</div>

	<hr />

	<div class="current-string">
		<h3>Current string value:</h3>

		<div>
			<sup>(This value should only contain letters, numbers, and spaces)</sup>
		</div>

		<div>
			<input
				name="<?php echo WPPF_Test_Post_Meta_Box::create_input_name( 'current_string' ); ?>"
				type="text"
				value="<?php echo $Test_Meta->current_string; ?>"
			/>
		</div>
	</div>

	<hr />

	<div class="toggle">
		<h3>A boolean toggle:</h3>

		<div>
			<input
				name="<?php echo WPPF_Test_Post_Meta_Box::create_input_name( 'is_toggle_active' ); ?>"
				type="checkbox"
				value="yes"
				<?php echo checked( $Test_Meta->is_toggle_active, true ); ?>
			/>
		</div>
	</div>
</div>
