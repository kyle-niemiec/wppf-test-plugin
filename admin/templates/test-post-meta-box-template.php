<?php
/**
 * DesignInk WP Framework Test Plugin
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
 * @author    DesignInk Digital
 * @copyright Copyright (c) 2008-2026, DesignInk, LLC
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

defined( 'ABSPATH' ) or exit;

global $post;
$Test_Meta = new DesignInk_Test_Post_Meta( $post );

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
				name="<?php echo DesignInk_Test_Post_Meta_Box::create_input_name( 'clear_saves' ); ?>"
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
				name="<?php echo DesignInk_Test_Post_Meta_Box::create_input_name( 'current_string' ); ?>"
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
				name="<?php echo DesignInk_Test_Post_Meta_Box::create_input_name( 'is_toggle_active' ); ?>"
				type="checkbox"
				value="yes"
				<?php echo checked( $Test_Meta->is_toggle_active, true ); ?>
			/>
		</div>
	</div>
</div>
