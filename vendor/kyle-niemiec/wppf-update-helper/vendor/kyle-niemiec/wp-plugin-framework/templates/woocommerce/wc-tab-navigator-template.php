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

?>

<div class="panel-wrap">
	<ul class="wc-tabs">

		<?php // Render tabs ?>
		<?php foreach ( $Navigator->get_tabs() as $tab ) : ?>
			<li class="<?php echo $tab['id']; ?> <?php echo array_key_exists( 'active', $tab ) && $tab['active'] ? 'active' : ''; ?>" data-tab-id="<?php echo $tab['id']; ?>">
				<a href="javascript:void(0);">
					<?php echo $tab['label']; ?>
				</a>
			</li>
		<?php endforeach; ?>

	</ul>

	<?php // Render content ?>
	<?php foreach ( $Navigator->get_tabs() as $tab ) : ?>
		<div class="panel woocommerce_options_panel <?php echo $tab['id']; ?>">
			<?php if ( is_callable( $tab['render'] ) ) : call_user_func_array( $tab['render'], array( $tab['render_args'] ) ); endif; ?>
		</div>
	<?php endforeach; ?>

</div>
